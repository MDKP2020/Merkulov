<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MajorRequest extends FormRequest
{

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:10'
        ];
    }
}
