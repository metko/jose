<?php

return [
    'facades' => [
        'app' => \Jose\App::class,
        'context' => \Jose\Components\Context::class,
        'dispatcher' => \Jose\Dispatcher::class,
        'logger' => \Jose\Components\Logger::class,
        'config' => \Jose\Components\Config::class,
        'post_type' => \Jose\PostType\PostType::class,
        'post_type_class' => \Jose\PostType\PostTypeClassMap::class,
        'task' => \Jose\Task::class,
        'acf_builder' => \StoutLogic\AcfBuilder\FieldsBuilder::class,
        'file' => \Symfony\Component\Filesystem\Filesystem::class,
        'finder' => \Symfony\Component\Finder\Finder::class,
        'view' => \Jose\Components\View::class,
        'assets' => \Jose\Components\Assets::class,
    ],
    'post_class_map' => [
        'post' => \Jose\Models\Post::class,
        'page' => \Jose\Models\Page::class,
        'user' => \Jose\Models\User::class,
        'term' => \Jose\Models\Term::class,
    ],
];