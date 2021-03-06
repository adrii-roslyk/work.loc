<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'country',
        'city',
        'phone',
        'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    const ROLE_WORKER = 'worker';
    const ROLE_EMPLOYER = 'employer';
    const ROLE_ADMIN = 'admin';

    const ROLE_NAMES = [
        self::ROLE_WORKER,
        self::ROLE_EMPLOYER,
        self::ROLE_ADMIN
    ];

    protected $attributes = [
        'role' => self::ROLE_WORKER
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function (self $user){
            foreach ($user->organizations as $organization){
                $organization->delete();
            }
        });
    }

    // Relations

    /**
     * @return belongsToMany
     */
    public function vacancies()
    {
        return $this->belongsToMany(Vacancy::class)->withTimestamps();
    }

    /**
     * @return hasMany
     */
    public function organizations()
    {
        return $this->hasMany(Organization::class);
    }

    /**
     * @return hasManyThrough
     */
    public function hasVacancies()
    {
        return $this->hasManyThrough(Vacancy::class, Organization::class);
    }

    //Mutators

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
