<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Activity
 *
 * @property int $id
 * @property int $project_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @method static Builder|\App\Activity newModelQuery()
 * @method static Builder|\App\Activity newQuery()
 * @method static Builder|\App\Activity query()
 * @method static Builder|\App\Activity whereCreatedAt($value)
 * @method static Builder|\App\Activity whereDescription($value)
 * @method static Builder|\App\Activity whereId($value)
 * @method static Builder|\App\Activity whereProjectId($value)
 * @method static Builder|\App\Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
        return $this->morphTo();
    }
}
