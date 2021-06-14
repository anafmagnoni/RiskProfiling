<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserHouseProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if($user_info->house->ownership_status == UserHouse::MORTGAGED_STATUS) {
            $risk_profiles['disability']['risk_score'] ++;
            $risk_profiles['home']['risk_score'] ++;
        }

        return $risk_profiles;
    }
}
