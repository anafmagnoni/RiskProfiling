<?php

namespace Origin\RiskProfiling\Models;

class UserVehicle {

    public int $manufacturing_year;

    public function __construct($unvalidated_user_vehicle_info) {
        if(!is_null($unvalidated_user_vehicle_info)) {
            $this->manufacturing_year = $unvalidated_user_vehicle_info['year'];
        }
    }
}
