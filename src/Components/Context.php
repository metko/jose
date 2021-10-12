<?php

namespace Jose\Components;

class Context
{

    private static $instance;
    public $context = [];

    public function __construct()
    {
        
        // autoload files on hooks
        // dd(jose('context')->get('hooks'));
    }

    public static function getInstance(): Context
    {
        if(!self::$instance) {
            self::$instance = new Context();
        }
        return self::$instance;
    }

    public function set($key, $value)
    {
        $this->context[$key] = $value;
    }

    /**
     * @param null $key
     * @return array|string|null
     */
    public function get($key = null)
    {
        $keys = explode('.', $key);
        $context = $this->context;
        if (count($keys)) {

            if(count($keys) == 1) {
                if (array_key_exists($key, $context)) {
                    return $context[$key];
                }
            }

            if (! accessibleArray($context)) {
                return $context;
            }

            if (is_null($key)) {
                return $context;
            }

            if (existKeyInArray($context, $key)) {
                return $context[$key];
            }

            if (strpos($key, '.') === false) {
                return $context[$key] ?? $context;
            }

            foreach (explode('.', $key) as $segment) {
                if (accessibleArray($context) && existKeyInArray($context, $segment)) {
                    $context = $context[$segment];
                } else {
                    return $context;
                }
            }

            return $context;

        } else {
            return $context;
        }
    }
}
