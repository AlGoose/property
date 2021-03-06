<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project.name' => 'required',
            'project.address' => 'required',
            'project.date' => 'required|date|after:today',
            'project.time' => 'required|date|before:tomorrow',
            'dealer.dealer_id' => 'required|exists:dealers,id',
            'dealer.staff_id' => 'required|exists:staff,id',
            'customer.customer_id' => 'required|exists:customers,id',
            'customer.staff_id' => 'required|exists:staff,id',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'project.name.required' => 'Имя проекта не указано',
            'project.address.required' => 'Адрес проекта не указан',
            'project.date.required' => 'Срок защиты проекта не указан',
            'project.date.after' => 'Срок защиты проекта должен быть позже сегодняшнего дня',
            'project.time.required' => 'Время создания проекта не указано',
            'project.time.before' => 'Время создания проекта должен быть не позже сегодняшнего дня',

            'dealer.dealer_id.required'=>'Дилер не указан',
            'dealer.dealer_id.exists'=>'Дилер указан неверно',
            'dealer.staff_id.required'=>'Представитель дилера не указан',
            'dealer.staff_id.exists'=>'Представитель дилера указан неверно',

            'customer.customer_id.required'=>'Заказчик не указан',
            'customer.customer_id.exists'=>'Заказчик указан неверно',
            'customer.staff_id.required'=>'Представитель заказчика не указан',
            'customer.staff_id.exists'=>'Представитель заказчика указан неверно',
        ];
    }
}
