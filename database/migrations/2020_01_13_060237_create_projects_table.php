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
            $table->string('address')->unique(); //Адрес объекта
            $table->string('customer'); //Заказчик
            $table->longText('contacts'); //Контактные данные лица на объекте
            $table->string('date'); //Срок реализации проекта
            $table->longText('work')->nullable(); //Работа, проведенная и ведущаяся диллером. Массив/Map/Объект вида "дата - событие" в JSON
            $table->bigInteger('user_id'); //Менеджер, принявший заявку
            $table->bigInteger('dealer_id')->default(1); //TODO: Не работает вставка если убрать default
            $table->timestamps();
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
