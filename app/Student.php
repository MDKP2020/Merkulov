<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    protected $with = ['groups'];

    public const STATUSES = [
        'studying' => 'учится',
        'expelled' => 'отчислен',
        'academic_leave' => 'в академическом отпуске',
        'graduated' => 'выпустился'
    ];

    public const ACADEMIC_DEGREES = [
        'bachelor',
        'specialist',
        'master',
        'postgraduate'
    ];

    /**
     */
    public function groups() {
        return $this->belongsToMany(Group::class, 'student_group')
//            ->withPivot('academic_year_id')
//            ->pivot->academic_year
            ->orderBy('student_group.academic_year_id', 'desc')
            ->orderBy('student_group.id', 'desc');
    }
}
