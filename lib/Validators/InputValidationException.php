<?php

namespace Origin\RiskProfiling\Validators;

class InputValidationException extends \Exception {

    public array $validation_messages;

    public function __construct(array $validation_messages) {
        parent::__construct('Provided input did not pass validation.');
        $this->validation_messages = $validation_messages;
    }
}
