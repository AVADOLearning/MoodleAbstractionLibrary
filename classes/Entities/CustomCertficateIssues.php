<?php

namespace Avado\MoodleAbstractionLibrary\Entities;

class CustomCertficateIssues extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'customcert_issues';

    /**
     * @var array
     */
    protected $guarded = ['id'];
}
