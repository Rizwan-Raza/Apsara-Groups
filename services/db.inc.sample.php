<?php

class DB
{
    private static $server = "HERE_GOES_SERVER_NAME";
    private static $username = "HERE_GOES_USER_NAME";
    private static $password = "HERE_GOES_PASSWORD";
    private static $dbname = "HERE_GOES_DATABASE_NAME";

    public static function getConnection()
    {
        return new mysqli(self::$server, self::$username, self::$password, self::$dbname);
    }
}
