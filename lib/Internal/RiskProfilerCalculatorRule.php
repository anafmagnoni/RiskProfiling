<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

interface RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array;
}
