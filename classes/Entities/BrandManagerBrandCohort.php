<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class BrandManagerBrandCohort
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class BrandManagerBrandCohort extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'brandmanager_brand_cohort';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(BrandManagerBrand::class, 'brandid', 'id');
    }
}
