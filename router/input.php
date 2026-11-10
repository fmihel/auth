<?php
namespace fmihel\router;

use fmihel\router\exceptions\TokenException;

class input
{
    const EXCEPTION      = '-exception-';
    private static $data = null;

    public static function data($name, $default = self::EXCEPTION)
    {
        try {

            if (self::$data === null) {
                self::$data = json_decode(file_get_contents("php://input"), true);
            }

            if (! isset(self::$data[$name])) {

                throw new \Exception("$name is not defined");
            }

            return self::$data[$name];

        } catch (\Exception $e) {

            if ($default === self::EXCEPTION) {
                throw $e;
            } else {
                return $default;
            }
        }
    }

    public static function token(): string
    {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? getallheaders()['Authorization'] ?? getallheaders()['authorization'] ?? null;
        if (! $auth_header || ! preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
            throw new TokenException('no token in header');

            // http_response_code(401);
            // echo json_encode(["success" => false, "message" => "Доступ запрещен. Токен отсутствует или некорректен."]);
            // exit();
        }
        return $matches[1];

    }
}
