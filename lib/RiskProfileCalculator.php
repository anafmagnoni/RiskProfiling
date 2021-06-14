<?php

namespace Origin\RiskProfiling;

use Origin\RiskProfiling\Models\RiskProfileResult;
use Origin\RiskProfiling\Models\ValidatedUserInfo;

class RiskProfileCalculator {

    public function evaluateRiskProfile(ValidatedUserInfo $user_info): RiskProfileResult {
        $risk_profiles = $this->initializeRiskProfiles();
        $risk_profiles = $this->applyRiskCalculatingRules($risk_profiles, $user_info);

        return new RiskProfileResult(
            self::definePlanForInsuranceLine($risk_profiles['auto']),
            self::definePlanForInsuranceLine($risk_profiles['disability']),
            self::definePlanForInsuranceLine($risk_profiles['home']),
            self::definePlanForInsuranceLine($risk_profiles['life']),
        );
    }

    private function applyRiskCalculatingRules(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $rules = [
            new Internal\UserRiskQuestionsProfilingRules(),
            new Internal\UserIneligibilityProfilingRules(),
            new Internal\UserAgeProfilingRules(),
            new Internal\UserIncomeProfilingRules(),
            new Internal\UserHouseProfilingRules(),
            new Internal\UserDependentsProfilingRules(),
            new Internal\UserMaritalStatusProfilingRules(),
            new Internal\UserVehicleProfilingRules(),
        ];

        foreach ($rules as $rule) {
            $risk_profiles = $rule->applyRule($risk_profiles, $user_info);
        }

        return $risk_profiles;
    }

    private function initializeRiskProfiles(): array {
        $insurance_line_template = [
            'risk_score' => 0,
            'risk_score_profile' => null,
            'user_eligibility' => true,
        ];

        return [
            'auto' => $insurance_line_template,
            'disability' => $insurance_line_template,
            'home' => $insurance_line_template,
            'life' => $insurance_line_template,
        ];
    }

    private static function definePlanForInsuranceLine(array $insurance_line): string {
        $risk_score = $insurance_line['risk_score'];

        if(!$insurance_line['user_eligibility']) {
            return RiskProfileResult::INSURANCE_PLAN_INELIGIBLE;
        }

        return match (true) {
            ($risk_score <= 0) => RiskProfileResult::INSURANCE_PLAN_ECONOMIC,
            ($risk_score > 0 && $risk_score <= 2) => RiskProfileResult::INSURANCE_PLAN_REGULAR,
            ($risk_score >= 3) => RiskProfileResult::INSURANCE_PLAN_RESPONSIBLE,
        };
    }

}
