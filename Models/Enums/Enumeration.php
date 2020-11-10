<?php

namespace Models\Enums;

use Models\Model;

/**
 * Base class for all classes with enumerations
 */
abstract class Enumeration extends Model
{
    /**
     * @return array {
     *      Array with names of enum and its values
     * 
     *      @type string Name of enum
     *      @type string Value of enum
     * }
     */
    public static function getAll()
    {
        return (new \ReflectionClass(static::class))->getConstants();
    }

    /**
     * @param various $item Any value needed to check
     * @return boolean Is it valid enum item or not
     */
    public static function isValid($item)
    {
        return in_array($item, static::getAll());
    }
}
