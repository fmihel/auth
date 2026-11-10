<?php

use fmihel\router\exceptions\TokenException;
use fmihel\router\input;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/my-session.php';

try {
    $token = input::token();

    session::autorizeByToken($token);

} catch (TokenException $e) {

    http_response_code(401);
    response::data(['success' => 0, 'msg' => 'access denied']);

} catch (\Exception $e) {
    response::error($e);
}
