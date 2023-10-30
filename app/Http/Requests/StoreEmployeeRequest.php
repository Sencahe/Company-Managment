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
            'company_id' => 'exists:companies,id',
            'email' => 'email|max:200|unique:employees|nullable',
            'phone' => 'min:3|max:200|nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The employee name is required.',
            'name.min' => 'The employee name must be at least :min characters.',
            'name.max' => 'The employee name may not be greater than :max characters.',
            'lastName.required' => 'The employee last name is required.',
            'lastName.min' => 'The employee last name must be at least :min characters.',
            'lastName.max' => 'The employee last name may not be greater than :max characters.',
            'company_id.required' => 'You must select a company.',
            'company_id.exists' => 'The selected company does not exist.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email may not be greater than :max characters.',
            'email.unique' => 'The email has already been taken.',
            'phone.min' => 'The phone number must be at least :min characters.',
            'phone.max' => 'The phone number may not be greater than :max characters.',
        ];
    }
}
