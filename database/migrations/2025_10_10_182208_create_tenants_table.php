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
        Schema::connection('master')->create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique();
            $table->string('database_name')->unique();
            $table->string('database_host')->default('127.0.0.1');
            $table->integer('database_port')->default(3306);
            $table->string('database_username')->default('root');
            $table->string('database_password')->nullable();
            $table->string('company_name');
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->date('license_start_date');
            $table->date('license_end_date');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // For storing additional tenant-specific settings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('master')->dropIfExists('tenants');
    }
};
