# laravel-unit-test-assignment-1

## Laravel PHPUnit Testing for Customer Registration Module

This document outlines the setup and usage of PHPUnit for unit and feature testing within a Laravel application, specifically focusing on a customer registration module.

---

### Understanding Test Types

Laravel supports two primary types of tests: Unit Tests and Feature/Integration Tests.

#### Unit Tests

* **Type:** Unit
* **Description:** Unit tests focus on a single piece of functionality. They ensure that a particular method (or "unit") of a class performs a set of specific tasks in isolation.
* **Example:** Testing individual classes, methods, services, helper functions, etc.
* **Command to Create:**
    ```bash
    php artisan make:test CustomerTest --unit
    ```
* **Default Path:** `laravel-unit-test-assignment-1/tests/Unit/CustomerTest.php`

#### Feature / Integration Tests

* **Type:** Feature / Integration
* **Description:** Feature tests hit a route, make assertions about the response, and ensure the database reflects any changes made. They test the interaction between multiple components.
* **Example:** Testing the entire application flow, routes, controllers, database interactions, middleware, authentication, and basic workflows.
* **Command to Create:**
    ```bash
    php artisan make:test CustomerTest
    ```
* **Default Path:** `laravel-unit-test-assignment-1/tests/Feature/CustomerTest.php`

---

### `phpunit.xml` Configuration

The `phpunit.xml` file is the configuration file for PHPUnit, the default testing framework in Laravel.

1.  **Bootstrap File:** Specifies which file to load before running tests.
    ```xml
    <phpunit bootstrap="vendor/autoload.php">
    ```

2.  **Test Suite Configuration:** Defines how PHPUnit should run your tests and which directories to include.
    ```xml
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    ```

    **Commands to Run Tests:**

    * **Run All Test Cases:**
        ```bash
        php artisan test
        ```
    * **Run Unit Test Cases Only:**
        ```bash
        php artisan test --testsuite=Unit
        ```
    * **Run Feature Test Cases Only:**
        ```bash
        php artisan test --testsuite=Feature
        ```
    * **Stop Immediately (Stop-on-failure):** Stops all subsequent test cases if any single running test case fails.
        ```bash
        php artisan test --testsuite=Feature --stop-on-failure
        ```

3.  **Environment Setup:** Sets environment variables specifically for testing. This ensures your actual `.env` file and live database are not affected.

    * **In-Memory SQLite Configuration (Default):**
        ```xml
        <php>
            <env name="APP_ENV" value="testing"/>
            <env name="DB_CONNECTION" value="sqlite"/>
            <env name="DB_DATABASE" value=":memory:"/>
            <env name="QUEUE_CONNECTION" value="sync"/>
        </php>
        ```

    * **File-Based SQLite Configuration:**
        First, create the SQLite database file:
        ```bash
        touch database/testing.sqlite
        ```
        Then, configure `phpunit.xml` accordingly:
        ```xml
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
        ```

4.  **Other Options:**

    * **Colors in Output:**
        ```xml
        <phpunit colors="true">
        ```

    * **Stop on Failure:**
        ```xml
        <phpunit stopOnFailure="true">
        ```

    * **Code Coverage (Optional):** Configures PHPUnit to monitor which application code is being tested and which is not.
        ```xml
        <coverage>
            <include>
                <directory>app</directory>
            </include>
        </coverage>
        ```
        **Commands to Generate Report:**
        ```bash
        phpunit --coverage-html coverage-report
        # OR
        ./vendor/bin/phpunit --coverage-html coverage-report
        ```
        After the command completes, you can view the report by opening:
        `coverage-report/index.html`

---

### General Notes for Testing

* **Use Proper Naming:**
    * Test classes should end with `Test` (e.g., `CustomerTest`).
    * Test methods should start with `test_` or have the `@test` annotation:

        **Method 1 (Annotation):**
        ```php
        /** @test */
        public function it_creates_customer_successfully()
        {
            // test logic
        }
        ```

        **Method 2 (Prefix):**
        ```php
        public function test_example(): void
        {
            // test logic
        }
        ```
        *(Note: PHPUnit allows both snake_case (e.g., `test_the_application_returns_a_successful_response()`) and camelCase (e.g., `testCustomerCanBeCreated()`) for test method names.)*

* **Use Laravel Helpers:**

    | Helper                              | Use For                                  |
    | :---------------------------------- | :--------------------------------------- |
    | `$this->get('/route')`              | Testing GET routes                       |
    | `$this->post('/route', [...])`      | Testing form submissions                 |
    | `$this->assertDatabaseHas()`         | Ensuring database has expected data      |
    | `$this->actingAs($user)`             | Simulating an authenticated user         |
    | `factory()` or `Model::factory()`   | Generating test models using model factories |

* **Database Testing:**
    * Use Laravel's in-memory SQLite DB for fast and isolated tests:
        ```xml
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        ```

    * To reset the database before each test when using an actual database connection or file-based SQLite:
        ```php
        use Illuminate\Foundation\Testing\RefreshDatabase;

        class CustomerTest extends TestCase
        {
            use RefreshDatabase; // Resets database for each test

            // your test methods
        }
        ```

* **Mock External Services:** Use Laravel's built-in mocking capabilities or a library like Mockery for class-level mocks:
    ```php
    Http::fake(); // Mocks HTTP client calls
    // or use Mockery for mocking specific classes/dependencies
    ```

* **Keep Tests Independent:** Ensure no shared state between tests to avoid flaky results. Each test should be able to run in isolation.
