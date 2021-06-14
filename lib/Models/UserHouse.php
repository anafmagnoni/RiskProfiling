<?php

namespace Origin\RiskProfiling\Models;

class UserHouse {

    public const OWNED_STATUS = 'owned';
    public const MORTGAGED_STATUS = 'mortgaged';

    public string $ownership_status;

    public function __construct($unvalidated_user_house_info) {
        $this->ownership_status = $unvalidated_user_house_info['ownership_status'];
    }
}
