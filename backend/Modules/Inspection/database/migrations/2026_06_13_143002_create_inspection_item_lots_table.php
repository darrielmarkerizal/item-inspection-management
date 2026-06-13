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
        Schema::create('inspection_item_lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_item_id')->constrained('inspection_items')->cascadeOnDelete();
            $table->foreignId('item_lot_id')->nullable()->constrained('item_lots')->nullOnDelete();
            $table->string('lot_no')->nullable();      // snapshot
            $table->string('allocation')->nullable();  // snapshot
            $table->string('owner')->nullable();       // snapshot
            $table->string('condition')->nullable();   // snapshot
            $table->decimal('qty', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_item_lots');
    }
};
