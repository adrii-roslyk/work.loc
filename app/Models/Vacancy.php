<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'workers_amount',
        'organization_id',
        'salary'
    ];

    protected $appends = [
        'status',
        'organization',
        'workers_booked'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    // Relations

    /**
     * @return belongsTo
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * @return belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // Accessors

    /**
     * @return string
     */
    public function getOrganizationAttribute()
    {
        //return $this->organization->title; Undefined property: App\\Models\\Vacancy::$organization
        return $this->organization()->value('title');
    }

    /**
     * @return int
     */
    public function getWorkersBookedAttribute()
    {
        return $this->users()->count();
    }

    /**
     *@return string
     */
    public function getStatusAttribute()
    {
        if ($this->workers_amount > $this->workers_booked) {
            return 'active';
        } else {
            return 'closed';
        }
    }
}
