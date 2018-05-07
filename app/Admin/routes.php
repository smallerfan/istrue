<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->get('question', 'QuestionController@index');

    $router->post('question/delete', 'QuestionController@delete');
    $router->get('question/{id}/edit', 'QuestionController@edit');
    $router->post('question/import', 'QuestionController@importChoose');
    $router->get('question/create', 'QuestionController@create');
//    $router->resource('admin/question',QuestionController::class);

});
