# Risk Profiling API

This is an implementation of the [Origin Backend Take-Home Assignment](https://github.com/OriginFinancial/origin-backend-take-home-assignment).

The chosen language for this project is PHP 8.

## Development

The development process is based on Docker:

```shell
docker-compose up
```

To enter the interactive shell of the PHP container:

```shell
docker-compose exec php bash
```

Inside this PHP container, composer is available and ready to use.

## Testing

### Running tests
PHPUnit is the test framework. To run the entire test suite, run the following command inside the PHP container:

```shell
composer test
```

You can also specify a single test class file:

```shell
composer test -- test/RiskProfileCalculatorTest.php
```

### Testing design
As a design decision, all logic algorithms used to evaluate the user's information and calculate her risk profile
were placed in the `Internal` directory, leaving only the class that serves as the handlers's entry point in the project root. 

Only the main class `RiskPorfileCalculator` has a test file for it, that covers all border cases and input varieties.

## Performing requests to the API

The endpoint `defineRiskProfile` is where all requests for user risk profiling must arrive. You can use this `cURL` command
with the wanted input to access it and retrieve the wanted data:

```shell
curl --header "Content-Type: application/json" \
  --request POST \
  --data '
  {
    "age": 35,
    "dependents": 2,
    "house": {"ownership_status": "owned"},
    "income": 0,
    "marital_status": "married",
    "risk_questions": [0, 1, 0],
    "vehicle": {"year": 2018}
  }
  ' \
  http://localhost:8080/defineRiskProfile
```

This way, what is tested is the main class that gathers all the logic, not the secondary ones that are only invoked through the
main class and never by themselves, providing realistic test cases.

## Input validation

The [JSON Schema lib](https://github.com/justinrainbow/json-schema) was chosen to validate the input fields.
This lib provides good error handling and makes it very easy for the developer to manipulate and add/remove data from
the JSON schema.

The defined JSON schema for this API can be found on the `json-schema.json` file, inside the project's `Http` directory.

## HTTP protocol

This first version of the Risk Profiling API does not use any specific framework to link the project's logic algorithms
to the HTTP layer. This decision was made upon the fact that only little HTTP logic was needed to create the server's 
communication and error handling.

By creating the PHP project from scratch, it was possible to load only the necessary libs
and leave the project's directory as clean and succinct as possible. Being this way, new frameworks and
HTTP logic can be easily added.
