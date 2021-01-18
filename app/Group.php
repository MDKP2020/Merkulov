<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function major() {
        return $this->belongsTo(Major::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class, 'student_group');
    }
}