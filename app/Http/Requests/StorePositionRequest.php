<?php

namespace App\Http\Requests;

use App\Enums\PositionType;
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
            'schedule'    => 'nullable|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type     = $this->input('type');
            $salary   = $this->input('salary');
            $schedule = $this->input('schedule');

            $positionType = PositionType::from($type);

            if ($positionType->requiresSalaryAndSchedule()) {
                if (is_null($salary)) {
                    $validator->errors()->add('salary', 'Salário é obrigatório para posições ' . $positionType->label() . '.');
                }

                if (is_null($schedule)) {
                    $validator->errors()->add('schedule', 'Horário é obrigatório para posições ' . $positionType->label() . '.');
                }
            }

            if (!is_null($salary) && $positionType->minSalary() && $salary < $positionType->minSalary()) {
                $validator->errors()->add('salary', 'Posições ' . $positionType->label() . ' devem ter um salário mínimo de R$' . number_format($positionType->minSalary(), 2, ',', '.') . '.');
            }

            if (!is_null($schedule) && $positionType->maxScheduleHours() && $schedule > $positionType->maxScheduleHours()) {
                $validator->errors()->add('schedule', 'Posições ' . $positionType->label() . ' não podem ter mais de ' . $positionType->maxScheduleHours() . ' horas por dia.');
            }

            // Check company limit
            $company = Company::find($this->input('company_id'));

            if ($company && !$company->canCreateJob()) {
                $validator->errors()->add('company_id', 'A empresa atingiu o número máximo de posições para o seu plano.');
            }
        });
    }
}
