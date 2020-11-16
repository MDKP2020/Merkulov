<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public $timestamps = false;
    protected $guarded = [];

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
}
