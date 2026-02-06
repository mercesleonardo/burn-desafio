<?php

namespace App\Http\Requests;

use App\Enums\CompanyPlan;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
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
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:1000'],
            'cnpj'        => ['sometimes', 'string', 'size:14', 'regex:/^\d{14}$/', Rule::unique('companies', 'cnpj')->ignore($this->route('company'))],
            'plan'        => ['sometimes', 'string', Rule::enum(CompanyPlan::class)],
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
            'name.string'    => 'O nome da empresa deve ser um texto.',
            'name.max'       => 'O nome da empresa não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição da empresa deve ser um texto.',
            'description.max' => 'A descrição da empresa não pode ter mais de 1000 caracteres.',
            'cnpj.string'    => 'O CNPJ deve ser um texto.',
            'cnpj.size'      => 'O CNPJ deve ter exatamente 14 dígitos.',
            'cnpj.unique'    => 'Este CNPJ já está cadastrado.',
            'cnpj.regex'     => 'O CNPJ deve conter apenas números.',
            'plan.string'    => 'O plano deve ser um texto.',
            'plan.in'        => 'O plano deve ser free ou premium.',
        ];
    }
}
