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
            'dealer.dealer_id' => 'required',
            'dealer.dealer_staff_id' => 'required',
            'customer.customer_id' => 'required',
            'customer.customer_staff_id' => 'required',
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
            'project.name.required' => 'Название проекта не указано!',
            'project.address.required' => 'Адрес не указан!',
            'dealer.dealer_id' => 'Дилер не указан!',
            'dealer.dealer_staff_id' => 'Представитель дилера не указан!',
            'customer.customer_id' => 'Заказчик не указан!',
            'customer.customer_staff_id' => 'Представитель заказчика не указан!',
        ];
    }
}
