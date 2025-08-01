<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;

/**
 * Feature tests for Customer-related functionalities.
 *
 * This test class covers end-to-end scenarios for customer management,
 * including creation, retrieval, updating, and deletion. It interacts
 * with the database and simulates HTTP requests to verify the
 * application's behavior through its routes and controllers.
 *
 * @package Tests\Feature
 * @author Tejas Soni
 * @contact soni.tejas@live.com
 */
class CustomerTest extends TestCase
{
    use RefreshDatabase; // When dealing with database-related operations, this trait is required to reset the database for each test.

    // =========================================================================
    // Happy Path Scenarios
    // These tests verify that the application behaves correctly under valid conditions.
    // =========================================================================

    /**
     * Test to ensure the /customers route exists and returns a 200 OK status.
     *
     * This verifies that the route is correctly defined and accessible for displaying customers.
     * @return void
     */
    public function test_customers_index_route_exists(): void
    {
        // Send a GET request to the /customers route
        $response = $this->get('/customers');
        // Assert that the response status code is 200 (OK)
        $response->assertStatus(200);
    }

    /**
     * Test that a customer can be successfully created with all valid data.
     *
     * This is a primary "Happy Path" test case for the customer creation process.
     * @return void
     */
    public function test_customer_can_be_created_with_all_valid_data(): void
    {
        // Sample test data for a new customer that needs to be stored
        $customerData = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile' => '1234567890',
            'gender' => 'male',
            'address' => '123 Street Name',
            'hobbies' => ['reading', 'traveling'], // Hobbies as an array
        ];
        // Simulate a POST request to the customer store route with the provided data
        $response = $this->post(route('customers.store'), $customerData);
        // Assert that the HTTP status code is 302 (Redirect) after successful creation
        $response->assertStatus(302);
        // Assert that the user is redirected to the customers index page
        $response->assertRedirect(route('customers.index'));
        // Assert that a database record with the given email exists
        $this->assertDatabaseHas('customers', [
            'email' => 'john.doe@example.com',
            // For JSON columns, assertDatabaseHas expects the JSON string in the DB
            'hobbies' => json_encode(['reading', 'traveling']),
        ]);
        // Assert that a success message is present in the session
        $response->assertSessionHas('success', 'Customer created successfully!');
    }

    /**
     * Test that a customer can be created successfully when optional fields are omitted.
     *
     * This is a "Happy Path" test to ensure validation correctly handles missing optional data.
     * @return void
     */
    public function test_customer_can_be_created_with_optional_fields_omitted(): void
    {
        // Sample test data where 'address' and 'hobbies' are intentionally omitted (optional fields)
        $customerData = [
            'firstname' => 'Alice',
            'lastname' => 'Brown',
            'email' => 'alice@example.com',
            'mobile' => '5551234567',
            'gender' => 'female',
            // 'address' and 'hobbies' are omitted, assuming they are nullable in the database
        ];
        // Simulate a POST request to the customer store route
        $response = $this->post(route('customers.store'), $customerData);
        // Assert that the HTTP status code is 302 (Redirect)
        $response->assertStatus(302);
        // Assert that the user is redirected to the customers index page
        $response->assertRedirect(route('customers.index'));
        // Assert that the customer record exists in the database with null/empty values for omitted fields
        $this->assertDatabaseHas('customers', [
            'firstname' => 'Alice',
            'email' => 'alice@example.com',
            'address' => null, // Assert that optional string field is null
            'hobbies' => '[]', // Assert that optional JSON array field is an empty JSON array
        ]);
        // Assert that a success message is present in the session
        $response->assertSessionHas('success', 'Customer created successfully!');
    }

    /**
     * Test that a customer can be created via the factory and persisted to the database.
     *
     * This is a "Happy Path" test for the Customer factory itself, ensuring it generates valid data.
     * @return void
     */
    public function test_customer_can_be_created_via_factory_and_persisted(): void
    {
        // Use the factory to create a customer instance and persist it to the database
        $customer = Customer::factory()->create();
        // Assert that the created instance is an actual Customer model object
        $this->assertInstanceOf(Customer::class, $customer);
        // Assert that the customer exists in the database using a unique attribute like email
        $this->assertDatabaseHas('customers', [
            'email' => $customer->email,
            // For JSON columns, assertDatabaseHas expects the JSON string in the DB
            'hobbies' => json_encode($customer->hobbies),
        ]);
        // Assert that the database contains exactly one customer record
        $this->assertCount(1, Customer::all());
        // Further assertions on the generated customer model's attributes
        $this->assertNotNull($customer->firstname);
        $this->assertIsString($customer->email);
        $this->assertStringContainsString('@', $customer->email);
        $this->assertIsString($customer->mobile);
        $this->assertContains($customer->gender, ['male', 'female']); // Assuming only 'male'/'female' are valid from factory
        $this->assertIsArray($customer->hobbies);
    }

    /**
     * Test that a customer can be created by POSTing factory-generated data.
     *
     * This combines factory data generation with a simulated POST request for a "Happy Path" scenario.
     * @return void
     */
    public function test_customer_can_be_created_by_posting_factory_data(): void
    {
        // Use the factory to generate customer data without persisting it to the database yet.
        // ->make() creates a model instance, ->toArray() converts it to an array for the request.
        $customerData = Customer::factory()->make()->toArray();
        // Simulate a POST request to the customer store route with the factory-generated data
        $response = $this->post(route('customers.store'), $customerData);
        // Assert that the HTTP status code is 302 (Redirect)
        $response->assertStatus(302);
        // Assert that the user is redirected to the customers index page
        $response->assertRedirect(route('customers.index'));
        // Assert that a success message is present in the session
        $response->assertSessionHas('success', 'Customer created successfully!');
        // Assert that a database record exists using the data from the request
        $this->assertDatabaseHas('customers', [
            'email' => $customerData['email'],
            'hobbies' => json_encode($customerData['hobbies']),
        ]);
    }

    /**
     * Test that a single customer's data is correctly displayed on the show page.
     *
     * This is a "Happy Path" test for retrieving and displaying a specific customer.
     * @return void
     */
    public function test_customer_show_displays_correct_data(): void
    {
        // Create a customer record using the factory to ensure it exists in the database
        $customer = Customer::factory()->create();
        // Simulate a GET request to the customer show route for the created customer's ID
        $response = $this->get(route('customers.show', $customer->id));
        // Assert that the response status code is 200 (OK)
        $response->assertStatus(200);
        // Assert that the customer's first name and email are visible on the page
        $response->assertSee($customer->firstname);
        $response->assertSee($customer->email);
    }

    /**
     * Test that an existing customer can be successfully updated with valid data.
     *
     * This is a "Happy Path" test for the customer update process.
     * @return void
     */
    public function test_customer_can_be_updated_with_valid_data(): void
    {
        // Create an existing customer record that will be updated
        $customer = Customer::factory()->create();
        // Sample test data for updating the customer
        $customerUpdatedData = [
            'firstname' => 'UpdatedFirst',
            'lastname' => 'UpdatedLast',
            'email' => 'updated@example.com',
            'mobile' => '1231231234',
            'gender' => 'male',
            'address' => 'Updated Address',
            'hobbies' => ['cycling', 'swimming'],
        ];
        // Simulate a PUT request to the customer update route for the existing customer's ID
        $response = $this->put(route('customers.update', $customer->id), $customerUpdatedData);
        // Assert that the HTTP status code is 302 (Redirect) after successful update
        $response->assertStatus(302);
        // Assert that the user is redirected to the customers index page
        $response->assertRedirect(route('customers.index'));
        // Assert that a success message is present in the session
        $response->assertSessionHas('success', 'Customer updated successfully!');
        // Assert that the database record has been updated with the new data
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id, // Ensure we are checking the correct record
            'firstname' => 'UpdatedFirst',
            'email' => 'updated@example.com',
            'hobbies' => json_encode($customerUpdatedData['hobbies']),
        ]);
    }

    /**
     * Test that an existing customer can be successfully deleted.
     *
     * This is a "Happy Path" test for the customer deletion process.
     * @return void
     */
    public function test_customer_can_be_deleted_successfully(): void
    {
        // Create a customer record that will be deleted
        $customer = Customer::factory()->create();
        // Simulate a DELETE request to the customer destroy route for the customer's ID
        $response = $this->delete(route('customers.destroy', $customer->id));
        // Assert that the HTTP status code is 302 (Redirect) after successful deletion
        $response->assertStatus(302);
        // Assert that the user is redirected to the customers index page
        $response->assertRedirect(route('customers.index'));
        // Assert that a success message is present in the session
        $response->assertSessionHas('success', 'Customer deleted successfully!');
        // Assert that the customer record no longer exists in the database
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    // =========================================================================
    // Validation & Error Scenarios
    // These tests verify how the application handles invalid input or non-existent resources.
    // =========================================================================

    /**
     * Test customer creation fails with missing required fields.
     *
     * This test ensures that the validation rules correctly identify and reject requests
     * where essential fields are absent during customer creation.
     * @return void
     */
    public function test_customer_creation_fails_with_missing_required_fields(): void
    {
        // Sample invalid data: 'firstname', 'email', 'mobile', 'gender' are missing (required fields)
        $customerInvalidData = [
            'lastname' => 'Doe',
            'address' => '123 Main St',
            'hobbies' => ['Cooking'],
        ];
        // Simulate a POST request to the customer store route with the invalid data
        $response = $this->post(route('customers.store'), $customerInvalidData);
        // Assert that the customer was NOT stored in the database
        $this->assertDatabaseMissing('customers', [
            'lastname' => 'Doe', // Assert on a field that would be present if saved
        ]);
        // Assert that the response redirects back (indicating validation failure)
        $response->assertRedirect();
        // Assert that specific validation errors are present in the session
        $response->assertSessionHasErrors(['firstname', 'email', 'mobile', 'gender']);
    }

    /**
     * Test customer creation fails with an invalid email format.
     *
     * This test verifies that the email validation rule correctly rejects malformed email addresses.
     * @return void
     */
    public function test_customer_creation_fails_with_invalid_email_format(): void
    {
        // Sample invalid data: 'email' has an incorrect format
        $customerInvalidData = [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'invalid-email', // Invalid email format
            'mobile' => '0987654321',
            'gender' => 'female',
            'address' => '456 Oak Ave',
            'hobbies' => [],
        ];
        // Simulate a POST request to the customer store route with the invalid data
        $response = $this->post(route('customers.store'), $customerInvalidData);
        // Assert that the customer was NOT stored in the database
        $this->assertDatabaseMissing('customers', [
            'email' => 'invalid-email', // Assert that the invalid email is not in the DB
        ]);
        // Assert that the response redirects back
        $response->assertRedirect();
        // Assert that an 'email' validation error is present in the session
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test customer creation fails with a duplicate email address.
     *
     * This test ensures that the unique email validation rule prevents duplicate entries.
     * @return void
     */
    public function test_customer_creation_fails_with_duplicate_email(): void
    {
        // Create an existing customer with a specific email
        Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);
        // Sample invalid data: Attempting to create a new customer with the same existing email
        $customerInvalidData = [
            'firstname' => 'EmailExist',
            'lastname' => 'User',
            'email' => 'existing@example.com', // Duplicate email
            'mobile' => '1112223333',
            'gender' => 'male',
            'address' => '789 Pine Ln',
            'hobbies' => [],
        ];
        // Simulate a POST request to the customer store route
        $response = $this->post(route('customers.store'), $customerInvalidData);
        // Assert that the new customer (with the duplicate email) was NOT stored in the database
        $this->assertDatabaseMissing('customers', [
            'email' => 'existing123@example.com', // Assert that this specific record was not created
        ]);
        // Assert that the response redirects back
        $response->assertRedirect();
        // Assert that an 'email' validation error (for uniqueness) is present in the session
        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test customer creation fails if an invalid gender option is provided.
     *
     * This test ensures that the 'gender' field is restricted to allowed values.
     * @return void
     */
    public function test_customer_creation_fails_with_invalid_gender(): void
    {
        // Sample invalid data: 'gender' is set to an unsupported value
        $customerData = [
            'firstname' => 'Alex',
            'lastname' => 'Green',
            'email' => 'alex@example.com',
            'mobile' => '9998887777',
            'gender' => 'other', // Testing an invalid gender option
            'address' => '101 Elm St',
            'hobbies' => ['Drawing'],
        ];
        // Simulate a POST request to the customer store route
        $response = $this->post(route('customers.store'), $customerData);
        // Assert that the customer was NOT stored in the database
        $this->assertDatabaseMissing('customers', [
            'firstname' => 'Alex', // Assert that this specific record was not created
        ]);
        // Assert that the response redirects back
        $response->assertRedirect();
        // Assert that a 'gender' validation error is present in the session
        $response->assertSessionHasErrors(['gender']);
    }

    /**
     * Test that attempting to show a non-existent customer returns a 404 Not Found status.
     *
     * This is an error scenario for the show route.
     * @return void
     */
    public function test_customer_show_fails_for_non_existent_id(): void
    {
        // Use an ID that is highly unlikely to exist in the database
        $invalidId = 999999;
        // Simulate a GET request to the customer show route with the invalid ID
        $response = $this->get(route('customers.show', $invalidId));
        // Assert that the response status code is 404 (Not Found)
        $response->assertStatus(404);
    }

    /**
     * Test customer update fails with missing required fields.
     *
     * This test ensures that validation rules prevent updating a customer
     * if essential fields are removed or left empty.
     * @return void
     */
    public function test_customer_update_fails_with_missing_required_fields(): void
    {
        // Create an existing customer to attempt to update
        $customer = Customer::factory()->create();
        // Sample invalid data: Missing 'firstname', 'email', 'mobile', 'gender' (required fields)
        $customerInvalidData = [
            'lastname' => 'Smith',
            'address' => '123 Street',
            'hobbies' => ['reading'],
        ];
        // Simulate a PUT request to the customer update route with the invalid data
        $response = $this->put(route('customers.update', $customer->id), $customerInvalidData);
        // Assert that the HTTP status code is 302 (Redirect)
        $response->assertStatus(302);
        // Assert that specific validation errors are present in the session
        $response->assertSessionHasErrors(['firstname', 'email', 'mobile', 'gender']);
        // Assert that the customer's data was NOT updated in the database
        $this->assertDatabaseMissing('customers', [
            'lastname' => 'Smith', // Check for a field that would have changed if update succeeded
            'address' => '123 Street',
        ]);
        // Also assert that the original data is still present (optional but good for robustness)
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'firstname' => $customer->firstname,
            'email' => $customer->email,
        ]);
    }

    /**
     * Test customer update fails with an invalid email format.
     *
     * This test verifies that the email validation rule correctly rejects malformed email addresses
     * during a customer update.
     * @return void
     */
    public function test_customer_update_fails_with_invalid_email_format(): void
    {
        // Create an existing customer to attempt to update
        $customer = Customer::factory()->create();
        // Sample invalid data: 'email' has an incorrect format
        $invalidData = [
            'firstname' => 'Invalid',
            'lastname' => 'Email',
            'email' => 'not-an-email', // Invalid email format
            'mobile' => '9998887777',
            'gender' => 'female',
            'address' => 'Invalid Email Rd',
            'hobbies' => ['invalid'],
        ];
        // Simulate a PUT request to the customer update route with the invalid data
        $response = $this->put(route('customers.update', $customer->id), $invalidData);
        // Assert that the HTTP status code is 302 (Redirect)
        $response->assertStatus(302);
        // Assert that an 'email' validation error is present in the session
        $response->assertSessionHasErrors(['email']);
        // Assert that the customer's email was NOT updated to the invalid format in the database
        $this->assertDatabaseMissing('customers', [
            'email' => 'not-an-email',
        ]);
        // Assert that the original email is still present
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'email' => $customer->email,
        ]);
    }

    /**
     * Test customer update fails when attempting to use an email address already taken by another customer.
     *
     * This test ensures that the unique email validation rule works correctly during updates,
     * allowing the current customer to keep their email but preventing them from taking another's.
     * @return void
     */
    public function test_customer_update_fails_with_duplicate_email_of_another_customer(): void
    {
        // Create an existing customer whose email will be used as a duplicate
        $existingCustomer = Customer::factory()->create([
            'email' => 'existing@example.com',
        ]);
        // Create a different customer who will be the target of the update attempt
        $targetCustomer = Customer::factory()->create([
            'email' => 'unique@example.com',
        ]);
        // Sample invalid data: Attempting to update targetCustomer's email to existingCustomer's email
        $invalidData = [
            'firstname' => 'Test',
            'lastname' => 'Duplicate',
            'email' => 'existing@example.com', // This email is already taken by $existingCustomer
            'mobile' => '9990008888',
            'gender' => 'male',
            'address' => 'Duplicate Email Ln',
            'hobbies' => ['something'],
        ];
        // Simulate a PUT request to update the target customer with the duplicate email
        $response = $this->put(route('customers.update', $targetCustomer->id), $invalidData);
        // Assert that the HTTP status code is 302 (Redirect)
        $response->assertStatus(302);
        // Assert that an 'email' validation error (for uniqueness) is present in the session
        $response->assertSessionHasErrors(['email']);
        // Assert that the original existing customer's email is still in the database
        $this->assertDatabaseHas('customers', [
            'id' => $existingCustomer->id,
            'email' => 'existing@example.com',
        ]);
        // Assert that the target customer's email was NOT updated to the duplicate email
        $this->assertDatabaseMissing('customers', [
            'id' => $targetCustomer->id,
            'email' => 'existing@example.com',
        ]);
        // Also assert that the target customer's original email is still present
        $this->assertDatabaseHas('customers', [
            'id' => $targetCustomer->id,
            'email' => 'unique@example.com',
        ]);
    }

    /**
     * Test that attempting to delete a non-existent customer fails gracefully.
     *
     * This is an error scenario for the destroy route, ensuring robust handling of invalid IDs.
     * @return void
     */
    public function test_customer_delete_fails_for_non_existent_id(): void
    {
        // Use an ID that is highly unlikely to exist in the database
        $invalidId = 999999;
        // Simulate a DELETE request to the customer destroy route with the invalid ID
        $response = $this->delete(route('customers.destroy', $invalidId));
        // Assert that the response status code is 404 (Not Found)
        // This assumes your controller returns a 404 for non-existent resources during deletion.
        // Alternatively, it might redirect with an error message, in which case you'd use:
        // $response->assertRedirect()->assertSessionHasErrors('error_message_key');
        $response->assertStatus(404);
    }
}
