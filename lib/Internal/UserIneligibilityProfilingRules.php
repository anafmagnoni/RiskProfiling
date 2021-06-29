<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\UserVehicle;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserIneligibilityProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $risk_profiles = self::applyIncomeIneligibilityRule($risk_profiles, $user_info);
        $risk_profiles = self::applyVehicleIneligibilityRule($risk_profiles, $user_info);
        $risk_profiles = self::applyHouseIneligibilityRule($risk_profiles, $user_info);

        return self::applyAgeIneligibilityRule($risk_profiles, $user_info->age);
    }

    private static function applyIncomeIneligibilityRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if($user_info->income == 0) {
            $risk_profiles['disability']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }

    private static function applyVehicleIneligibilityRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if(!isset($user_info->vehicle)) {
            $risk_profiles['auto']['user_eligibility'] = false;
        }

        return $risk_profiles;
    }

    private static function applyHouseIneligibilityRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        if(!isset($user_info->house)) {
            $risk_profiles['home']['user_eligibility'] = false;
            $risk_profiles['renters']['user_eligibility'] = false;
        }

        if($user_info->house->ownership_status == UserHouse::RENTED_STATUS) {
            $risk_profiles['home']['user_eligibility'] = false;
        }
        else {
            $risk_profiles['renters']['user_eligibility'] = false;
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
