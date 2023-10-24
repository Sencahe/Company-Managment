<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->admin === 1;
    }
    /*
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyId = $this->input('id');
        return [
            'name' => [
                'min:3',
                'max:200',
                 Rule::unique('companies')->ignore($companyId),
            ],
            'email' => [
                'email',
                'nullable',
                'max:200',
                 Rule::unique('companies')->ignore($companyId),
            ],
            'website' => 'url|max:200|nullable',
            'logoFile' => 'image|dimensions:min_width=100,min_height=100|nullable',
        ];
    }
}
