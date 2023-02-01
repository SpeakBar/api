<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'owner_id'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')->withPivot('joined_at');
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }
}
