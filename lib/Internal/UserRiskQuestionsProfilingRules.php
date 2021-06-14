<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserRiskQuestionsProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $initial_risk_sum = array_sum((array)$user_info->risk_questions);

        foreach ($risk_profiles as &$risk_profile) {
            $risk_profile['risk_score'] = $initial_risk_sum;
        }

        return $risk_profiles;
    }
}
