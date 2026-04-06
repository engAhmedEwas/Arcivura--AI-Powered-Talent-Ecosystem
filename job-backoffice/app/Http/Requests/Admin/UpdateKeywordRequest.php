<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKeywordRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:keywords,name,' . $this->keyword->id,
        ];
    }
}