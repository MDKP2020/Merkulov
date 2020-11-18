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
}
