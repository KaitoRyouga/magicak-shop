<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('website_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('user_website_id')->nullable();
            $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('user_website_id')->references('id')->on('user_websites')->nullOnDelete();
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('website_messages');
        Schema::enableForeignKeyConstraints();
    }
}
