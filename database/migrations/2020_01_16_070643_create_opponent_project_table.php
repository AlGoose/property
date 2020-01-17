<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpponentProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opponent_project', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('opponent_id')->unsigned();
            $table->bigInteger('project_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('opponent_project', function (Blueprint $table) {
            $table->foreign('opponent_id')->references('id')->on('opponents')->onDelete('cascade');
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
        Schema::dropIfExists('opponent_project');
    }
}
