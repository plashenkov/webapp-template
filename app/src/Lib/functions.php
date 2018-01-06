<?php

if (!function_exists('ee')) {
    function ee()
    {
        echo '<pre>';
        array_map('var_dump', func_get_args());
        echo '</pre>';
        exit;
    }
}

if (!function_exists('getMaxUploadSize')) {
    function getMaxUploadSize()
    {
        $convertPHPSizeToBytes = function ($size) {
            if (is_numeric($size)) {
                return $size;
            }

            $powers = ['K' => 1, 'M' => 2, 'G' => 3, 'Y' => 4, 'P' => 5];
            $suffix = substr($size, -1);
            $size = substr($size, 0, -1);

            return $size * (1024 ** $powers[$suffix]);
        };

        $postMaxSize = ini_get('post_max_size');
        $uploadMaxSize = ini_get('upload_max_filesize');

        return min(
            $convertPHPSizeToBytes($postMaxSize),
            $convertPHPSizeToBytes($uploadMaxSize)
        );
    }
}

if (!function_exists('arrayGetItem')) {
    function arrayGetItem($array, $key, $default = null)
    {
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }
}

if (!function_exists('arrayTakeOnly')) {
    function arrayTakeOnly($array, $takeOnly = null)
    {
        if (is_array($takeOnly)) {
            return array_intersect_key($array, array_flip($takeOnly));
        }

        return $array;
    }
}
