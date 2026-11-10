<?php

class response
{
    public static function data(array $data)
    {
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Authorization, Content-Type");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        echo json_encode($data);
        exit();
    }
    public static function error($e)
    {
        $out = [
            'success' => 0,
            'msg'     => '',
        ];
        if (is_a($e, '\Exception')) {
            $out['msg'] = $e->getMessage();
        } else {
            $out['msg'] = "$e";
        }
        self::data($out);
    }

}
