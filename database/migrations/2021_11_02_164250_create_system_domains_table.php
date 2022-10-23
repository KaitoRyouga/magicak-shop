<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('system_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_domain_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('domain_type_id')->nullable();
            $table->string('ip')->nullable();
            $table->string('domain_register_from')->default('Vodien');
            $table->string('domain_name')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('domain_auth_key')->nullable();
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            // $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('deleted_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('system_domains');
        Schema::enableForeignKeyConstraints();
    }
}
