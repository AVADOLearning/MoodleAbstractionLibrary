<?php

namespace Avado\MoodleAbstractionLibrary\Entities\ACL;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use Avado\MoodleAbstractionLibrary\Entities\UserEnrolment;

class User extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var array
     */
    protected $guarded = ['password'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrolments()
    {
        return $this->hasMany(UserEnrolment::class, 'userid', 'id');
    }

    public function roles()
    {
        return $this->hasManyThrough(Role::class, UserRole::class, 'user_id','id','id', 'role_id');
    }

    /**
     * @return bool
     */
    public function hasPermission($permission)
    {
        return $this->whereHas('roles.permissions', function($query) use ($permission){
            $query->where('name', $permission);
        })->exists();
    }
}
