<?php

namespace App\Http\Controllers;

use App\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
//   public function store(Request $request)
  public function add() //TODO: Должен request с формой передаваться, что логично
  {
    // Проверка запроса...

    $form = new Form;

    $form->name = 'Name';
    $form->address = 'Address';
    $form->customer = 'Customer';
    $form->competition = false;
    $form->opponents = json_encode('');
    $form->contacts = json_encode(array('1','2','3'));
    $form->realization_date = date('Y-m-d');
    $form->work = json_encode(array('4','5','6'));
    $form->products = json_encode(array('7','8','9'));
    $form->manager = 'Manager';

    try {
        $form->save();
        print_r('Added');
    } catch(\Illuminate\Database\QueryException $e){
        $errorCode = $e->errorInfo[1];
        if($errorCode == '1062'){
            print_r($e->errorInfo[2]);
        }
    }
  }

  public function getAll() { //TODO: Пока хз зачем но пусть будет
    $forms = Form::All();
    print_r($forms->toJson());
  }

  public function get($id) { //TODO: Для получения полной информации об объекте
    $forms = Form::find($id);
    print_r($forms);
  }

  public function search(Request $request) { //TODO: Поиск объекта по адресу (для проверки наличия?)
    //TODO:
  }

  public function delete($id) {
    Form::destroy($id);
    print_r('Deleted');
  }

  public function update($id) { //TODO: Сюда request должен приходить с новыми данными и id изменяемой формы 
    $form = Form::find($id);
    $form->name = "SHAKAL";
    $form->save();
    print_r('Updated');
  }
}