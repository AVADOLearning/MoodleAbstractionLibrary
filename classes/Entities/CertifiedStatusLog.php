<?php

namespace local\leaderboards\Entities;

use Avado\MoodleAbstractionLibrary\Entities\BaseModel;

/**
 * Class CertifiedStatusLog
 *
 * @package local\leaderboards\Entities
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
