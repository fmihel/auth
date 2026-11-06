<?php

class session
{
    private static $enable = false;
    public static function autorize($callback)
    {
        self::$enable = false;

        if ($callback());
    }

    public static function logout()
    {

    }

}
