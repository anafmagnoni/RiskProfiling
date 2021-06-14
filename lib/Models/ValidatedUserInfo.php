<?php

namespace Origin\RiskProfiling\Models;

class ValidatedUserInfo {

    const MARRIED_STATUS = 'married';
    const SINGLE_STATUS = 'single';

    public int $age;
    public int $dependents;
    public UserHouse $house;
    public int $income;
    public string $marital_status;
    public array $risk_questions;
    public UserVehicle $vehicle;

    public function __construct(array $validated_json) {
        $this->age = $validated_json['age'];
        $this->dependents = $validated_json['dependents'];
        $this->house = new UserHouse($validated_json['house']);
        $this->income = $validated_json['income'];
        $this->marital_status = $validated_json['marital_status'];
        $this->risk_questions = $validated_json['risk_questions'];
        $this->vehicle = new UserVehicle($validated_json['vehicle']);
    }
}
