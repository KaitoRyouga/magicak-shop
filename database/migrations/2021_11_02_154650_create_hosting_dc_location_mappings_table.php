<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostingDcLocationMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_dc_location_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('hosting_plan_id')->nullable();
            $table->unsignedBigInteger('dc_location_id')->nullable();
            $table->foreign('hosting_plan_id')->references('id')->on('hosting_plans')->cascadeOnDelete();
            $table->foreign('dc_location_id')->references('id')->on('data_center_locations')->nullOnDelete();
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
        Schema::dropIfExists('hosting_dc_location_mappings');
        Schema::enableForeignKeyConstraints();
    }
}
