<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('product_code', 50)->nullable();
            $table->string('product_name', 150)->nullable();
            $table->string('pack', 50)->nullable();
            $table->integer('box')->default(0);
            $table->integer('pcs')->default(0);
            $table->decimal('rate', 10, 2)->default(0);
            $table->string('rate_type', 1)->default('N'); // N, T, or R
            $table->decimal('b_per_box', 10, 2)->default(0);
            $table->decimal('stx', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            
            $table->foreign('invoice_id')->references('id')->on('sales_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoice_items');
    }
}
