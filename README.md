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
composer test -- test/SampleTest.php
```
