# laravel-unit-test-assignment-1
Laravel PHPUnit testing for customer registration module

Type Type : Unit
Description : Unit testing should be test single peace of functionality. They ensure that particular method ( or unit ) of class performs a set of specific tasks.
Example : Test Individual classes / methods, services, helper functions etc
Command : php artisan make:test CustomerTest --unit
Path : laravel-unit-test-assignment-1/tests/Unit/CustomerTest.php

Type Type : Feature
Description : Feature  /  Integration Testing: feature test should hit a route, make sure the response if you want and the database reflects the changes you made. 
Example : Test whole application , Routes, controllers, DB interaction, Middleware, Authentication, Some basic work flow
Command : php artisan make:test CustomerTest
Path : laravel-unit-test-assignment-1/tests/Feature/CustomerTest.php

phpunit.xml
Description : configuration file for PHPUnit, which is the default testing framework in Laravel.
1) Bootstrap File : Specifies which file to load before running tests:
<phpunit bootstrap="vendor/autoload.php">

2) Test Suite Configuration : how PHPUnit should run your tests, and what directories to include:
<testsuites>
    <testsuite name="Unit">
        <directory>tests/Unit</directory>
    </testsuite>
    <testsuite name="Feature">
        <directory>tests/Feature</directory>
    </testsuite>
</testsuites>

-> Run All Test cases
Command : php artisan test

-> Run Unit Test case only
Command : php artisan test --testsuite=Unit

-> Run Feature Test case only
Command : php artisan test --testsuite=Feature
-> Stop Immediatly next all test case if any 1 running test case fails
Command : php artisan test --testsuite=Feature --stop-on-failure

3) Environment Setup : Sets environment variables specifically for testing, so your actual .env and live DB aren't affected:
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
</php>
File base sqlite configuration
Command : touch database/testing.sqlite
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value="database/testing.sqlite"/>
    <env name="BCRYPT_ROUNDS" value="4"/>
    <env name="MAIL_MAILER" value="array"/>
    <env name="QUEUE_CONNECTION" value="sync"/>
    <env name="SESSION_DRIVER" value="array"/>
    <env name="TELESCOPE_ENABLED" value="false"/>
</php>

4) Other Options
-> Colors in output:
<phpunit colors="true">

-> Stop on failure:
<phpunit stopOnFailure="true">

-> Code coverage (optional): PHPUnit which application code to monitor to see what’s being tested and what is not.
<coverage>
    <include>
        <directory>app</directory>
    </include>
</coverage>
Command : phpunit --coverage-html coverage-report
OR 
Command : ./vendor/bin/phpunit --coverage-html coverage-report
After the command completes, you can view the report by opening:
Path : coverage-report/index.html


General Notes
Use Proper Naming
-> Test classes should end with Test (e.g., CustomerTest)
-> Test methods should start with test_ or have @test annotation:
Method 1 : 
/** @test */
public function it_creates_customer_successfully()
{
    // test logic
}

Method 2 :
public function test_example(): void
{
  // test logic
}

Use Laravel Helpers
| Helper                            | Use for                            |
| --------------------------------- | ---------------------------------- |
| `$this->get('/route')`            | Testing GET routes                 |
| `$this->post('/route', [...])`    | Testing form submissions           |
| `$this->assertDatabaseHas()`      | Ensure DB has expected data        |
| `$this->actingAs($user)`          | Simulate authenticated user        |
| `factory()` or `Model::factory()` | Generate test models using seeders |

Database Testing : Use Laravel's in-memory SQLite DB:
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>

Database In tests:
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    // your test methods
}

-> Mock External Services : Use Laravel's built-in mocking:
Http::fake(); // or use Mockery for class-level mocks

-> Keep tests independent — no shared state between tests.

-> Default PHPUnit ExampleTest functions are written in snake case test_the_application_returns_a_successful_response() but it is also allowed camel case testCustomerCanBeCreated() function names