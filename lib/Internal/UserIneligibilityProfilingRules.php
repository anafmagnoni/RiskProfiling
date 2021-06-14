<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\UserVehicle;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserIneligibilityProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $risk_profiles = self::applyIncomeIneligibilityRule($risk_profiles, $user_info->income);
        $risk_profiles = self::applyVehicleIneligibilityRule($risk_profiles, $user_info->vehicle);
        $risk_profiles = self::applyHouseIneligibilityRule($risk_profiles, $user_info->house);

        return self::applyAgeIneligibilityRule($risk_profiles, $user_info->age);
    }

    private static function applyIncomeIneligibilityRule(array $risk_profiles, int $income): array {
        if($income == 0) {
            $risk_profiles['disability']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }

    private static function applyVehicleIneligibilityRule(array $risk_profiles, ?UserVehicle $vehicle): array {
        if(is_null($vehicle)) {
            $risk_profiles['auto']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }

    private static function applyHouseIneligibilityRule(array $risk_profiles, ?UserHouse $house): array {
        if(is_null($house)) {
            $risk_profiles['home']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }

    private static function applyAgeIneligibilityRule(array $risk_profiles, int $user_age): array {
        if($user_age > 60) {
            $risk_profiles['disability']['user_eligibility'] = false;
            $risk_profiles['life']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }
}
