<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowsToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('files', function (Blueprint $table) {
        //     $table->bigIncrements('id');
        //     $table->timestamps();
        // });

        Schema::table('projects', function (Blueprint $table) {
            $table->date('tender_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('files');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('tender_date');
        });
    }
}
