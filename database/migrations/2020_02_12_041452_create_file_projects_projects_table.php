<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileProjectsProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_project_project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('file_project_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('file_project_project', function (Blueprint $table) {
            $table->foreign('file_project_id')->references('id')->on('file_projects')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_projects_projects');
    }
}
