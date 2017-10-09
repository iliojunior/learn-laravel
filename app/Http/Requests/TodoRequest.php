<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
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
            'name'        => 'required|max:255|unique:todos',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'O campo nome é obrigatório',
            'name.max'             => 'Tamanho máximo do campo nome é 255',
            'name.unique'          => 'Este nome já está cadastrado, informe outro',
            'description.required' => 'O campo descrição é obrigatório',
        ];
    }
}
