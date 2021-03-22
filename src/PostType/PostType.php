<?php

namespace Jose\PostType;

use Jose\Exception\PostTypeDoesntExistException;

class PostType
{

    /**
     * All post type
     * @var array
     */
    public $all = [];

    /**
     *
     * Init a new a post type
     *
     * @param $id
     * @param $name
     * @param $plural_name
     * @return PostTypeBuilder
     */
    public function new($id, $name, $plural_name): PostTypeBuilder
    {
         return new PostTypeBuilder($id, $name, $plural_name);
    }

    /**
     * return the post type object
     * @param $postTypeId
     * @return PostTypeBuilder
     * @throws PostTypeDoesntExistException
     */
    public function get($postTypeId = null): PostTypeBuilder
    {
        if( ! $postTypeId) {
            return $this->all;
        }

        if (array_key_exists($postTypeId, $this->all)) {

            $this->current_id = $postTypeId;
            return $this->all[$postTypeId];

        } else {
            throw new PostTypeDoesntExistException('Post type '.$postTypeId . ' doesnt exist');
        }
    }
}