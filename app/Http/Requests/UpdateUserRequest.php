<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $this->route('user')->id,
            'cpf'      => 'sometimes|string|unique:users,cpf,' . $this->route('user')->id,
            'age'      => 'sometimes|integer|min:18',
            'password' => 'sometimes|string|min:8|confirmed',
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
            'name.string'        => 'O campo nome deve ser um texto.',
            'name.max'           => 'O campo nome não pode exceder 255 caracteres.',
            'email.email'        => 'O campo email deve estar em um formato válido.',
            'email.unique'       => 'O email informado já está registrado.',
            'cpf.string'         => 'O campo CPF deve ser um texto.',
            'cpf.unique'         => 'O CPF informado já está registrado.',
            'age.integer'        => 'O campo idade deve ser um número inteiro.',
            'age.min'            => 'O campo idade deve ser maior ou igual a 18.',
            'password.string'    => 'O campo senha deve ser um texto.',
            'password.min'       => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'As senhas não correspondem.',
        ];
    }
}
