<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'country',
        'city',
        'user_id'
    ];

    //Relations

    /**
     * @return belongsTo
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return hasMany
     */

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class);
    }
}
