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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("number");
            $table->integer("quantity");
            $table->unsignedBigInteger("link");
            $table->foreign('link')->references('id')->on('users')->onDelete('cascade');
            
            $table->string("name");
            $table->string("status");
            $table->string("comment");

            $table->unsignedBigInteger("product");
            $table->foreign('product')->references('id')->on('products')->onDelete('cascade');
            $table->float("price");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
