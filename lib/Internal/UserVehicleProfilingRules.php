<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserVehicleProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $current_year = date("Y");
        if(($current_year - $user_info->vehicle->manufacturing_year) <= 5) {
            $risk_profiles['auto']['risk_score'] ++;
        }

        return $risk_profiles;
    }
}
