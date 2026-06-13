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
        Schema::create('inspection_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained('inspections')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->timestamp('changed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_status_histories');
    }
};
