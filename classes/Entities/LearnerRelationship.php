<?php

namespace local\learner_relationships\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Avado\MoodleAbstractionLibrary\Entities\Role;
use Avado\MoodleAbstractionLibrary\Entities\User;

/**
 * Class LearnerRelationship
 *
 * @package local\learner_relationships\Entities
 */
class LearnerRelationship extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'lr_learner_relationships';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'relationship_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
