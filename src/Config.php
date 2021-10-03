<?php
namespace Jose;

use Jose\Exception\FileAlreadyLoadedException;
use Jose\Exception\NotFoundException;

class Config {

    public $config = [];

    /**
     * @var mixed
     */
    public function __construct() {
        $this->config = require_once(ROOT_JOSE.'/config/config.php');
    }

    /**
     * @param array|string $config
     * @throws NotFoundException
     */
    public function set($config) {
        $user_config = [];

        if(is_string($config)) {

            if(jose('file')->exists($config)) {

                if (!empty($config)) {
                    /** @noinspection PhpIncludeInspection */
                    $user_config = require_once($config);
                    // If it's not an array, means the file is already loaded (return Bool)
                    if(!is_array($user_config)) {
                        throw new FileAlreadyLoadedException('Config file '.$config.' is already loaded. Can be only once.');
                    };
                }

            } else {
                throw new NotFoundException('Config file '.$config .' not found');
            }

        } else {
            $user_config = array_merge($user_config, $config);
        }
        foreach ($user_config as $k => $v) {
            $this->config[$k] = $v;
        }
        //$this->config = array_merge($this->config, $user_config);

    }

    /**
     * @param null $key
     * @return array|string|null
     */
    public function get($key = null)
    {
        $keys = explode('.', $key);
        $config = $this->config;
        if (count($keys)) {

            if(count($keys) == 1) {
                if (array_key_exists($key, $config)) {
                    return $config[$key];
                }
            }

            if (! accessibleArray($config)) {
                return $config;
            }

            if (is_null($key)) {
                return $config;
            }

            if (existKeyInArray($config, $key)) {
                return $config[$key];
            }

            if (strpos($key, '.') === false) {
                return $config[$key] ?? $config;
            }

            foreach (explode('.', $key) as $segment) {
                if (accessibleArray($config) && existKeyInArray($config, $segment)) {
                    $config = $config[$segment];
                } else {
                    return $config;
                }
            }

            return $config;

        } else {
            return $config;
        }
    }



}