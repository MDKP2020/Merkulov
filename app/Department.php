<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function major() {
        return $this->hasOne(Major::class);
    }
}
