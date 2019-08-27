<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreateRequest extends FormRequest
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
            'name' => 'required|min:2|max:100',
            'phone' => 'required',
            'address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Имя не заполнено',
            'name.min' => 'Минимальная длина имени [:min] символов',
            'name.max' => 'Максимальная длина имени [:max] символов',
            'phone.required' => 'Номер телефона не заполнен',
            'address.required' => 'Адрес не заполнен'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Имя',
            'phone' => 'Номер телефона',
            'address' => 'Адрес'
        ];
    }
}
