<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserAgeProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if($user_info->age < 30) {
            return self::deductPointsFromAllInsuranceLines($risk_profiles, $points_to_deduct = 2);
        }
        else if(($user_info->age >= 30) && ($user_info->age <= 40)) {
            return self::deductPointsFromAllInsuranceLines($risk_profiles, $points_to_deduct = 1);
        }
        else {
            return $risk_profiles;
        }
    }

    private static function deductPointsFromAllInsuranceLines(array $risk_profiles, int $points_to_deduct): array {
        foreach ($risk_profiles as &$risk_profile) {
            if($risk_profile['user_eligibility']) {
                $risk_profile['risk_score'] -= $points_to_deduct;
            }
        }

        return $risk_profiles;
    }
}
