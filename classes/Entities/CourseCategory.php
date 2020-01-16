<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;
use local\helpdesk\Entities\HelpdeskProduct;
use local\helpdesk\Entities\HelpdeskProductCourses;

class CourseCategory extends BaseModel
{
    protected $table = 'course_categories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subCategory(){
        return $this->hasMany(CourseCategory::class,'parent','id');
    }

    /**
     * Get the HelpdeskProductCourses that the Coursecategory belong to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function helpdeskProductCourses(){
        return $this->belongsTo(HelpdeskProductCourses::class,'coursecategoryid','id');
    }

}
