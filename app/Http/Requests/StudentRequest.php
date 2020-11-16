<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'transcript' => 'nullable|integer',
            'status' => ['required', Rule::in(array_keys(Student::STATUSES))],
            'academic_degree' => ['required', Rule::in(Student::ACADEMIC_DEGREES)],
        ];
    }
}
