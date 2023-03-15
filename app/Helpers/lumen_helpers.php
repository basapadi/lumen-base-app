<?php

if (!function_exists('config_path')) {

    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '') {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }

}

if (!function_exists('urlGenerator')) {

    /**
     * @return \Laravel\Lumen\Routing\UrlGenerator
     */
    function urlGenerator() {
        return new \Laravel\Lumen\Routing\UrlGenerator(app());
    }

}

if (!function_exists('asset')) {

    /**
     * @param $path
     * @param bool $secured
     *
     * @return string
     */
    function asset($path, $secured = false) {
        return urlGenerator()->asset($path, $secured);
    }

}

if (!function_exists('public_path')) {

    /**
     * Return the path to public dir
     *
     * @param null $path
     *
     * @return string
     */
    function public_path($path = null) {
        return rtrim(app()->basePath('public/' . $path), '/');
    }

}