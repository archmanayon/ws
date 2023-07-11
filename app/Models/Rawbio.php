<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rawbio extends Model
{
    use HasFactory;

    public function punchtype()
    {
        return $this->belongsTo(Punchtype::class);
    }  
}
