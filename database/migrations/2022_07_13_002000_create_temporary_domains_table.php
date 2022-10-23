<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('temporary_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_domain_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('domain_type_id')->nullable();
            $table->unsignedBigInteger('dc_location_id')->nullable();
            $table->string('domain_register_from')->default('Vodien');
            $table->string('domain_name');
            $table->date('registration_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('ip')->nullable();
            $table->boolean('available')->default(1);
            $table->boolean('active')->default(1);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->foreign('dc_location_id')->references('id')->on('data_center_locations')->nullOnDelete();
            // $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('deleted_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('domain_type_id')->references('id')->on('domain_types')->nullOnDelete();
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
        Schema::dropIfExists('temporary_domains');
        Schema::enableForeignKeyConstraints();
    }
}
