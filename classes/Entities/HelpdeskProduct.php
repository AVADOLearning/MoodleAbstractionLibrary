<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class HelpdeskProduct
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class HelpdeskProduct extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'helpdesk_products';

    /**
     * Get all the HelpdeskProductCourse for HelpdeskProduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productCourses()
    {
        return $this->hasMany(HelpdeskProductCourse::class, 'productid', 'id');
    }

}
