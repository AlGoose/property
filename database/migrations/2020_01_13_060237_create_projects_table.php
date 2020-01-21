<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name'); //Название объекта
            $table->string('address'); //Адрес объекта
            $table->string('customer'); //Заказчик
            $table->string('date'); //Срок реализации проекта
            $table->longText('work')->nullable(); //Работа, проведенная и ведущаяся диллером. Массив/Map/Объект вида "дата - событие" в JSON
            $table->bigInteger('user_id')->unsigned(); //Менеджер, принявший заявку
            $table->bigInteger('dealer_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dealer_id')->references('id')->on('dealers')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
