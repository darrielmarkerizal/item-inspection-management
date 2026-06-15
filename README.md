# Item Inspection Management System

A warehouse inspects pipe — casing and tubing, the kind that goes down an oil well — before any of it is cleared for use. Each inspection samples specific lots of an item, records the result, and runs through three states: **Open** while you're still editing it, **For Review** once it's submitted, **Completed** once it's signed off. Completed is the end. After that, it's locked.

That's the system. A Laravel 12 API, a Vue 3 single-page app, REST between them.

Two rules did most of the steering. The dropdown data loads once at startup and never again — the brief is blunt about it: choosing a value from a dropdown must not touch the server. And an inspection's content freezes the second it leaves Open. Frozen on the server, not greyed out in the UI and trusted to behave.

## Tech stack

| Layer | What |
|---|---|
| Backend | PHP 8.4, Laravel 12, `nwidart/laravel-modules`, `spatie/laravel-query-builder` |
| Backend tests | Pest 3 / PHPUnit 11 |
| Frontend | Vue 3, Vuex 4, Vue Router, Axios, Element Plus, Tailwind v4, Vite |
| Frontend tests | Vitest, `@vue/test-utils` |
| Database | SQLite by default; Postgres or MySQL by changing `.env` |

## Running it

You need PHP 8.4+ with Composer and Node 20.19+.

Backend first:

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

php artisan serve            # http://localhost:8000
```

SQLite is the default, so there's no database server to stand up. `migrate --seed` creates the file and fills it. Want Postgres or MySQL instead? Set the `DB_*` values in `.env` before you migrate.

Frontend, second terminal:

```bash
cd frontend
npm install
cp .env.example .env         # VITE_API_PROXY_TARGET defaults to http://localhost:8000
npm run dev                  # http://localhost:5173
```

Open http://localhost:5173. Vite proxies `/api` to the backend, so there's no CORS to set up and the SPA just calls a relative `/api/v1`.

## Architecture

The backend splits into four domain modules under `backend/Modules/`:

- **Core** — the shared parts: the `InspectionStatus` and `ServiceType` enums, the `ApiResponse` envelope, the domain exceptions, the `CsvImporter`.
- **MasterData** — reference data and the one prefetch endpoint.
- **Inventory** — `items` and their `item_lots`, which feed the Order Information cascading filter.
- **Inspection** — inspections, their items and lots, charges, status history, and the workflow.

Every request takes the same path, which keeps controllers thin and the rules where I can test them:

```
Route → Controller → FormRequest        (validation)
                  → Service             (business logic, transactions, workflow)
                      → Repository       (Eloquent / Spatie QueryBuilder)
                  → API Resource         (response shaping)
                  → ApiResponse          (envelope)
```

Two choices are worth pointing at. Every response — success or failure — comes back as `{ success, message, data, errors }`, and the failure cases (illegal transition, locked inspection, validation, not-found, bad query) get rendered in one place, `bootstrap/app.php`, instead of being re-handled in every controller. And when an inspection is created, the lot's attributes — `lot_no`, `allocation`, `owner`, `condition` — are copied onto the inspection row. That copy is on purpose. A Completed inspection is a record, and a record shouldn't change because somebody edited a lot in inventory three months later.

On the frontend, `App.vue` is the shell. It prefetches the master data once, then hands off to the router. State sits in two Vuex modules — `master` for the cached reference data, `inspections` for everything else — and every API call goes through a thin `services/` layer so components never touch Axios directly. Dropdowns and the cascading lot filter read straight from the store. That's the whole point of prefetching: after the first load, picking a value is a local operation.

## The data, and where it comes from

The brief says reference data arrives as Excel. None was attached. So I treated it as an Excel-to-CSV export and wrote a small importer rather than pulling in an `.xlsx` parser for a file that doesn't exist. The CSVs live in `Modules/MasterData/database/data/` and `Modules/Inventory/database/data/`; `Modules\Core\Support\CsvImporter` reads them into header-keyed rows; the seeders resolve foreign keys by code or name and upsert, so `migrate --seed` is safe to run twice.

Order is MasterData, then Inventory, then Inspection. That last seeder creates three sample inspections — one in each status — so every listing tab and the detail page have something real to show on first load.

## Tests

```bash
cd backend && php artisan test                 # Pest, in-memory SQLite
cd frontend && npm run test:unit -- --run      # Vitest
```

The backend tests go where the risk is: every legal and illegal status transition, the edit-lock returning 403, and validation checked both ways — good payloads through, bad ones rejected (missing items, empty lots, a quantity above the lot's stock, a wrong enum). The frontend tests cover the parts that break quietly — the master cache proving it fetches once, the cascading filter resolving a lot, the create payload dropping its display-only fields before it's sent.

## API reference

Base URL `http://localhost:8000/api/v1`. Everything uses the `{ success, message, data, errors }` envelope. A Postman collection with saved example responses — the error cases included — sits in [`postman/`](postman/Inspection-Management-API.postman_collection.json).

| Method | Endpoint | Purpose |
|---|---|---|
| `GET` | `/master-data` | Every dropdown plus the `service_type`/`status` enums, in one prefetch |
| `GET` | `/items` | Items with nested lots — the cascading-filter source |
| `POST` | `/scopes-of-work` | Create a scope (the "+ New SOW" action) |
| `GET` | `/inspections` | Paginated list with `filter[status]`, `sort`, `per_page`, and `status_counts` for the tab badges |
| `POST` | `/inspections` | Create one (saved as `open`) |
| `GET` | `/inspections/{id}` | Full detail; `?include=statusHistories` for the audit timeline |
| `PUT` | `/inspections/{id}` | Replace content — only while `open` |
| `PATCH` | `/inspections/{id}/status` | Move it through the workflow |

The list endpoint follows Spatie's conventions, e.g. `/inspections?filter[status]=open&sort=-date_submitted`.

## The workflow

```
open ──submit──▶ for_review ──complete──▶ completed
```

Forward only. The `InspectionStatus` enum owns the rules, so an illegal jump like `open → completed` gets a 422 from the same place whether it came from the API or a client built later. Editing is allowed only while `open`; a `PUT` against anything else returns 403. `request_no` is generated server-side as `REQ-YYYY-NNNN`, and every move is written to `inspection_status_histories`.

## Decisions, and what I left out

Where the brief left room, here's what I picked and why.

**Status is an enum; inspection type is a table.** Workflow status and service type are small fixed sets that drive behaviour — the transition rules live on the enum itself — so they're enums. Inspection type, location, customer and the rest are open-ended lists someone might add to, so they're tables. The "New / Draft / In Progress" labels in the mockups all map onto the three real statuses, and the three tabs are those statuses. The "Pending Journal" tab is gone, because the brief says build three.

**Quantity sits on the lot, not the item.** The Order Information mockup puts Qty Required and Inspection Required on each lot row, so that's where they live. The server rejects any quantity above the lot's available stock.

**Conditions carry a colour.** Not in the spec. I added it so the condition badges — Good green, Quarantine red — come from data instead of a hardcoded switch in the frontend.

**Charges are read-only.** The detail page shows them. There's no "add charge" flow, because adding charges wasn't a functional requirement, so I didn't build the write side.

**There's no edit screen.** The `PUT` endpoint and the edit-lock both exist and are tested — you just can't reach them from the UI yet. Editing is still blocked on the server once an inspection leaves Open, which was the part that actually mattered.

**No auth.** The brief doesn't ask for it, so the API is open and the Sanctum scaffolding is gone.

Master data is read from CSV rather than `.xlsx`, and the SPA assumes it's served behind a proxy on the same origin in production — the same shape as the Vite proxy locally.

## Demo video

_Walkthrough:_ **<add link>**

Roughly: the list with its tabs and sorting; creating an inspection — metadata, a scope made on the fly, the cascading order items; then the detail page, walked through Submit for Review and Complete with the history timeline filling in behind it.
