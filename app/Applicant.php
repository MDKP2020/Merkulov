<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'date:Y-m-d H:m',
        'updated_at' => 'date:Y-m-d H:m',
    ];

    protected $with = ['certificate', 'majors'];

    public function certificate() {
        return $this->belongsTo(Certificate::class);
    }

    public function majors() {
        return $this->belongsToMany(
            Major::class,
            'applicant_major',
            'applicant_id',
            'major_id'
        );
    }
}
