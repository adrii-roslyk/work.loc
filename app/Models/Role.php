<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    const NAMES = ['worker', 'employer','admin'];

    // Relations

    /**
     * @return belongsToMany
     */

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
