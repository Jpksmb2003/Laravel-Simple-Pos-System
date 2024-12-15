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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('product_code')->unique();  // Unique product code
            $table->string('name');  // Name of the item
            $table->integer('quantity');  // Quantity of the item
            $table->decimal('price', 8, 2);  // Price with 8 digits total and 2 decimal places
            $table->text('description')->nullable();  // Optional description
            $table->timestamps();  // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
