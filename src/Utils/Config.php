<?php

namespace Jose\Utils;

use ErrorException;

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
     * Get conf array by key / or all the conf
     *
     * @param  string $key
     * @return array|null
     */
    public function get($key = null)
    {
        if ($key) {
            if (array_key_exists($key, $this->config)) {
                return $this->config[$key];
            }
            return null;
        } else {
            return $this->config;
        }
    }
 
    /**
     * Get config instance object
     *
     * @return \Jose\Utils\Config
     */
    public static function getInstance(): \Jose\Utils\Config
    {
        if (!self::$instance) {
     
            self::$instance = new Config();

        }
      
        return self::$instance;
    }

    /**
     * Set a configuration array, or file path
     *
     * @param  string|array $config
     * @return void
     */
    public function define($config): void
    {
        $this->configuration = $config;
    }

    /**
     * Init the configuration object of the app
     * 
     *
     * @return void
     */
    public function init()
    {
        // Rirst, check if conf_path is defined
        if ($this->configuration) {

            // if it's a string, check if the file exist
            if (is_string($this->configuration)) {

                // if defined file doest exists, throw an error
                if (!$this->finder->file_exists(ROOT . $this->configuration)) {
                    throw new ErrorException(ROOT . $this->configuration . " doesnt exists");
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
