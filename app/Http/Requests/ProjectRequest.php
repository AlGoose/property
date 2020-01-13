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
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:50',
            'customer' => 'required|string|max:50',
            'opponents' => 'required|string|max:50',
            'contacts' => 'required|string|max:50',
            'date' => 'required|string|max:50',
            'manager' => 'required|string|max:50',
        ];
    }
}
