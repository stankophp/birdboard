<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Task
 *
 * @property int $id
 * @property int $project_id
 * @property string $body
 * @property bool $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activity
 * @property-read \App\Project $project
 * @method static Builder|\App\Task newModelQuery()
 * @method static Builder|\App\Task newQuery()
 * @method static Builder|\App\Task query()
 * @method static Builder|\App\Task whereBody($value)
 * @method static Builder|\App\Task whereCompleted($value)
 * @method static Builder|\App\Task whereCreatedAt($value)
 * @method static Builder|\App\Task whereId($value)
 * @method static Builder|\App\Task whereProjectId($value)
 * @method static Builder|\App\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use RecordsActivity;

    protected $guarded = [];
    protected $touches = ['project'];
    protected $casts   = ['completed' => 'boolean'];

    protected static $recordableEvents = ['created', 'deleted'];

    public function path()
    {
        return '/projects/'.$this->project_id.'/tasks/'.$this->id;
    }

    /**
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('task_completed');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('task_incompleted');
    }
}
