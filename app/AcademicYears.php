<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static truncate()
 * @method static create(array $array)
 */
class AcademicYears extends Model
{
    protected $fillable = ['start_year', 'end_year'];
}
