<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    use HasFactory;

    protected $with = ['tardis'];

    public function users()
    {
        return $this->hasMany(User::class);
    }   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    } 

    public function tardis()
    {
        return $this->hasMany(Tardi::class);
    }  
}
