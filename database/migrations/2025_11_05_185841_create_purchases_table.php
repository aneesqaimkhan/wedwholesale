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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_no');
            $table->date('invoice_date');
            $table->string('company_code', 20)->nullable();
            $table->string('company_name', 150)->nullable();
            $table->string('address')->nullable();
            $table->string('remarks')->nullable();
            $table->decimal('previous_balance', 10, 2)->default(0.00);
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->string('product_code', 50)->nullable();
            $table->string('product_name', 150)->nullable();
            $table->string('pack')->nullable();
            $table->integer('box')->default(0);
            $table->integer('pcs')->default(0);
            $table->decimal('rate', 10, 2)->default(0.00);
            $table->string('rate_type', 1)->default('N');
            $table->decimal('b_per_box', 10, 2)->default(0.00);
            $table->decimal('stx', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2)->default(0.00);
            
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};


