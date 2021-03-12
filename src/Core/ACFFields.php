<?php

namespace Jose\Core;

use Jose\Core\Exceptions\FolderDoesntExistException;
use Jose\Utils\Config;
use Jose\Utils\Finder;
use Timber\Timber;

class ACFFields {


    /**
     * @var string
     */
    private $acf_fields_path;

    public function __construct() {

        if(! $this->acf_fields_path = Config::getInstance()->get("acf_fields_path") ) {
            $this->acf_fields_path = null;
        }
    }

    public function init() {
        if( ! $this->acf_fields_path) return;

        if ( class_exists('ACF')) {
//            add_action( 'acf/init', [$this, 'jose_acf_json_init'], 1, 0 );
//            add_filter('acf/settings/load_json', [$this, 'acf_json_load'], 20);
            $this->jose_acf_php_init();
        }

    }

    public function jose_acf_php_init() {

        if(!Finder::getInstance()->file_exists(ROOT.$this->acf_fields_path)) {
            throw new FolderDoesntExistException(ROOT.$this->acf_fields_path);
        }

        foreach ( Finder::getInstance()->getFiles(ROOT.$this->acf_fields_path) as $file ) {
            $file_path = $file->getPathname();
            require_once($file_path);
        }
    }

    public function acf_json_save( $path ) {
        // update path
        $path = ROOT . $this->acf_fields_path;
        // return
        return $path;

    }
    public function acf_json_load( $paths ) {
        // remove original path
        unset($paths[0]);

        // append path
        $paths[] = ROOT . $this->acf_fields_path;

        // return
        return $paths;

    }
    public function jose_acf_json_init() {
        add_filter('acf/settings/save_json', [$this, 'acf_json_save']);

    }

}


class BuildACFFields extends \StoutLogic\AcfBuilder\FieldsBuilder {

    protected $name = null;

    /**
     * @var false
     */
    private $is_block;

    /**
     * BuildACFFields constructor.
     * @param String $name
     * @param Boolean $block
     * @param array $groupConfig
     */
    public function __construct(String $name, Bool $block, Array $groupConfig ) {
        $this->name = $name;
        $this->is_block = $block;
        parent::__construct($name, $groupConfig);
    }

    /**
     * Create the fields group and assign it automaticaly to the block if it's one
     */
    public function create(): ?BuildACFFields
    {
        if($this->is_block) {
            $this->setLocation("block", "==", "acf/$this->name");
        }
        acf_add_local_field_group($this->build());
        return null;

    }

    /**
     * @param $callback
     * Callback to filter data before render
     * @return BuildACFFields
     */
    public function filter($callback ): BuildACFFields
    {

        add_filter( "timber/acf-gutenberg-blocks-data/$this->name", function( $context) use ($callback){
             return $callback->call($this, $context);
        });

        return $this;
    }


}