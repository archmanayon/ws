<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update_bio extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'time_card', 'timecard');
    }

    public function punchtype()
    {
        return $this->belongsTo(Punchtype::class);
    }  
}
