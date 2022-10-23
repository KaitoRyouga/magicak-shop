<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_type_id')->nullable();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->boolean('ssl')->default(1);
            $table->float('ram')->comment('GB');
            $table->float('cpu')->comment('vCore');
            $table->float('hosting_app_storage')->nullable()->comment('GB');
            $table->float('hosting_db_storage')->nullable()->comment('GB');
            $table->boolean('mailbox')->default(0)->nullable();
            $table->float('mailbox_size')->nullable()->comment('GB');
            $table->boolean('backup')->default(0)->nullable();
            $table->tinyInteger('duration')->default(1)->comment('month');
            $table->unsignedBigInteger('sort')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('plan_type_id')->references('id')->on('hosting_plan_types')->nullOnDelete();
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
        Schema::dropIfExists('hosting_plans');
        Schema::enableForeignKeyConstraints();
    }
}
