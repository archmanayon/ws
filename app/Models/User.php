<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        // protected $fillable = [

        //     'active',
        //     'timecard',
        //     'student_id',
        //     'name',
        //     'username',
        //     'email',
        //     'password',
        //     'image_path'
        // ];

        /**
         * The attributes that should be hidden for serialization.
         *
         * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];  

    // protected $with = ['manual_shifts', 'shift', 'update_bios'];

    public function tardis()
    {
        return $this->hasMany(Tardi::class);
    }  

    public function punches()
    {
        return $this->hasMany(Punch::class);
    }   

    public function tasks()
    {
        return $this->hasMany(Task::class);
    } 

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function head()
    {
        return $this->belongsTo(Head::class);
    }

    public function heads()
    {
        return $this->hasMany(Head::class);
    }

    public function manual_shifts()
    {
        return $this->hasMany(ManualShift::class);
    } 

    public function update_bios()
    {
        return $this->hasMany(Update_bio::class,'time_card', 'timecard');
    } 

    public function scopeFilter ($query, array $filters){
       
        if($filters['search_name'] ?? false){

            $query
            ->where('student_id', 'like', '%'.$filters['search_name'].'%')
            ->orWhere('name', 'like', '%'.$filters['search_name'].'%')
            ->orWhere('username', 'like', '%'.$filters['search_name'].'%')
            ->orWhere('email', 'like', '%'.$filters['search_name'].'%')
            ->orWhere('student_id', 'like', '%'.$filters['search_name'].'%')
            ->orWhere('username', 'like', '%'.$filters['search_name'].'%');

        }


    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
