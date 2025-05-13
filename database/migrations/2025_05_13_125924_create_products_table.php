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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key for 'id'
            $table->string('name'); // 'name' is a required field (string)
            $table->string('description')->nullable(); // 'description' is optional (string)
            $table->float('price'); // 'price' is required (float)
            $table->integer('stock_quantity'); // 'stock_quantity' is required 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
