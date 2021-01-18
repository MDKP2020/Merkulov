<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{

    public function rules()
    {
        return [
            'number' => 'required|integer',
            'major_id' => 'required|integer',
            'academic_degree' => ['required', Rule::in(Student::ACADEMIC_DEGREES)],
        ];
    }
}