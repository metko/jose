<?php

namespace Jose;

class Router {

    public static $instance = null;
    public $routes = [];

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Router();
        }
        return self::$instance;
    }
    public static function routes($key = null) {
        $router = self::getInstance();
        if ($key) return array_key_exists($key, $router->routes) ? $router->routes[$key] : [];
        return $router->routes;
    }

    public static function single($post_type, $controller) {
        $router = self::getInstance();
        $router->routes['single'] = [$post_type => $controller];
    }

    public static function archive($post_type, $controller) {
        $router = self::getInstance();
        $router->routes['archive'] = [$post_type => $controller];
    }

    public static function taxonomy($taxonomy, $controller) {
        $router = self::getInstance();
        $router->routes['taxonomy'] = [$taxonomy => $controller];
    }

    public static function template($template, $controller) {
        $router = self::getInstance();
        $router->routes['template'] = [$template => $controller];
    }

    public static function frontpage($controller) {
        $router = self::getInstance();
        $router->routes['frontpage'] = $controller;
    }

    public static function home($controller) {
        $router = self::getInstance();
        $router->routes['home'] = $controller;
    }


    public static function e_404($controller) {
        $router = self::getInstance();
        $router->routes['e_404'] = $controller;
    }

    public static function render($action) {
        jose('dispatcher')->execute($action);
    }
}