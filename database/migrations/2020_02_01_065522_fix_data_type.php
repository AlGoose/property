<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixDataType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_project', function (Blueprint $table) {
            $table->float('count')->change();
            $table->float('price')->change();
        });

         Schema::table('projects', function (Blueprint $table) { //FIXME: Почему-то не применяется
             $table->date('date')->charset('')->change();
             $table->date('time')->charset('')->change();
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_project', function (Blueprint $table) {
            $table->integer('count')->change();
            $table->integer('price')->change();
        });

         Schema::table('projects', function (Blueprint $table) {
             $table->string('date')->change();
             $table->string('time')->change();
         });
    }
}
