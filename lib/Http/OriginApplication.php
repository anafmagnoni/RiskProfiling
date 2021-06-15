<?php

namespace Origin\RiskProfiling\Http;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OriginApplication {

    public static function handleRequest(RequestInterface $request): ResponseInterface {
        $request_path = $request->getUri()->getPath();

        if($request_path == '/defineRiskProfile') {
            return RiskProfilingHandler::handleRequest($request);
        }

        return new JsonResponse([
            'errors' => 'No action mapped for provided endpoint: '. $request_path,
            'status' => 404,
        ], 404);
    }
}
