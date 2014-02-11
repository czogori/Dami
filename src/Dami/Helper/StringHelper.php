<?php

namespace Dami\Helper;

class StringHelper
{
    /**
     * Camelize the given string.
     *
     * @param $string String in underscore format.
     *
     * @return string
     */
    public static function camelize($string)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) { return ('.' === $match[1] ? '_' : '').strtoupper($match[2]); }, $string);
    }

    /**
     * Underscore the given string.
     *
     * @param $string String in camel case format.
     *
     * @return string
     */
    public static function underscore($string)
    {
        return strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), array('\\1_\\2', '\\1_\\2'), strtr($string, '_', '.')));
    }
}
