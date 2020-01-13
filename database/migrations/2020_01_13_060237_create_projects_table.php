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
            $table->json('opponents')->nullable(); //Если есть, то массив в JSON
            $table->json('contacts'); //Контактные данные лица на объекте
            $table->string('date'); //Срок реализации проекта
            $table->json('work')->nullable(); //Работа, проведенная и ведущаяся диллером. Массив/Map/Объект вида "дата - событие" в JSON
            $table->json('products')->nullable(); //Применяемая продукция. Массив в JSON. Как лучше сделать:с ссылками на сами объекты или без?
            $table->string('manager'); //Менеджер, принявший заявку
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
