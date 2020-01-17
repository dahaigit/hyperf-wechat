<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');
Router::get('/app/auth/login', 'App\Controller\Wechat\AuthController@login');
Router::post('/user/parse', 'App\Controller\Wechat\UserController@parse');
// 有权限验证
Router::addGroup('/app', function(){
    Router::get('/user/info', 'App\Controller\Wechat\UserController@info');

}, [
    'middleware' => [
        \App\Middleware\AuthCheck::class
    ]
]);