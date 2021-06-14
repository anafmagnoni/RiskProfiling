<?php

namespace Test\Origin\RiskProfiling;

use Origin\RiskProfiling\Models\RiskProfileResult;
use Origin\RiskProfiling\Models\ValidatedUserInfo;
use Origin\RiskProfiling\RiskProfileCalculator;
use PHPUnit\Framework\TestCase;

class RiskProfileCalculatorTest extends TestCase {

    public function testValidUserInfoInput_WillResultInExpectedRiskProfiling() {
        $validated_user_info = $this->mockValidatedUserInfo();
        $risk_profile_calculator = new RiskProfileCalculator();

        $risk_profile_result = $risk_profile_calculator->evaluateRiskProfile($validated_user_info);

        $this->assertEquals(RiskProfileResult::INSURANCE_PLAN_REGULAR, $risk_profile_result->auto_line);
        $this->assertEquals(RiskProfileResult::INSURANCE_PLAN_INELIGIBLE, $risk_profile_result->disability_line);
        $this->assertEquals(RiskProfileResult::INSURANCE_PLAN_ECONOMIC, $risk_profile_result->home_line);
        $this->assertEquals(RiskProfileResult::INSURANCE_PLAN_REGULAR, $risk_profile_result->life_line);
    }

    private function mockValidatedUserInfo(): ValidatedUserInfo {
        $example_json = <<<'JSON'
{
  "age": 35,
  "dependents": 2,
  "house": {"ownership_status": "owned"},
  "income": 0,
  "marital_status": "married",
  "risk_questions": [0, 1, 0],
  "vehicle": {"year": 2018}
}
JSON;
        return new ValidatedUserInfo(json_decode($example_json, true));
    }
}
