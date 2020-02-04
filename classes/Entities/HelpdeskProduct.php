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
     * Get the course_categories id, via the helpdesk_product_courses table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function productCategories()
    {
        return $this->hasManyThrough(HelpdeskProduct::class, HelpdeskProductCourse::class, 'coursecategoryid', 'id', 'id', 'id');
    }

}
