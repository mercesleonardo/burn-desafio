<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'cpf'      => 'required|string|size:11|unique:users,cpf',
            'age'      => 'required|integer|min:18',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'O campo nome é obrigatório.',
            'name.string'        => 'O campo nome deve ser um texto.',
            'name.max'           => 'O campo nome não pode exceder 255 caracteres.',
            'email.required'     => 'O campo email é obrigatório.',
            'email.email'        => 'O campo email deve estar em um formato válido.',
            'email.unique'       => 'O email informado já está registrado.',
            'cpf.required'       => 'O campo CPF é obrigatório.',
            'cpf.string'         => 'O campo CPF deve ser um texto.',
            'cpf.size'           => 'O campo CPF deve ter exatamente 11 caracteres.',
            'age.required'       => 'O campo idade é obrigatório.',
            'age.integer'        => 'O campo idade deve ser um número inteiro.',
            'age.min'            => 'O campo idade deve ser maior ou igual a 18.',
            'password.required'  => 'O campo senha é obrigatório.',
            'password.string'    => 'O campo senha deve ser um texto.',
            'password.min'       => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não correspondem.',
        ];
    }
}
