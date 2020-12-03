<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function applicant() {
        return $this->hasOne(Applicant::class);
    }
}
