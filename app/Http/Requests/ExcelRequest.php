<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelRequest extends FormRequest
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
            'id' => 'required',
            'excel' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Отсутсвует ID проекта',
            'excel.required' => 'Отсутствует файл',
            // 'excel.mimes' => 'Неверный формат файла. Нужен xls|xlsx файл.',
        ];
    }
}