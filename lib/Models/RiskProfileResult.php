<?php

namespace Origin\RiskProfiling\Models;

class RiskProfileResult {

    public const INSURANCE_PLAN_INELIGIBLE = 'ineligible';
    public const INSURANCE_PLAN_ECONOMIC = 'economic';
    public const INSURANCE_PLAN_REGULAR = 'regular';
    public const INSURANCE_PLAN_RESPONSIBLE = 'responsible';

    private const VALID_INSURANCE_PLANS = [
        self::INSURANCE_PLAN_INELIGIBLE,
        self::INSURANCE_PLAN_ECONOMIC,
        self::INSURANCE_PLAN_REGULAR,
        self::INSURANCE_PLAN_RESPONSIBLE
    ];

    public string $auto_line;
    public string $disability_line;
    public string $home_line;
    public string $life_line;

    public function __construct(
        string $auto_line,
        string $disability_line,
        string $home_line,
        string $life_line,
    ) {
        $this->validateInsurancePlanValue($auto_line);
        $this->validateInsurancePlanValue($disability_line);
        $this->validateInsurancePlanValue($home_line);
        $this->validateInsurancePlanValue($life_line);

        $this->auto_line = $auto_line;
        $this->disability_line = $disability_line;
        $this->home_line = $home_line;
        $this->life_line = $life_line;
    }

    private function validateInsurancePlanValue(string $insurance_plan_value) {
        if(!in_array($insurance_plan_value, self::VALID_INSURANCE_PLANS)) {
            throw new \InvalidArgumentException("Provided insurance plan value $insurance_plan_value is invalid.");
        }
    }
}
