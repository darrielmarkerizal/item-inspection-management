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
        Schema::create('scope_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scope_of_work_id')->constrained('scopes_of_work')->cascadeOnDelete();
            $table->foreignId('inspection_parameter_id')->constrained('inspection_parameters')->cascadeOnDelete();
            $table->unique(['scope_of_work_id', 'inspection_parameter_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scope_parameter');
    }
};
