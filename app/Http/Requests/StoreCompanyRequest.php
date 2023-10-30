<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->admin === 1;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:200|unique:companies',
            'email' => 'email|unique:companies|nullable',
            'website' => 'url|max:200|nullable',
            'logoFile' => 'image|dimensions:min_width=100,min_height=100|nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The company name is required.',
            'name.min' => 'The company name must be at least :min characters.',
            'name.max' => 'The company name may not be greater than :max characters.',
            'name.unique' => 'The company name has already been taken.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'website.url' => 'Please enter a valid URL (https://www.website.com).',
            'website.max' => 'The website URL may not be greater than :max characters.',
            'logoFile.image' => 'Please upload an image file.',
            'logoFile.dimensions' => 'The image dimensions must be at least 100x100 pixels.',
        ];
    }
}
