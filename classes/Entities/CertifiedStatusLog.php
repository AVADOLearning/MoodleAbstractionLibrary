<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

/**
 * Class CertifiedStatusLog
 *
 * @package Avado\MoodleAbstractionLibrary\Entities
 */
class CertifiedStatusLog extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'certified_status_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
