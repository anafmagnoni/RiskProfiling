<?php

namespace Origin\RiskProfiling\Models;

class UserHouse {

    public const OWNED_STATUS = 'owned';
    public const MORTGAGED_STATUS = 'mortgaged';
    public const RENTED_STATUS = 'rented';

    public string $ownership_status;

    public function __construct(string $ownership_status) {
        $this->ownership_status = $ownership_status;
    }
}
