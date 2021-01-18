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

    public function groups() {
        return $this->hasMany(Group::class);
    }
}
