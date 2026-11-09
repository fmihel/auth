<?php

use Firebase\JWT\JWT;

class session
{
    public static $server = 'local';
    public static $key    = 'replace';
    public static $expire = 366;

    private static $token = false;
    public static $data   = [];

    public static function enable(): bool
    {
        return ! ! self::$token;
    }

    public static function autorize($callback)
    {
        try {

            $input_data = json_decode(file_get_contents("php://input"), true);
            if (isset($input_data['password']) && isset($input_data['username'])) {
                self::login($input_data['login'], $input_data['password'], $callback);
            } elseif (isset($input_data['token'])) {

            } else {
                throw new \Exception('no autorize data');
            }

            self::$data = $payload['data'] ?? [];

            if ($callback) {
                $callback($payload);
            }

        } catch (\Exception $e) {
            self::$enable = false;
            self::$data   = [];

            throw $e;
        }
    }

    private static function login(string $login, string $password)
    {
        self::$token = false;
        self::$data  = [];

        try {
            if (empty($data = self::$getNewTokenData(['login' => $login, 'password' => $password]))) {
                throw new \Exception('getDataForToken return empty');
            }
            self::$token = self::createToken($data, $params);
            self::$data  = $payload['data'] ?? [];

        } catch (\Exception $e) {

            self::$token = false;
            self::$data  = [];

            throw $e;
        }
    }

    private static function createToken(array $data, string $key, string $server, $expire): string
    {
        $time    = time();
        $payload = [
            'server' => $server,
            'start'  => $time,
            'stop'   => $time + $expire * 86400,
            'data'   => $data,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;
    }

    public static function getNewTokenData(array $params)
    {}

    // abstract public static function getAutorizeData(array $params);
}
