<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    protected $with = ['department'];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function applicants() {
        return $this->belongsToMany(
            Applicant::class,
            'applicant_major',
            'major_id',
            'applicant_id'
        );
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }
}
