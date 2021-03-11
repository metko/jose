<?php

namespace Jose\Utils;

use ErrorException;
use Jose\Core\Exceptions\ConfigIsNotArrayException;
use Jose\Core\Exceptions\FileNotException;

class Config
{

    /**
     * $instance  \Jose\Utils\Config
    */
    public static $instance = null;

    /**
     * $config array
     * 
     * The config object
    */
    public $config = [];

     /**
     * $configuration string|array
     * 
     * The custom config pass
    */
    public $configuration = null;
    
    /**
     * $finder \Jose\Utils\Finder
     *
     * The finder object
     */
    public $finder = null;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->finder = Finder::getInstance();
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


    /**
     * @return Config|null
     */
    public static function getInstance(): ?Config
    {
        if (!self::$instance) {
            self::$instance = new Config();
        }
      
        return self::$instance;
    }

    /**
     * Init the configuration object of the app
     * @param $config
     * @throws ErrorException
     * @throws FileNotException
     * @throws ConfigIsNotArrayException
     */
    public function init($config)
    {
        if($config) {
            $this->configuration = $config;
        }

        // Rirst, check if conf_path is defined
        if ($this->configuration) {

            // if it's a string, check if the file exist
            if (is_string($this->configuration)) {

                // if defined file doest exists, throw an error
                if (!$this->finder->file_exists(ROOT . $this->configuration)) {
                    throw new FileNotException(ROOT . $this->configuration . " doesnt exists");
                }

                // require the desired file
                $this->configuration = $this->finder->require(ROOT . $this->configuration);

            } else if (!is_array($this->configuration)) {
                throw new ErrorException('the config object must be an array or a file path.');
            }
            
        } else {

            // If not, check if the default path exist in the client app
            if ($this->finder->file_exists(APP . 'config.php')) {
                $this->configuration =  $this->finder->require(APP . 'config.php');
            }

        }

        // Then merge the default conf, with the new conf if exist
        $this->config = array_merge(
            $this->finder->require(ROOT_JOSE . 'config_default.php'),
            $this->configuration ?? []
        );

    }
}
