<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class CohortMember
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class CohortMember extends BaseModel
{
    protected $table = 'cohort_members';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cohort()
    {
        return $this->belongsTo(Cohort::class, 'cohortid', 'id');
    }
}
