<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateEmployeeRequest extends FormRequest
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
            'name'  => 'required|max:255|unique:employees',
            'phone' =>  'required|unique:employees|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __("index.name.required"),
            'name.max' => __("index.name.max"),
            'name.unique' => __("index.name.unique"),
            'phone.required' => __("index.phone.required"),
            'phone.max' => __("index.phone.max"),
            'phone.unique' => __("index.phone.unique"),
            'phone.numeric' => __("index.phone.numeric"),
        ];
    }
}
