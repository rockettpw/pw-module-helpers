<?php

/**
 * Utility Trait (helpers) for ProcessWire
 * modules that require it.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Traits;

trait UtilityTrait
{
    /**
     * Flatten a multi-dimensional associative array with slashes.
     *
     * @param  array   $array
     * @param  string  $prepend
     * @return array
     */
    public function flattenArray($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $results = array_merge($results, self::flattenArray($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

    /**
     * Flatten an associative array's keys that contain pipes,
     * mapping their values along the way.
     *
     * @param  array   $array
     * @param  string  $delimter
     * @return mixed
     */
    public function flattenKeys($array, $delimter = '|')
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if (strpos($key, $delimter)) {
                $key = explode('|', $key);
                foreach ($key as $newKey) {
                    $newArray[$newKey] = $value;
                }
            } else {
                $newArray[$key] = $value;
            }
        }

        return $newArray;
    }

    /**
     * Parse a property list
     * Valid separators are "=" and ":"
     * Separators may be surrounded by whitespace - this will be trimmed. Ex:
     *     MSFT=Microsoft
     *     HP: Hewlett Packard
     *     SKU = Stock Keeping Unit
     * @param  $list
     * @return Array
     */
    protected function parsePropertyList($list, $propertySeparator = "\n")
    {
        $properties = explode($propertySeparator, $list);
        foreach ($properties as $property) {
            $separator = strpbrk($property, '=:');
            list($key, $value) = explode($separator[0], $property);
            $propertiesArray[trim($key)] = trim($value);
        }

        return $propertiesArray;
    }

    /**
     * Convert a string to snake case
     * Used for converting module config options to their equivalent functions
     * @param $input
     */
    protected function snakeCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
