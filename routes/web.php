<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix' => '/'], function() use ($router) {
    $router->get('', 'PortalController@list');
    $router->get('/portal/{portal}', 'PortalController@list');
    $router->get('/portal/{portal}/page', 'PortalController@list');
    $router->get('/portal/{portal}/page/{page}', 'PortalController@list');
    $router->get('/portal/{portal}/neighborhood/{neighborhood}', 'PortalController@list');
});

