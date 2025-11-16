<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentColumnToReceiptPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
            // Add payment column first
            $table->decimal('payment', 10, 2)->default(0)->after('amount');
        });
        
        // Copy amount to receipt (using raw SQL since we'll rename later)
        \DB::statement('ALTER TABLE receipt_payments CHANGE amount receipt DECIMAL(10,2)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipt_payments', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
        
        // Rename receipt back to amount
        \DB::statement('ALTER TABLE receipt_payments CHANGE receipt amount DECIMAL(10,2)');
    }
}
