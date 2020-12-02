<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{

    public function rules()
    {
        return [
            'number' => 'required|integer',
        ];
    }
}
