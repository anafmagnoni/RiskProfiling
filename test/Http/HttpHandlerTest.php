<?php

namespace Test\Origin\RiskProfiling\Http;

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\Serializer;
use Laminas\Diactoros\StreamFactory;
use Origin\RiskProfiling\Http\OriginApplication;
use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\ValidatedUserInfo;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpHandlerTest extends TestCase {

    /**
     * @dataProvider provideInputRequestAndExpectedAPIResponse
     */
    public function testGivenInputRequest_WillLeadToExpectedAPIResponse(
        Request $json_request,
        JsonResponse $expected_json_response
    ) {
        $actual_json_response = OriginApplication::handleRequest($json_request);
        $this->assertResponseEquals($expected_json_response, $actual_json_response);
    }

    public function provideInputRequestAndExpectedAPIResponse(): iterable {
        yield 'Happy path' => [
            $request = new Request(
                $uri = '/defineRiskProfile',
                $method = 'POST',
                $body = $this->createStreamFromJSONBody(),
                $headers = ['Content-Type' => 'application/json'],
            ),
            new JsonResponse(
                $body = [
                    'auto' => 'regular',
                    'disability' => 'ineligible',
                    'home' => 'economic',
                    'life' => 'regular',
                    'renters' => 'ineligible'
                ],
                $status = 200
            )
        ];

        yield 'Unmapped request URI' => [
            $request = new Request(
                $uri = null,
                $method = 'POST',
                $body = $this->createStreamFromJSONBody(),
                $headers = ['Content-Type' => 'application/json'],
            ),
            new JsonResponse(
                $body = [
                    'errors' => 'No action mapped for provided endpoint: ',
                    'status' => 404
                ],
                $status = 404
            )
        ];

        yield 'Invalid JSON format' => [
            $request = new Request(
                $uri = '/defineRiskProfile',
                $method = 'POST',
                $body = $this->getInvalidJSONBody(),
                $headers = ['Content-Type' => 'application/json'],
            ),
            new JsonResponse(
                $body = [
                    'errors' => 'Input info could not be parsed as JSON',
                    'status' => 422
                ],
                $status = 422
            )
        ];

        yield 'Invalid JSON input value' => [
            $request = new Request(
                $uri = '/defineRiskProfile',
                $method = 'POST',
                $body = $this->createStreamFromJSONBody($user_age = 'thirty'),
                $headers = ['Content-Type' => 'application/json'],
            ),
            new JsonResponse(
                $body = [
                    'errors' => ['age string value found, but an integer is required '],
                    'status' => 400
                ],
                $status = 400
            )
        ];
    }

    private function assertResponseEquals(JsonResponse $expected_json_response, ResponseInterface $actual_json_response) {
        $expected_json_response_as_string = Serializer::toString($expected_json_response);
        $actual_json_response_as_string = Serializer::toString($actual_json_response);

        $this->assertEquals($expected_json_response_as_string, $actual_json_response_as_string);
    }

    private function getInvalidJSONBody(): StreamInterface {
        $stream_factory = new StreamFactory();

        return $stream_factory->createStream('
{
    "age"": 35,
    "dependents": 2,
    "house": {"ownership_status": "owned"},
    "income": 0,
    "marital_status": "married",
    "risk_questions": [0, 1, 0],
    "vehicle": {"year": 2018}
}'
        );
    }

    private function createStreamFromJSONBody(int|string $user_age = 35): StreamInterface {
        $stream_factory  = new StreamFactory();

        return $stream_factory->createStream(json_encode([
            'age' => $user_age,
            'dependents' => 2,
            'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
            'income' => 0,
            'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
            'risk_questions' => [0, 1, 0],
            'vehicle' => ['year' => 2018]
        ]));
    }
}
