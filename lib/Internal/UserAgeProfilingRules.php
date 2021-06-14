<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserAgeProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $risk_profiles = self::applyUnderThirtyRule($risk_profiles, $user_info->age);

        return self::applyBetweenThirtyAndFortyRule($risk_profiles, $user_info->age);
    }

    private static function applyUnderThirtyRule(array $risk_profiles, int $user_age): array {
        if($user_age < 30) {
           return self::deductPointsFromAllInsuranceLines($risk_profiles, $points_to_deduct = 2);
        }

        return $risk_profiles;
    }

    private static function applyBetweenThirtyAndFortyRule(array $risk_profiles, int $user_age): array {
        if(($user_age > 30) && ($user_age < 40)) {
            return self::deductPointsFromAllInsuranceLines($risk_profiles, $points_to_deduct = 1);
        }

        return $risk_profiles;
    }

    private static function deductPointsFromAllInsuranceLines(array $risk_profiles, int $points_to_deduct): array {
        foreach ($risk_profiles as &$risk_profile) {
            $risk_profile['risk_score'] -= $points_to_deduct;
        }

        return $risk_profiles;
    }
}
