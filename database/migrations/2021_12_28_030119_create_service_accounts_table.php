<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->string('post_code');
            $table->integer('country_code');
            $table->string('phone');
            $table->string('email');
            $table->string('account_type');
            $table->string('username');
            $table->string('password');
            $table->string('mobile');
            $table->string('business_name');
            $table->string('business_number_type');
            $table->string('business_number');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_accounts');
    }
}
