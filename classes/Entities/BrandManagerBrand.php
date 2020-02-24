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
    public function brandCohorts()
    {
        return $this->hasMany(BrandManagerBrandCohort::class, 'brandid', 'id');
    }
}
