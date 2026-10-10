<?php
namespace Catstat\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface {
	private ResponseFactoryInterface $respFactory;

	public function __construct(ResponseFactoryInterface $responseFactory) {
		$this->respFactory = $responseFactory;
	}

	public function process(ServerRequestInterface $req, RequestHandlerInterface $handler): ResponseInterface {
		$req_password = $req->getHeaderLine('Password');
		if ($req_password !== $_ENV['PASSWORD']) {
			return $this->respFactory->createResponse()->withStatus(403);
		}
		
		return $handler->handle($req);
	}
}
