<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Punchtype extends Model
{
    use HasFactory;

    public function rawbios()
    {
        return $this->hasMany(Rawbio::class);
    } 

    public function update_bios()
    {
        return $this->hasMany(Update_bio::class);
    } 
}
