<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class BrandManagerBrand
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class BrandManagerBrand extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'brandmanager_brand';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cohorts()
    {
        return $this->hasMany(BrandManagerBrandCohort::class, 'brandid', 'id');
    }
}