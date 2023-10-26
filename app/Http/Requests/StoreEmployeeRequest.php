<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
        return [
            'name' => 'required|min:3|max:200',
            'lastName' => 'required|min:3|max:200',
            'company_id' => 'required|exists:companies,id',
            'email' => 'email|max:200|unique:employees|nullable',
            'phone' => 'min:3|max:200|nullable',
        ];
    }
}
