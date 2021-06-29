<?php

namespace Origin\RiskProfiling\Internal;

use Origin\RiskProfiling\Models\ValidatedUserInfo;

class UserRiskQuestionsProfilingRules implements RiskProfilerCalculatorRule {

    public function applyRule(array $risk_profiles, ValidatedUserInfo $user_info): array {
        $initial_risk_sum = array_sum((array)$user_info->risk_questions);

        foreach ($risk_profiles as &$risk_profile) {
            $risk_profile['risk_score'] = $initial_risk_sum;
        }

        $risk_profiles['disability'] = $this->applySecondQuestionRule($user_info->risk_questions, $risk_profiles['disability']);

        return $risk_profiles;
    }

    private function applySecondQuestionRule(array $risk_questions, array $disability_line): array {
        if($risk_questions[1]) {
            $disability_line['risk_score'] += 2;
        }

        return $disability_line;
    }
}
