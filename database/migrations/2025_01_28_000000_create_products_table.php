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
            $table->id('product_id');
            $table->string('product_code', 50);
            $table->string('product_name', 255);
            $table->integer('pcs_in_box')->default(0);
            $table->integer('supplier_id')->nullable();
            $table->enum('bonus_type', ['A', 'D'])->default('D'); // A=Add, D=Deduct
            $table->date('expire_date')->nullable();
            $table->string('packing', 100)->nullable();
            $table->integer('opening_qty_box')->default(0);
            $table->integer('opening_qty_pcs')->default(0);
            $table->integer('minimum_stock_box')->default(0);
            $table->integer('minimum_stock_pcs')->default(0);
            $table->decimal('n_price_box', 10, 2)->default(0.00);
            $table->decimal('n_price_pcs', 10, 2)->default(0.00);
            $table->decimal('t_price_box', 10, 2)->default(0.00);
            $table->decimal('t_price_pcs', 10, 2)->default(0.00);
            $table->decimal('r_price_box', 10, 2)->default(0.00);
            $table->decimal('r_price_pcs', 10, 2)->default(0.00);
            $table->decimal('sales_tax', 5, 2)->default(0.00); // e.g., 17.00 for 17%
            $table->decimal('rate_in_percent', 5, 2)->default(0.00);
            $table->enum('default_rate_type', ['T', 'R', 'N'])->default('N');
            $table->integer('company_id')->nullable();
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

