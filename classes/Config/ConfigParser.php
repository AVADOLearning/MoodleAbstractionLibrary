<?php

namespace Avado\MoodleAbstractionLibrary\Config;

class ConfigParser
{
    /**
     * @param string $configFile
     * @return void
     */
    public static function parseConfig($configFile)
    {
        try {
            $configValues = [];
    
            $configFile = file_get_contents($configFile);
            preg_match_all("/[\n\r].*CFG->(.*[^=])=(.*);/m", $configFile, $configValues);
    
            $keys = static::clearKeys($configValues[1]);
            $values = static::clearValues($configValues[2]);
    
            return static::matchValuesToKeys($keys, $values);
        } catch (\Exception $e){
            die;
        }
    }

    /**
     * @param array $values
     * @return array
     */
    private static function clearValues($values)
    {
        return array_map(function($value){
            return preg_replace('/(^[\"\']|[\"\']$)/', '', trim($value));
        }, $values);
    }

    /**
     * @param array $keys
     * @return array
     */
    private static function clearKeys($keys)
    {
        return array_map(function($key){
            return trim($key);
        }, $keys);
    }

    /**
     * @param array $keys
     * @param array $values
     * @return array
     */
    private static function matchValuesToKeys($keys, $values)
    {
        $keyValues = [];

        foreach($keys as $index => $key){
            $keyValues[$key] = $values[$index];
        }
        return $keyValues;
    }
}
