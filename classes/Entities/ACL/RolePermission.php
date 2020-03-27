<?php

namespace Avado\MoodleAbstractionLibrary\Entities\ACL;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;

/**
 * Class RolePermission
 * @package Avado\MoodleAbstractionLibrary\Entities\ACL
 */
class RolePermission extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'acl_role_permissions';

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
