<?php

namespace Origin\RiskProfiling\Http;

use Laminas\Diactoros\Response\JsonResponse;
use Origin\RiskProfiling\Models\RiskProfilePlanDefiner;
use Origin\RiskProfiling\Models\RiskProfileScore;
use Origin\RiskProfiling\Models\ValidatedUserInfo;
use Origin\RiskProfiling\RiskProfileCalculator;
use Origin\RiskProfiling\Validators\InputJSONValidator;
use Origin\RiskProfiling\Validators\InputValidationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RiskProfilingHandler {

    public static function handleRequest(RequestInterface $request): ResponseInterface {
        try {
            $validated_user_input = InputJSONValidator::parseAndValidateInput($request->getBody());
            $validate_user_info = new ValidatedUserInfo($validated_user_input);
        } catch (\JsonException) {
            return new JsonResponse(self::createErrorResponseArray(
                'Input info could not be parsed as JSON',
                422
            ), 422);
        } catch (InputValidationException $input_validation_exception) {
            $error_messages = $input_validation_exception->validation_messages;

            return new JsonResponse(self::createErrorResponseArray(
                $error_messages,
                400
            ), 400);
        }

        $user_risk_profile_scores = RiskProfileCalculator::evaluateRiskProfile($validate_user_info);
        $user_risk_profile_plans = self::createSuccessfulResponseArray($user_risk_profile_scores);

        return new JsonResponse($user_risk_profile_plans, 200);
    }

    private static function createSuccessfulResponseArray(RiskProfileScore $user_risk_profile_scores): array {
        return [
            'auto' => RiskProfilePlanDefiner::definePlanForRiskScore($user_risk_profile_scores->auto_score),
            'disability' => RiskProfilePlanDefiner::definePlanForRiskScore($user_risk_profile_scores->disability_score),
            'home' => RiskProfilePlanDefiner::definePlanForRiskScore($user_risk_profile_scores->home_score),
            'life' => RiskProfilePlanDefiner::definePlanForRiskScore($user_risk_profile_scores->life_score),
            'renters' => RiskProfilePlanDefiner::definePlanForRiskScore($user_risk_profile_scores->renters_score),
        ];
    }

    private static function createErrorResponseArray(array|string $errors, int $status): array {
        return [
            'errors' => $errors,
            'status' => $status,
        ];
    }
}
