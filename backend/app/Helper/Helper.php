<?php

namespace App\Helper;

class Helper
{

    /**
     * The url is normalized where protocol and www is 
     * stripped out to generate a more normalized url
     * This will prevent redundency in the db to save up some 
     * space
     * 
     * @param string
     * @return string
     */
    static function normalizeUr($url)
    {
        $url = preg_replace('#^https?://#', '', $url); //removing protocol
        $url = preg_replace('#^www\.#', '', $url); //removing protocol

        $url = rtrim($url, "/");
        return $url;
    }
}
