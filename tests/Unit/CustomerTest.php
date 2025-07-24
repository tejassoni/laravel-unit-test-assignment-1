<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Customer;

/**
 * Unit tests for the Customer model.
 *
 * This test class ensures that key custom logic within the Customer model
 * functions correctly, such as:
 * - The `full_name` accessor that combines first and last names.
 * - The `getHobbyCount()` method which counts the customer's hobbies.
 *
 * These tests validate the behavior of the model in isolation without requiring
 * a database or external services.
 *
 * @package Tests\Unit
 * @author Tejas Soni
 * @contact soni.tejas@live.com
 */
class CustomerTest extends TestCase
{
    /**
     * Test that the full_name accessor on the Customer model returns 
     * the expected concatenated first and last name.
     *
     * @return void
     */
    public function test_full_name_accessor_returns_correct_result(): void
    {
        // Create a new Customer instance with first and last name
        $customer = new Customer([
            'firstname' => 'John',
            'lastname' => 'Doe',
        ]);

        // Assert that the full_name accessor returns the expected string
        $this->assertEquals('John Doe', $customer->full_name);
    }

    /**
     * Test that the getHobbyCount method returns the correct number
     * of hobbies for a customer.
     *
     * @return void
     */
    public function test_get_hobby_count_returns_correct_number(): void
    {
        // Create a new Customer instance with an array of hobbies
        $customer = new Customer([
            'hobbies' => ['Reading', 'Swimming', 'Gaming'],
        ]);

        // Assert that the getHobbyCount method returns the correct count
        $this->assertEquals(3, $customer->getHobbyCount());
    }
}
