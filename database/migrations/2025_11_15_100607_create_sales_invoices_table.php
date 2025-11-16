<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_no')->unique();
            $table->date('invoice_date');
            $table->string('salesman_code', 10)->nullable();
            $table->string('salesman_name', 100)->nullable();
            $table->string('customer_code', 20);
            $table->string('customer_name', 150);
            $table->string('address', 255)->nullable();
            $table->string('remarks', 255);
            $table->decimal('previous_balance', 10, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_invoices');
    }
}
