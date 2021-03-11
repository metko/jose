<?php

namespace Jose;

use ErrorException;
use Jose\Core\ACFFields;
use Jose\Core\App;
use Jose\Core\Blocks;
use Jose\Core\PostClass;
use Jose\Core\PostClassMap;
use Jose\Core\Theme\RegisterMenu;
use Jose\Core\Theme\Theme;
use Jose\Core\Views;
use Jose\Utils\Config;
use Timber\Timber;

class Jose {


    /**
     * @var bool
     * If Jose has been inited
     */
    public static $hasInit = false;


    /**
     * @return App|null
     * @throws ErrorException
     */
    public static function app(): ?App
    {
        if( ! self::$hasInit) {
            throw new ErrorException('Must init jose first');
        }
        return App::getInstance()->app();
    }


    /**
     * @param null $config
     * @throws Core\Exceptions\ConfigIsNotArrayException
     * @throws Core\Exceptions\FileNotException
     * @throws Core\Exceptions\ManifestAssetsNotFound
     * @throws ErrorException
     */
    public static function init($config = null)
    {
        if( ! self::$hasInit ) {
            // need to check
            if(self::checkRequirments() ) {
               self::registerClass($config);
               self::$hasInit = true;
            }
        }else {
            throw new ErrorException('You already init jose');
        }

    }

    /**
     * @param $config
     * @throws Core\Exceptions\ConfigIsNotArrayException
     * @throws Core\Exceptions\FileNotException
     * @throws Core\Exceptions\ManifestAssetsNotFound
     * @throws ErrorException
     */
    private static function registerClass($config) {
        require(dirname(__DIR__).'/src/Utils/constants.php');
        require(dirname(__DIR__).'/src/Utils/helpers.php');
        Config::getInstance()->init($config);
        Assets::getInstance()->init();
        (new Views())->init();
        add_action( 'init', function ()  {
            (new Theme())->init();
            (new RegisterMenu())->init();

            if ( function_exists( 'add_action' ) && function_exists( 'acf_register_block' ) ) {
                (new ACFFields())->init();
                (new Blocks())->init();
            }

            (new PostClass())->init();
            PostClassMap::getInstance()->apply();
        });
    }


    /**
     * @return bool|null
     * @throws ErrorException
     */
    private static function checkRequirments(): ?bool
    {
        if ( ! class_exists('\WP') ) {
            throw new ErrorException('You need wordpress to use Jose. Sorry...');
        }
        return true;
    }

}