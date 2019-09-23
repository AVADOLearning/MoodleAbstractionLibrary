<?php

namespace Avado\MoodleAbstractionLibrary\Language;

/**
 * Class LanguageService
 * @package Avado\MoodleAbstractionLibrary\Language
 */
class LanguageService
{
    public function __construct()
    {

    }

    public function get($name, $component)
    {
        return get_string($name, $component);
    }
}
