<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceNoAndSupplierToReceiptPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
            $table->integer('invoice_no')->nullable()->after('id');
            $table->string('supplier_code', 20)->nullable()->after('entity_name');
            $table->string('supplier_name', 150)->nullable()->after('supplier_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
            $table->dropColumn(['invoice_no', 'supplier_code', 'supplier_name']);
        });
    }
}
