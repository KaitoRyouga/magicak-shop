<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostingClusterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->unsignedBigInteger('dc_location_id')->nullable();
            $table->unsignedBigInteger('hosting_platform_id')->nullable();
            $table->unsignedBigInteger('system_domain_id')->nullable();
            $table->unsignedBigInteger('sort')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->foreign('dc_location_id')->references('id')->on('data_center_locations')->nullOnDelete();
            $table->foreign('hosting_platform_id')->references('id')->on('hosting_platforms')->nullOnDelete();
            $table->foreign('system_domain_id')->references('id')->on('system_domains')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
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
        Schema::dropIfExists('hosting_clusters');
        Schema::enableForeignKeyConstraints();
    }
}
