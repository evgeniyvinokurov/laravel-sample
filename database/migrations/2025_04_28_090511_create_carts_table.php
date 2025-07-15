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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->unsignedBigInteger("product");
            $table->foreign('product')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedBigInteger("user");
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');

            $table->integer("quantity");
            $table->float("price");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
