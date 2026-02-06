<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
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
            'company_id'  => 'required|exists:companies,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'type'        => 'required|in:pj,clt,estagio',
            'salary'      => 'nullable|numeric|min:0',
            'schedule'    => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type     = $this->input('type');
            $salary   = $this->input('salary');
            $schedule = $this->input('schedule');

            if (in_array($type, ['clt', 'estagio'])) {
                if (is_null($salary)) {
                    $validator->errors()->add('salary', 'Salário é obrigatório para posições CLT e Estágio.');
                }

                if (is_null($schedule)) {
                    $validator->errors()->add('schedule', 'Horário é obrigatório para posições CLT e Estágio.');
                }
            }

            if ($type === 'clt' && $salary < 1212) {
                $validator->errors()->add('salary', 'Posições CLT devem ter um salário mínimo de R$1212,00.');
            }

            if ($type === 'estagio' && !is_null($schedule)) {
                // Assuming schedule is like "X horas/semana", check max 6
                if (preg_match('/(\d+) horas\/semana/', $schedule, $matches)) {
                    if ($matches[1] > 6) {
                        $validator->errors()->add('schedule', 'Posições de Estágio não podem ter mais de 6 horas por semana.');
                    }
                }
            }

            // Check company limit
            $company = Company::find($this->input('company_id'));

            if ($company && !$company->canCreateJob()) {
                $validator->errors()->add('company_id', 'A empresa atingiu o número máximo de posições para o seu plano.');
            }
        });
    }
}
