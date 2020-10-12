<?php

use Jose\Assets;

/**
 * Return url of compiled style or script file
 *
 * @since 1.0.0
 *
 * @param $key
 *
 * @return string
 */
function asset($asset)
{       
    return Assets::getInstance()->assetPath($asset);
}


/**
 * Return random string
 *
 * @since 1.9.0
 *
 * @param int $length
 * @return void
 */
function random_string($length)
{
    return substr(bin2hex(random_bytes($length)), 0, $length);
}
