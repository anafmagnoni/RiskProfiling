<?php

namespace Origin\RiskProfiling;

use Origin\RiskProfiling\Models\RiskProfileScore;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class RiskProfileCalculator {

    public static function evaluateRiskProfile(ValidatedUserInfo $user_info): RiskProfileScore {
        $risk_profiles = self::initializeRiskProfiles();
        $risk_profiles = self::applyRiskCalculatingRules($risk_profiles, $user_info);

        return new RiskProfileScore(
            self::getRiskScoreOrNull($risk_profiles['auto']),
            self::getRiskScoreOrNull($risk_profiles['disability']),
            self::getRiskScoreOrNull($risk_profiles['home']),
            self::getRiskScoreOrNull($risk_profiles['life']),
        );
    }

    private static function applyRiskCalculatingRules(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $rules = [
            new Internal\UserRiskQuestionsProfilingRules(),
            new Internal\UserIneligibilityProfilingRules(),
            new Internal\UserAgeProfilingRules(),
            new Internal\UserIncomeProfilingRules(),
            new Internal\UserHouseProfilingRules(),
            new Internal\UserDependentsProfilingRules(),
            new Internal\UserMaritalStatusProfilingRules(),
            new Internal\UserVehicleProfilingRules(),
        ];

        foreach ($rules as $rule) {
            $risk_profiles = $rule->applyRule($risk_profiles, $user_info);
        }

        return $risk_profiles;
    }

    private static function initializeRiskProfiles(): array {
        $insurance_line_template = [
            'risk_score' => 0,
            'risk_score_profile' => null,
            'user_eligibility' => true,
        ];

        return [
            'auto' => $insurance_line_template,
            'disability' => $insurance_line_template,
            'home' => $insurance_line_template,
            'life' => $insurance_line_template,
        ];
    }

    private static function getRiskScoreOrNull(array $risk_profile): ?int {
        if($risk_profile['user_eligibility']) {
            return $risk_profile['risk_score'];
        }

        return null;
    }
}
