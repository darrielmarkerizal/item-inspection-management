<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();
            $table->string('service_type'); // new_arrival | maintenance | on_spot
            $table->foreignId('inspection_type_id')->nullable()->constrained('inspection_types')->nullOnDelete();
            $table->foreignId('scope_of_work_id')->nullable()->constrained('scopes_of_work')->nullOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('related_to')->nullable();
            $table->string('dvc_code')->nullable();
            $table->date('date_submitted')->nullable();
            $table->date('estimated_completion_date')->nullable();
            $table->string('status')->default('open')->index(); // open | for_review | completed
            $table->text('note_to_yard')->nullable();
            $table->boolean('charge_to_customer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
