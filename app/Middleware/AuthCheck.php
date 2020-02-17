<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthCheck implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $response;

    public function __construct(ContainerInterface $container, \Hyperf\HttpServer\Contract\ResponseInterface $httpResponse)
    {
        $this->container = $container;
        $this->response = $httpResponse;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (empty($request->getHeader('auth_code'))) {
            return $this->response->json([
                'message' => '权限验证没有通过',
                'code' => 401,
            ]);
        }
        return $handler->handle($request);
    }
}