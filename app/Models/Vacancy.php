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
        'vacancy_name',
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
    protected $casts = [
        'created_at' => 'timestamp:Y-m-d H:i:s',
        'updated_at' => 'timestamp:Y-m-d H:i:s'
    ];

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
        $organization = $this->organization()->value('title');
        return $organization;
    }

    /**
     * @return int
     */
    public function getWorkersBookedAttribute()
    {
        $workers_booked = $this->users()->count('user_id');
        return $workers_booked;
    }

    /**
     *@return string
     */
    public function getStatusAttribute(){
        $active = 'active';
        $closed = 'closed';
        if ($this->workers_amount > $this->workers_booked) {
            return $active;
        } else {
            return $closed;
        }
    }
}
