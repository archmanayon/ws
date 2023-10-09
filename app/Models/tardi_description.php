<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tardi_description extends Model
{
    use HasFactory;

    protected $with = ['tardis'];

    public function tardis()
    {
        return $this->hasMany(Tardi::class);
    } 
}
