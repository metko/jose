<?php

return [
    'config' => \Jose\Config::class,
    'post_type' => \Jose\PostType\PostType::class,
    'post_type_class' => \Jose\PostType\PostTypeClassMap::class,
    'task' => \Jose\Task::class,
    'acf_builder' => \StoutLogic\AcfBuilder\FieldsBuilder::class,
    'file' => \Symfony\Component\Filesystem\Filesystem::class,
    'finder' => \Symfony\Component\Finder\Finder::class,
];