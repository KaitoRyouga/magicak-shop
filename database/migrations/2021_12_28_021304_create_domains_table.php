<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_domain_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('domain_type_id')->nullable();
            $table->string('domain_register_from')->default('Vodien');
            $table->string('domain_name');
            $table->integer('domain_time');
            $table->date('registration_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->boolean('domain_migration')->default(false)->nullable();
            $table->string('domain_migration_status')->nullable();
            $table->string('ip')->nullable();
            $table->boolean('is_transfer')->nullable()->default(false);
            $table->string('domain_auth_key')->nullable();
            $table->string('price')->nullable()->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
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
        Schema::dropIfExists('domains');
        Schema::enableForeignKeyConstraints();
    }
}
