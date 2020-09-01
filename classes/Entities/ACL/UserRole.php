<?php

namespace Avado\MoodleAbstractionLibrary\Entities\ACL;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;

/**
 * Class UserRole
 * @package Avado\MoodleAbstractionLibrary\Entities\ACL
 */
class UserRole extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'acl_user_roles';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id'];
}
