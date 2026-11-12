<?php

use fmihel\console;
use fmihel\router\exceptions\TokenException;
use fmihel\router\input;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/my-session.php';
require_once __DIR__ . '/router/input.php';

try {

    if ($route = input::getHeaderVar('X-Custom-Route', false)) {

    } else {

    }

    $token = input::token();
    session::autorizeByToken($token);

    $data = input::data();

    console::log($data);

} catch (TokenException $e) {
    response::error($e, 401);
} catch (\Exception $e) {
    console::log($e);
    response::error($e);
}
