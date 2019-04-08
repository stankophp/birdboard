<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Task
 *
 * @property int $id
 * @property int $project_id
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|\App\Task newModelQuery()
 * @method static Builder|\App\Task newQuery()
 * @method static Builder|\App\Task query()
 * @method static Builder|\App\Task whereBody($value)
 * @method static Builder|\App\Task whereCreatedAt($value)
 * @method static Builder|\App\Task whereId($value)
 * @method static Builder|\App\Task whereProjectId($value)
 * @method static Builder|\App\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/projects/'.$this->project_id.'/tasks/'.$this->id;
    }
}
