<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostingClusterMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_cluster_mappings', function (Blueprint $table) {
            $table->unsignedBigInteger('hosting_cluster_id')->nullable();
            $table->unsignedBigInteger('hosting_plan_id')->nullable();
            $table->foreign('hosting_cluster_id')->references('id')->on('hosting_clusters')->nullOnDelete();
            $table->foreign('hosting_plan_id')->references('id')->on('hosting_plans')->cascadeOnDelete();
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
        Schema::dropIfExists('hosting_cluster_mappings');
        Schema::enableForeignKeyConstraints();
    }
}
