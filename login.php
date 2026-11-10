<?php

require_once __DIR__ . '/my-session.php';
require_once __DIR__ . '/router/input.php';
require_once __DIR__ . '/router/response.php';
require_once __DIR__ . '/router/exceptions/AccessDenied.php';

use fmihel\console;
use fmihel\router\exceptions\AccessDenied;
use fmihel\router\input;

try {

    $login = input::data('login');
    $pass  = input::data('password');

    session::autorizeByLogin($login, $pass);

    response::success(['token' => session::$token]);

} catch (AccessDenied $e) {
    response::error($e, 401);
} catch (\Exception $e) {
    console::log($e);
    response::error($e, 401);
};
