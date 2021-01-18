<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $with = ['groups'];

    /**
     */
    public function majors() {
        return $this->hasMany(Major::class);
    }

    /**
     */
    public function groups() {
        return $this->hasManyThrough(Group::class, Major::class);
    }
}
