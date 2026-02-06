<?php

namespace App\Http\Requests;

use App\Enums\CompanyPlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'cnpj'        => ['required', 'string', 'size:14', 'unique:companies,cnpj', 'regex:/^\d{14}$/'],
            'plan'        => ['required', 'string', Rule::enum(CompanyPlan::class)],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'        => 'O nome da empresa é obrigatório.',
            'name.string'          => 'O nome da empresa deve ser um texto.',
            'name.max'             => 'O nome da empresa não pode ter mais de 255 caracteres.',
            'description.required' => 'A descrição da empresa é obrigatória.',
            'description.string'   => 'A descrição da empresa deve ser um texto.',
            'description.max'      => 'A descrição da empresa não pode ter mais de 1000 caracteres.',
            'cnpj.required'        => 'O CNPJ é obrigatório.',
            'cnpj.string'          => 'O CNPJ deve ser um texto.',
            'cnpj.size'            => 'O CNPJ deve ter exatamente 14 dígitos.',
            'cnpj.unique'          => 'Este CNPJ já está cadastrado.',
            'cnpj.regex'           => 'O CNPJ deve conter apenas números.',
            'plan.required'        => 'O plano é obrigatório.',
            'plan.string'          => 'O plano deve ser um texto.',
            'plan.in'              => 'O plano deve ser free ou premium.',
        ];
    }
}
