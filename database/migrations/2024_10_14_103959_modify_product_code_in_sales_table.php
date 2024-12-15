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
        Schema::table('sales', function (Blueprint $table) {
            // Drop the unique constraint if it exists
            $table->dropUnique(['product_code']); // Adjust this if the unique index was named differently
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Restore the unique constraint if needed
            $table->unique('product_code'); // You can add the unique constraint back if you rollback
        });
    }
};
