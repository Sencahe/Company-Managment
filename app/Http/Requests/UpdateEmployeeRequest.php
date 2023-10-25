<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->admin === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $employeeId = $this->input('id');
        return [
            'name' => [
                'min:3',
                'max:200',
            ],
            'lastName' => [
                'min:3',
                'max:200',
            ],
            'companyId' => [
                'required|exists:companies,id'
            ],
            'email' => [
                'email',
                'nullable',
                'max:200',
                 Rule::unique('employees')->ignore($employeeId),
            ],
            'phone' => [
                'nullable',
                'min:3',
                'max:200',
                 Rule::unique('employees')->ignore($employeeId),
            ]
        ];
    }
}
