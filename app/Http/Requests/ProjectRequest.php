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
            'dealer.dealer_id' => 'required|exists:dealers,id',
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
            'dealer.dealer_id.exists'=>'Диллер указан не верно',
            'customer.customer_id.required'=>'Заказчик не указан',
            'required'=>'Поле :attribute не заполнено',

        ];
    }
}
