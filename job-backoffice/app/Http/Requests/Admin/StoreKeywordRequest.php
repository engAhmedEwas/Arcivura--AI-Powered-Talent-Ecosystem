<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeywordRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'keywords'    => 'required|array|min:1',
            'keywords.*'  => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category is invalid.',
            'keywords.*.required' => 'Keyword name cannot be empty.',
        ];
    }
}
