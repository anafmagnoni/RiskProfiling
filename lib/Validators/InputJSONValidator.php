<?php

namespace Origin\RiskProfiling\Validators;

use JsonSchema\Constraints\Constraint;
use JsonSchema\Validator;

class InputJSONValidator {

    /**
     * @throws \JsonException when the input is not a valid JSON
     * @throws InputValidationException when the JSON body does not match the expected schema
     */
    public static function parseAndValidateInput(string $input): array {
        $json_body = json_decode($input, null, 512, JSON_THROW_ON_ERROR);

        $validator = new Validator();
        $json_schema = json_decode(file_get_contents(__DIR__ . '/json-schema.json'));
        $validator->validate($json_body, $json_schema, Constraint::CHECK_MODE_APPLY_DEFAULTS);

        if ($validator->isValid()) {
            return json_decode(json_encode($json_body), true);
        }

        $json_schema_errors = [];
        foreach ($validator->getErrors() as $error) {
            array_push(
                $json_schema_errors,
                $error['property'] . ' ' . strtolower($error['message']) . ' '
            );
        }

        throw new InputValidationException($json_schema_errors);
    }
}
