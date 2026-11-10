<?php
namespace fmihel\router;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class session
{
    const EXCEPTION       = '-exception-';
    public static $server = 'local';
    public static $key    = 'replace';
    public static $expire = 366;

    public static $token        = false;
    protected static $tokenData = [];

    public static function enable(): bool
    {
        return ! ! self::$token;
    }

    public static function autorizeByLogin(string $login, string $password)
    {
        self::$token     = false;
        self::$tokenData = [];

        try {
            if (empty($tokenData = static::createTokenData(['login' => $login, 'password' => $password]))) {
                throw new \Exception('createTokenData');
            }
            self::$token     = self::createToken($tokenData, self::$key, self::$server, self::$expire);
            self::$tokenData = $tokenData;

        } catch (\Exception $e) {

            self::$token     = false;
            self::$tokenData = [];

            throw $e;
        }
    }

    public static function autorizeByToken(string $token)
    {
        self::$token     = false;
        self::$tokenData = [];

        try {
            $decoded = JWT::decode($token, new Key(self::$key, 'HS256'));

            self::$token     = $token;
            self::$tokenData = $decoded->data;

        } catch (\Exception $e) {

            self::$token     = false;
            self::$tokenData = [];

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

    protected static function createTokenData(array $params): array
    {
        return [];
    }

    protected static function data(string $name, $default = self::EXCEPTION)
    {
        return [];
    }

}
