<?php
session_start();

class Caminho
{
    private static $connect = null;

    public static function getConn()
    {
        if (self::$connect === null) {
            self::$connect = new PDO(
                'mysql:host=localhost;dbname=ecompartilhar;charset=utf8mb4',
                'root',
                '123'
            );

            self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connect;
    }
}