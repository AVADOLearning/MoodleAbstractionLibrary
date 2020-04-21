<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use local\leaderboards\Entities\LeaderboardsCohortSettings;
use local_cohortmanagement\Entities\CourseCohortSync;

/**
 * Class Cohort
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class Cohort extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'cohort';

    /**
     * @return mixed
     */
    public function members()
    {
        return $this->hasManyThrough(User::class, CohortMember::class, 'cohortid', 'id', 'id', 'userid');
    }

    /**
     * @return mixed
     */
    public function courseCohortSyncs()
    {
        return $this->hasMany(CourseCohortSync::class,'cohortid','id');
    }

    /**
     * @return mixed
     */
    public function leaderboardCohortSetting()
    {
        return $this->hasOne(LeaderboardsCohortSettings::class, 'cohort_id', 'id');
    }
}
