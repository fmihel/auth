<?php

use fmihel\console;
use fmihel\router\exceptions\AccessDenied;
use fmihel\router\input;
use fmihel\router\response;
use fmihel\router\router;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/my-session.php';
require_once __DIR__ . '/router/input.php';
require_once __DIR__ . '/router/router.php';
require_once __DIR__ . '/router/response.php';
require_once __DIR__ . '/router/exceptions/TokenExeption.php';
require_once __DIR__ . '/router/exceptions/AccessDenied.php';

try {

    if (router::enable()) {

        session::autorizeByToken(input::token());

        $module = router::module([__DIR__, 'server']);
        require_once $module;

    }

} catch (AccessDenied $e) {
    response::error($e, 401);
} catch (\Exception $e) {
    console::log($e);
    response::error($e);
}

response::html(__DIR__ . '/index.html');
