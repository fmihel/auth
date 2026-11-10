<?php
require_once __DIR__ . '/my-session.php';
require_once __DIR__ . '/router/input.php';
require_once __DIR__ . '/router/response.php';

use fmihel\console;
use fmihel\router\input;

try {

    $login = input::data('login');
    $pass  = input::data('password');

    console::log($login, $pass);
    session::autorizeByLogin($login, $pass);

    response::data([
        'success' => true,
        'token'   => session::$token,
    ]);

} catch (\Exception $e) {
    response::error($e);
};
