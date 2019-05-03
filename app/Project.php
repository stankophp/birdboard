<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Project
 *
 * @property int $id
 * @property int $owner_id
 * @property string $title
 * @property string $description
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $members
 * @property-read \App\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @method static Builder|\App\Project newModelQuery()
 * @method static Builder|\App\Project newQuery()
 * @method static Builder|\App\Project query()
 * @method static Builder|\App\Project whereCreatedAt($value)
 * @method static Builder|\App\Project whereDescription($value)
 * @method static Builder|\App\Project whereId($value)
 * @method static Builder|\App\Project whereNotes($value)
 * @method static Builder|\App\Project whereOwnerId($value)
 * @method static Builder|\App\Project whereTitle($value)
 * @method static Builder|\App\Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    use RecordsActivity;

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

    /**
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));
    }

    /**
     * @return HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    /**
     * @return BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();

    }
}
