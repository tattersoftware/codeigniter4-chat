<?php

// Chat API endpoints
$routes->group('chatapi', ['namespace' => '\Tatter\Chat\Controllers'], static function ($routes) {
    $routes->resource('messages', ['websafe' => 1]);
});
