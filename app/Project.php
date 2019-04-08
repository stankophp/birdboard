<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Project
 *
 * @property int $id
 * @property int $owner_id
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|\App\Project newModelQuery()
 * @method static Builder|\App\Project newQuery()
 * @method static Builder|\App\Project query()
 * @method static Builder|\App\Project whereCreatedAt($value)
 * @method static Builder|\App\Project whereDescription($value)
 * @method static Builder|\App\Project whereId($value)
 * @method static Builder|\App\Project whereOwnerId($value)
 * @method static Builder|\App\Project whereTitle($value)
 * @method static Builder|\App\Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/projects/'.$this->id;
    }

    /**
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }
}
