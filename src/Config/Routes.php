<?php

// Chat API endpoints
$routes->group('chatapi', ['namespace' => '\Tatter\Chat\Controllers'], function($routes)
{
	$routes->resource('messages', ['websafe' => 1]);
});
