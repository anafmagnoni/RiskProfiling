<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserMaritalStatusProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if($user_info->marital_status == ValidatedUserInfo::MARRIED_STATUS) {
            if($risk_profiles['life']['user_eligibility']) {
                $risk_profiles['life']['risk_score'] ++;
            }
            if($risk_profiles['disability']['user_eligibility']) {
                $risk_profiles['disability']['risk_score'] --;
            }
        }

        return $risk_profiles;
    }
}
