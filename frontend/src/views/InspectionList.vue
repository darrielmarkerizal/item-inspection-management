<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import StatusTag from '@/components/StatusTag.vue'

const router = useRouter()
const store = useStore()

const TABS = [
  { name: 'open', label: 'Open' },
  { name: 'for_review', label: 'For Review' },
  { name: 'completed', label: 'Completed' },
]

const SORTABLE = {
  request_no: 'request_no',
  date_submitted: 'date_submitted',
  estimated_completion_date: 'estimated_completion_date',
  status: 'status',
  related_to: 'related_to',
}

const DEFAULT_SORT = '-created_at'

const activeTab = ref('open')
const sort = ref(DEFAULT_SORT)
const page = ref(1)

const list = computed(() => store.state.inspections.list)
const pagination = computed(() => store.state.inspections.pagination)
const statusCounts = computed(() => store.state.inspections.statusCounts)
const loading = computed(() => store.state.inspections.loading)
const error = computed(() => store.state.inspections.error)

function fetch() {
  store
    .dispatch('inspections/fetchList', {
      'filter[status]': activeTab.value,
      sort: sort.value,
      page: page.value,
    })
    .catch(() => {})
}

function changeTab() {
  page.value = 1
  fetch()
}

function handleSort({ prop, order }) {
  sort.value = !order || !SORTABLE[prop] ? DEFAULT_SORT : (order === 'descending' ? '-' : '') + SORTABLE[prop]
  page.value = 1
  fetch()
}

function changePage(next) {
  page.value = next
  fetch()
}

function goToDetail(row) {
  router.push({ name: 'inspections.show', params: { id: row.id } })
}

onMounted(fetch)
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">Inspections</h2>
      <el-button type="primary" @click="router.push({ name: 'inspections.create' })">
        Create Inspection
      </el-button>
    </div>

    <el-tabs v-model="activeTab" @tab-change="changeTab">
      <el-tab-pane v-for="tab in TABS" :key="tab.name" :name="tab.name">
        <template #label> {{ tab.label }} ({{ statusCounts[tab.name] ?? 0 }}) </template>
      </el-tab-pane>
    </el-tabs>

    <el-alert
      v-if="error"
      :title="error"
      type="error"
      show-icon
      :closable="false"
      class="mb-3"
    >
      <el-button size="small" @click="fetch">Retry</el-button>
    </el-alert>

    <el-table
      v-loading="loading"
      :data="list"
      row-key="id"
      class="cursor-pointer"
      @row-click="goToDetail"
      @sort-change="handleSort"
    >
      <el-table-column prop="request_no" label="Request No" sortable="custom" min-width="150" />
      <el-table-column label="Location" min-width="120">
        <template #default="{ row }">{{ row.location ?? '-' }}</template>
      </el-table-column>
      <el-table-column label="Scope of Work" min-width="190">
        <template #default="{ row }">{{ row.scope_of_work ?? '-' }}</template>
      </el-table-column>
      <el-table-column label="Type" min-width="120">
        <template #default="{ row }">{{ row.inspection_type ?? '-' }}</template>
      </el-table-column>
      <el-table-column
        prop="date_submitted"
        label="Date Submitted"
        sortable="custom"
        min-width="150"
      >
        <template #default="{ row }">{{ row.date_submitted ?? '-' }}</template>
      </el-table-column>
      <el-table-column
        prop="estimated_completion_date"
        label="ECD"
        sortable="custom"
        min-width="120"
      >
        <template #default="{ row }">{{ row.estimated_completion_date ?? '-' }}</template>
      </el-table-column>
      <el-table-column prop="related_to" label="Related To" sortable="custom" min-width="140">
        <template #default="{ row }">{{ row.related_to ?? '-' }}</template>
      </el-table-column>
      <el-table-column prop="items_count" label="Job Parts" width="110" align="center" />
      <el-table-column prop="status" label="Status" sortable="custom" width="140">
        <template #default="{ row }">
          <StatusTag :status="row.status" />
        </template>
      </el-table-column>
      <template #empty>
        <el-empty description="No inspections" />
      </template>
    </el-table>

    <div class="flex justify-end mt-4">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="pagination.total"
        :page-size="pagination.per_page"
        :current-page="pagination.current_page"
        @current-change="changePage"
      />
    </div>
  </div>
</template>
