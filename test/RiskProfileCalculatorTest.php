<?php

namespace Test\Origin\RiskProfiling;

use Origin\RiskProfiling\Models\RiskProfileScore;
use Origin\RiskProfiling\Models\UserHouse;
use Origin\RiskProfiling\Models\ValidatedUserInfo;
use Origin\RiskProfiling\RiskProfileCalculator;
use PHPUnit\Framework\TestCase;

class RiskProfileCalculatorTest extends TestCase {

    /**
     * @dataProvider provideRiskScoreDefiningCases
     */
    public function testGivenUserInfo_WillResultInExpectedRiskProfileScores(
        ValidatedUserInfo $user_info,
        RiskProfileScore $expected_risk_profile_scores
    ) {
        $risk_profile_scores = RiskProfileCalculator::evaluateRiskProfile($user_info);
        $this->assertEquals($expected_risk_profile_scores, $risk_profile_scores);
    }

    public function provideRiskScoreDefiningCases(): iterable {
        yield 'User with zero income' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 0,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(1, null, 0, 2, null),
        ];

        yield 'User with no vehicle' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 0,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => null
            ]),
            new RiskProfileScore(null, 0, 0, 2, null),
        ];

        yield 'User without house properties' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 0,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(1, null, 0, 2, null),
        ];

        yield 'User aged over 60 years old' => [
            new ValidatedUserInfo([
                'age' => 61,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 0,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(2, null, 1, null, null),
        ];

        yield 'User aged between 30 and 40 years old' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 1,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [0, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(0, -1, -1, 1, null),
        ];

        yield 'User aged under 30 years old' => [
            new ValidatedUserInfo([
                'age' => 29,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 1,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(0, -1, -1, 1, null),
        ];

        yield 'User aged over 40 and under 60 years old' => [
            new ValidatedUserInfo([
                'age' => 50,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 10,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(2, 1, 1, 3, null),
        ];

        yield 'User with income above $200k' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 200001,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(0, -1, -1, 1, null),
        ];

        yield 'User with income under $200k' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 199999,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(1, 0, 0, 2, null),
        ];

        yield 'User with mortgaged house' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::MORTGAGED_STATUS],
                'income' => 1,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(1, 1, 1, 2, null),
        ];

        yield 'User with single marital status' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 0,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 1,
                'marital_status' => ValidatedUserInfo::SINGLE_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => 2018]
            ]),
            new RiskProfileScore(1, 0, 0, 0, null),
        ];

        yield 'User with vehicle older than five years ago' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 0,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [1, 0, 0],
                'vehicle' => ['year' => date("Y") - 6]
            ]),
            new RiskProfileScore(0, null, 0, 2, null),
        ];

        yield 'User that answered yes to second risk question will have two points added to Disability score' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::OWNED_STATUS],
                'income' => 10,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [0, 1, 0],
                'vehicle' => ['year' => date("Y") - 6]
            ]),
            new RiskProfileScore(0, 2, 0, 2, null),
        ];

        yield 'User with rented house will be eligible for Renters insurance line' => [
            new ValidatedUserInfo([
                'age' => 35,
                'dependents' => 2,
                'house' => ['ownership_status' => UserHouse::RENTED_STATUS],
                'income' => 10,
                'marital_status' => ValidatedUserInfo::MARRIED_STATUS,
                'risk_questions' => [0, 1, 0],
                'vehicle' => ['year' => date("Y") - 6]
            ]),
            new RiskProfileScore(0, 2, null, 2, 0),
        ];
    }
}
