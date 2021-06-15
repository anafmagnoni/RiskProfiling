<?php

namespace Origin\RiskProfiling\Models;

class RiskProfilePlanDefiner {

    public const INSURANCE_PLAN_INELIGIBLE = 'ineligible';
    public const INSURANCE_PLAN_ECONOMIC = 'economic';
    public const INSURANCE_PLAN_REGULAR = 'regular';
    public const INSURANCE_PLAN_RESPONSIBLE = 'responsible';

    public static function definePlanForRiskScore(?int $risk_profile_score): string {
        return match (true) {
            ($risk_profile_score === null) => RiskProfilePlanDefiner::INSURANCE_PLAN_INELIGIBLE,
            ($risk_profile_score <= 0) => RiskProfilePlanDefiner::INSURANCE_PLAN_ECONOMIC,
            ($risk_profile_score > 0 && $risk_profile_score <= 2) => RiskProfilePlanDefiner::INSURANCE_PLAN_REGULAR,
            ($risk_profile_score >= 3) => RiskProfilePlanDefiner::INSURANCE_PLAN_RESPONSIBLE,
        };
    }
}
