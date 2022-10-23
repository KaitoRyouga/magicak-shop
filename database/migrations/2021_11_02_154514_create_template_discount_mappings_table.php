<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateDiscountMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('template_discount_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('template_discount_id')->nullable();
            $table->foreign('template_id')->references('id')->on('templates')->cascadeOnDelete();
            $table->foreign('template_discount_id')->references('id')->on('template_discounts')->cascadeOnDelete();
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
        Schema::dropIfExists('template_discount_mappings');
        Schema::enableForeignKeyConstraints();
    }
}
