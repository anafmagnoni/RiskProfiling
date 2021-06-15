<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserHouseProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if(!isset($user_info->house)) {
            return $risk_profiles;
        }

        if($user_info->house->ownership_status == UserHouse::MORTGAGED_STATUS) {
            if($risk_profiles['disability']['user_eligibility']) {
                $risk_profiles['disability']['risk_score'] ++;
            }
            if($risk_profiles['home']['user_eligibility']) {
                $risk_profiles['home']['risk_score'] ++;
            }
        }

        return $risk_profiles;
    }
}
