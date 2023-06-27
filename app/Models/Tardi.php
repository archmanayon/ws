<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tardi extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }   

    public function tardi_description()
    {
        return $this->belongsTo(tardi_description::class);
    }  

    public function term()
    {
        return $this->belongsTo(Term::class);
    } 
    
}
