<?php

namespace Origin\RiskProfiling\Models;

class RiskProfileScore {

    public function __construct(
        public ?int $auto_score,
        public ?int $disability_score,
        public ?int $home_score,
        public ?int $life_score,
        public ?int $renters_score,
    ) {}
}
