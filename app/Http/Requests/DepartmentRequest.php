<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
