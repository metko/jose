<?php

namespace Jose;

use Jose\Exception\AppAlreadyRunException;
use Jose\Exception\ExpectedTypeException;
use Jose\Exception\NotFoundException;
use Jose\Exception\ClassDoesntExistException;

class App
{

    /**
     * @var App
     */
    private static $instance;
    /**
     * @var Config
     */
    private $config;

    private $hasRun = false;

    public function __construct()
    {
        // autoload files on hooks
        // dd(jose('config')->get('hooks'));
    }

    public function hasRun() {
        if ($this->hasRun) {
            throw new AppAlreadyRunException('App already run');
        }
        $this->hasRun = true;
    }

    public function run()
    {
        // Thow error if the rhe run methods has already been done
        $this->hasRun();
        
        // Load the user config
        $this->loadUserConfig();

        // Load the user providers facades
        $this->loadUserFacades();

        // Load the user class map
        $this->loadUserClassMap();

        // Load the user routes
        $this->loadUserRoutes();
    
        // Load templater
        $this->initTemplater();
        
        // Set hooks
        $this->setHooks();
        $this->setUserHooks();
        jose('logger')->warning('test');
    }

    private function initTemplater ()
    {
        new \Jose\Components\Templater();
    }

    private function loadUserConfig ()
    {
        jose('config')->loadUserConfig();
    }

    private function loadUserRoutes ()
    {
        // TODO: THROW ERROR
        require_once(ROOT_APP . 'App/routes.php');
    }

    private function loadUserFacades ()
    {
        (Container::getInstance())->loadUserFacades();
    } 

    private function loadUserClassMap ()
    {
        (Container::getInstance())->loadUserClassMap();
    } 
    
    private function setHooks ()
    {
        // SET CONTEXT WITH RESULT OF WP QUERY
        add_action('wp', function () {

            // jose('context')->set('pagination', paginate_links(['type' => 'array']));
            // jose('context')->set('post', jose()->cast(get_queried_object()));
        });
        // add_action('posts_selection', function ($selection) {
        //     // dd($selection);
        //     // jose('context')->set('pagination', paginate_links(['type' => 'array']));
        //     // jose('context')->set('post', jose()->cast(get_queried_object()));
        // });
        add_action('template_include', function ($template) {
            if( !(jose('dispatcher', [$template])->dispatched)) {
                dump('not dispatched');
                return $template;
            };
        });
    }

    private function setUserHooks () 
    {
        /*
        /* Check for actions hooks
        */
        if (array_key_exists('actions', jose('config')->get())) {

            foreach (jose('config')->get('actions') as $hook => $file) {

                add_action($hook, function (...$args) use ($file) {
                    if(is_string($file)) {
                        if (jose('file')->exists(ROOT_APP . $file)) {
                            return require_once(ROOT_APP . $file);
                        } else {
                            throw new NotFoundException('Hook file ' . $file . ' not found');
                        }

                    } else {
                        throw new ExpectedTypeException('Hook key must be a valid path');
                    } 
                });
            }
        }

        /*
        /* Check for filters hooks
        */
        if (array_key_exists('filters', jose('config')->get())) {

            foreach (jose('config')->get('filters') as $hook => $file) {
                add_filter($hook, function (...$args) use ($file) {
                    if(is_string($file)) {
                        if (jose('file')->exists(ROOT_APP . $file)) {
                            require_once(ROOT_APP . $file);
                        } else {
                            throw new NotFoundException('Hook file ' . $file . ' not found');
                        }

                    } else {
                        throw new ExpectedTypeException('Hook key must be a valid path');
                    } 
                });
            }
        }

       
    }

    public function castPosts(Array $posts, $type = null)
    {

        // dd($type);
        if (count($posts)) {
            // dump($posts);
            $post = $posts[0];
            if ($type) {
                $post_type = $type;
            } else {
                $post_type = $post->post_type;
            }
            $container = Container::getInstance();
            $classMap = $container->post_class_map;
            // dd($post_type);
            // If the asked type exists
            if (array_key_exists($post_type,  $classMap)) {
                $modelClass = $classMap[$post_type];
            } else {
                // Determine which model to load
                // If its a post type
                if (isset($post->post_type)) {
                    $modelClass = array_key_exists('post:' . $post->post_type,  $classMap) ? $classMap['post:' . $post->post_type] : null;
                    if (!$modelClass) {
                        $modelClass = array_key_exists($post->post_type,  $classMap) ? $classMap[$post->post_type] : null;
                        if (!$modelClass) {
                            $modelClass = array_key_exists('post',  $classMap) ? $classMap['post'] : null;
                        }
                        if (!$modelClass) {
                            
                            $modelClass = '\Jose\Models\Post';
                        }  
                    }
                } else if ($post instanceof \WP_Term) {
                    $modelClass = array_key_exists( $post->post_type,  $classMap) ? $post->post_type : '\Jose\Models\Term';
                } else if ($post instanceof \Wp_User) {
                    $modelClass = array_key_exists( $post->post_type,  $classMap) ?  $post->post_type : '\Jose\Models\User';
                }

                // if its a term

                // if its a user

                // if its page
            }

            //dd($modelClass);
            // dd($model_name);
            $data = [];
            // dd($modelClass);
            if(class_exists($modelClass)) {
                foreach($posts as $post) {
                    $data[] = new $modelClass($post);
                }
            } else {
                throw new ClassDoesntExistException('Class ' . $modelClass. ' doesnt exists');
            }   
            return $data;
        }
        return [];
    }

 
}
