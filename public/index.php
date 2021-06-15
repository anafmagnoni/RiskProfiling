<?php

require __DIR__ . '/../vendor/autoload.php';

use Laminas\Diactoros\ServerRequestFactory;
use Narrowspark\HttpEmitter\SapiEmitter;
use Origin\RiskProfiling\Http\OriginApplication;

$request = ServerRequestFactory::fromGlobals();
$response = OriginApplication::handleRequest($request);

$response_emitter = new SapiEmitter();
$response_emitter->emit($response);
