<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type'        => 'sometimes|required|in:pj,clt,estagio',
            'salary'      => 'nullable|numeric|min:0',
            'schedule'    => 'nullable|integer|min:1',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type     = $this->input('type', $this->route('position')->type->value);
            $salary   = $this->input('salary', $this->route('position')->salary);
            $schedule = $this->input('schedule', $this->route('position')->schedule);

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
                if ($schedule > 6) {
                    $validator->errors()->add('schedule', 'Posições de Estágio não podem ter mais de 6 horas por dia.');
                }
            }

            if ($type === 'clt' && !is_null($schedule)) {
                if ($schedule > 9) {
                    $validator->errors()->add('schedule', 'Posições CLT não podem ter mais de 9 horas por dia.');
                }
            }
        });
    }
}
