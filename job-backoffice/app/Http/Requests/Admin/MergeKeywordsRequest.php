<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MergeKeywordsRequest extends FormRequest
{
    public function authorize(){
        return true;
    }

    public function rules(): array
    {
        return [
            'target_id' => 'required|exists:keywords,id',
            'source_ids' => 'required|string',
        ];
    }
}