<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Activity
 *
 * @property int $id
 * @property int $project_id
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property string|null $description
 * @property array|null $changes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 * @method static Builder|\App\Activity newModelQuery()
 * @method static Builder|\App\Activity newQuery()
 * @method static Builder|\App\Activity query()
 * @method static Builder|\App\Activity whereChanges($value)
 * @method static Builder|\App\Activity whereCreatedAt($value)
 * @method static Builder|\App\Activity whereDescription($value)
 * @method static Builder|\App\Activity whereId($value)
 * @method static Builder|\App\Activity whereProjectId($value)
 * @method static Builder|\App\Activity whereSubjectId($value)
 * @method static Builder|\App\Activity whereSubjectType($value)
 * @method static Builder|\App\Activity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Activity extends Model
{
    protected $guarded = [];

    protected $casts = ['changes' => 'array'];

    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
