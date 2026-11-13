<?php

use fmihel\console;
use fmihel\router\input;
use fmihel\router\response;

$value = input::data('value');
console::log('test', $value);

response::ok();
