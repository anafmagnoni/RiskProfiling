<?php

namespace Origin\RiskProfiling\Models;

class UserVehicle {

    public int $manufacturing_year;

    public function __construct(int $manufacturing_year) {
        $this->manufacturing_year = $manufacturing_year;
    }
}
