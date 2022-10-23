<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostingDiscountMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_discount_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('hosting_plan_id')->nullable();
            $table->unsignedBigInteger('hosting_discount_id')->nullable();
            $table->foreign('hosting_plan_id')->references('id')->on('hosting_plans')->cascadeOnDelete();
            $table->foreign('hosting_discount_id')->references('id')->on('hosting_plan_discounts')->cascadeOnDelete();
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
        Schema::dropIfExists('hosting_discount_mappings');
        Schema::enableForeignKeyConstraints();
    }
}
