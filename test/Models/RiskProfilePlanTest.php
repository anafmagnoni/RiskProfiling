<?php

namespace Test\Origin\RiskProfiling\Models;

use Origin\RiskProfiling\Models\RiskProfilePlanDefiner;
use PHPUnit\Framework\TestCase;

class RiskProfilePlanTest extends TestCase {

    /** @dataProvider provideInsurancePlanDefiningCases */
    public function testGivenRiskScore_WillBeMappedToExpectedInsurancePlan(?int $insurance_score, $expected_insurance_plan) {
        $this->assertEquals($expected_insurance_plan,
            RiskProfilePlanDefiner::definePlanForRiskScore($insurance_score));
        $this->assertEquals($expected_insurance_plan,
            RiskProfilePlanDefiner::definePlanForRiskScore($insurance_score));
        $this->assertEquals($expected_insurance_plan,
            RiskProfilePlanDefiner::definePlanForRiskScore($insurance_score));
        $this->assertEquals($expected_insurance_plan,
            RiskProfilePlanDefiner::definePlanForRiskScore($insurance_score));
    }

    public function provideInsurancePlanDefiningCases(): iterable {
        yield [0, RiskProfilePlanDefiner::INSURANCE_PLAN_ECONOMIC];
        yield [1, RiskProfilePlanDefiner::INSURANCE_PLAN_REGULAR];
        yield [3, RiskProfilePlanDefiner::INSURANCE_PLAN_RESPONSIBLE];
        yield [null, RiskProfilePlanDefiner::INSURANCE_PLAN_INELIGIBLE];
    }

}
