<?php

class Jobs {
    
    public static $_dir = '_jobs/';
    public static $dir = '_jobs/';
    
    public static function init() {
        self::$dir = str_replace('/classes', '', str_replace('\\', '/', __DIR__)) . '/' . self::$_dir;
    }

    public static function add() {
        
    }

    public static function get() {
        
    }

    public static function get_all() {
        
    }

}

Jobs::init();
