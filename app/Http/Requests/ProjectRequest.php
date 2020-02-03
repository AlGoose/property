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
            'project.name.required' => 'name is required!',
            'project.address.required' => 'address is required!',
            'dealer.dealer_id' => 'dealer_id required',
            'dealer.dealer_staff_id' => 'dealer_staff_id required',
            'customer.customer_id' => 'customer_id required',
            'customer.customer_staff_id' => 'customer_staff_id required',
        ];
    }
}
