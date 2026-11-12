<?php

class response
{
    public static function data(array $data, $code = -1)
    {
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if ($code > 0) {
            http_response_code($code);
        }
        echo json_encode($data);
        exit();
    }
    public static function error($e, $code = -1)
    {
        $out = [
            'success' => 0,
            'message' => '',
        ];
        if (is_a($e, '\Exception')) {
            $out['message'] = $e->getMessage();
        } else {
            $out['message'] = "$e";
        }
        self::data($out, $code);
    }
    public static function success($payload, $code = -1)
    {
        self::data([
            'success' => 1,
            'payload' => $payload,
        ], $code);
    }

}
