<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $iconURL
 * @property string $created_at
 * @property int $owner_id
 * @method static find(int $id)
 * @mixin IdeHelperGroup
 */
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
}
