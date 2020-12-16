<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'organization'
    ];

    /**
     * @return belongsTo
     */

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    // Accessors

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getOrganizationAttribute()
    {
        $organization = $this->organization()->value('title');
        return $organization;
    }
}
