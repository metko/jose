<?php

if( ! function_exists('accessibleArray')) {

    /**
     * @param $value
     * @return bool
     */
    function accessibleArray($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

if( ! function_exists('existKeyInArray')) {

    /**
     * @param $array
     * @param $key
     * @return bool
     */
    function existKeyInArray($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }
}

function sluggify($string, $separator = '-', $maxLength = 96)
{
    $title = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $title = preg_replace("%[^-/+|\w ]%", '', $title);
    $title = strtolower(trim(substr($title, 0, $maxLength), '-'));
    $title = preg_replace("/[\/_|+ -]+/", $separator, $title);

    return $title;
}




