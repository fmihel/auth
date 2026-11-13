<?php

use fmihel\console;
use fmihel\router\exceptions\TokenException;
use fmihel\router\input;
use fmihel\router\response;
use fmihel\router\router;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/my-session.php';
require_once __DIR__ . '/router/input.php';
require_once __DIR__ . '/router/router.php';
require_once __DIR__ . '/router/response.php';

try {

    if (router::enable()) {

        session::autorizeByToken(input::token());

        $module = router::module([__DIR__, 'server']);
        require_once $module;

    }

} catch (TokenException $e) {
    response::error($e, 401);
} catch (\Exception $e) {
    console::log($e);
    response::error($e);
}

response::html(__DIR__ . '/index.html');
