# Risk Profiling API

This is an implementation of the [Origin Backend Take-Home Assignment](https://github.com/OriginFinancial/origin-backend-take-home-assignment).

The chosen language for this project is PHP 8.

## Development

The development process is based on Docker.

```shell
docker-compose up
```

To enter the interactive shell of the PHP container:

```shell
docker-compose exec php bash
```

Inside this PHP container, composer is available and ready to use.

## Testing

PHPUnit is the test framework. To run the entire test suite, run the following command inside the PHP container:

```shell
composer test
```

You can also specify a single test class file:

```shell
composer test -- test/RiskProfileCalculatorTest.php
```

As a design decision, all logic algorithms used to evaluate the user's information and calculate her risk profile
were placed in the `Internal` directory, leaving only the class that serves as the controller's entry point exposed.

Only the main class `RiskPorfileCalculator` has a test file for it, covering all possible condition
fulfillments. This way, we test the main class that gathers all the logic, not the secondary ones that are only invoked through the
main class and never by themselves.

