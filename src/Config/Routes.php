<?php

namespace Tatter\Chat\Config;

$routes ??= service('routes');

// Chat API endpoints
$routes->group('chatapi', ['namespace' => '\Tatter\Chat\Controllers'], static function ($routes) {
    $routes->resource('messages', ['websafe' => 1]);
});
