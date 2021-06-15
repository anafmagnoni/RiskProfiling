<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserIncomeProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if($user_info->income > 200000) {
            foreach ($risk_profiles as &$risk_profile) {
                if($risk_profile['user_eligibility']) {
                    $risk_profile['risk_score'] --;
                }
            }
        }

        return $risk_profiles;
    }
}
