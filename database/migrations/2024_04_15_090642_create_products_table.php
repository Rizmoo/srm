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
            $table->id();
            $table ->string('name');
            $table ->unsignedInteger('quantity')->default(0);
            $table ->unsignedInteger('reorder_stock')->default(0);
            $table ->unsignedInteger('fulfilled_orders')->default(0);
            $table ->unsignedInteger('unfulfilled_orders')->default(0);
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
