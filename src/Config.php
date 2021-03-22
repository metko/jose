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
        $this->config = require_once(ROOT_JOSE.'config.php');
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
                    if(! is_array($user_config)) {
                        throw new FileAlreadyLoadedException('Config file '.$config.' is already loaded. Can be only once.');
                    };
                }

            } else {
                throw new NotFoundException('Config file '.$config .' not found');
            }

        } else {
            $user_config = array_merge($user_config, $config);

        }
        $this->config = array_merge($this->config, $user_config);

    }

    /**
     * @param null $key
     * @return array|string|null
     */
    public function get($key = null)
    {
        $keys = explode('.', $key);

        if (count($keys)) {

            if(count($keys) == 1) {
                if (array_key_exists($key, $this->config)) {
                    return $this->config[$key];
                }
            }

            if (! accessibleArray($this->config)) {
                return $this->config;
            }

            if (is_null($key)) {
                return $this->config;
            }

            if (existKeyInArray($this->config, $key)) {
                return $this->config[$key];
            }

            if (strpos($key, '.') === false) {
                return $this->config[$key] ?? $this->config;
            }

            foreach (explode('.', $key) as $segment) {
                if (accessibleArray($this->config) && existKeyInArray($this->config, $segment)) {
                    $this->config = $this->config[$segment];
                } else {
                    return $this->config;
                }
            }

            return $this->config;

        } else {
            return $this->config;
        }
    }



}