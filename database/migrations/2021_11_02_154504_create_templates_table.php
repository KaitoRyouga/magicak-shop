<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_category_id')->nullable();
            $table->string('name')->index();
            $table->string('code')->index();
            $table->text('description')->nullable();
            $table->string('version');
            $table->string('thumbnail');
            $table->string('capture');
            $table->string('url');
            $table->integer('template_app_storage');
            $table->integer('template_db_storage');
            $table->unsignedBigInteger('sort')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('template_category_id')->references('id')->on('template_categories')->nullOnDelete();
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
        Schema::dropIfExists('templates');
        Schema::enableForeignKeyConstraints();
    }
}
