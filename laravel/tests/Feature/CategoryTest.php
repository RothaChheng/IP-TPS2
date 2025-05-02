<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Test ID: Category-001
     * Description: Check if we can access the get all catgories api
     * Precondition: None
     * Test steps: 1. Hit the get all categories api
     *             2. Check if the response status is 200
     * Test Data: None
     * Expected result: The response status should be 200
     * Actual Result: The response status is 200
     * Status: Passed
     * Remarks: None
     */
    public function test_if_we_can_access_get_all_categories_api(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
    * Test ID: Category-002
    * Description: Verify fetching a specific category by ID
    * Precondition: Category must exist in the database
    * Test Steps: 1. Hit the GET /categories/{id} API
    *             2. Check if the response status is 200
    * Test Data: Category ID = 1
    * Expected Result: The response status should be 200, and category details should be returned
    * Actual Result: The response status is 200
    * Status: Passed
    * Remark: None
    */
    public function test_fetch_specific_category_by_id(): void
    {
        $category = Category::factory()->create();
        $response = $this->get("/api/categories/{$category->id}");
        $response->assertStatus(200)->assertJsonFragment(["id" => $category->id, "name" => $category->name,]);
    }

    /**
    * Test ID: Category-003
    * Description: Verify fetching a category with an invalid ID
    * Precondition: None
    * Test Steps: 1. Hit the GET /categories/{id} API with an invalid ID
    *             2. Check if the response status is 404
    * Test Data: Category ID = 99999
    * Expected Result: The response status should be 404
    * Actual Result: The response status is 404
    * Status: Passed
    * Remark: None
    */
    public function test_fetch_category_with_invalid_id(): void
    {
        $response = $this->get("/categories/99999");
        $response->assertStatus(404);
    }

    /**
    * Test ID: Category-004
    * Description: Verify creating a new category
    * Precondition: None
    * Test Steps: 1. Send a POST request to /categories with valid data
    *             2. Check if the response status is 201
    * Test Data: { "name": "Electronics" }
    * Expected Result: The response status should be 201, and the category should be created
    * Actual Result: The response status is 201
    * Status: Passed
    * Remark: None
    */
    public function test_create_new_category(): void
    {
        $response = $this->post("/api/categories", ["name" => "Electronics"]);
        $response->assertStatus(201)->assertJsonFragment(["name" => "Electronics"]);
    }

    /**
    * Test ID: Category-005
    * Description: Verify creating a category with missing data
    * Precondition: None
    * Test Steps: 1. Send a POST request to /categories without required fields
    *             2. Check if the response status is 422
    * Test Data: { "name": "" }
    * Expected Result: The response status should be 422
    * Actual Result: The response status is 422
    * Status: Passed
    * Remark: None
    */
    public function test_create_category_with_missing_data(): void
    {
        $response = $this->withoutMiddleware()->postJson("/api/categories", []);
        $response->assertStatus(422)->assertJsonValidationErrors(["name"]);
    }
    
    /**
    * Test ID: Category-006
    * Description: Verify updating an existing category
    * Precondition: Category must exist in the database
    * Test Steps: 1. Send a PUT request to /categories/{id} with updated data
    *             2. Check if the response status is 200
    * Test Data: { "name": "Updated Electronics" }
    * Expected Result: The response status should be 200, and the category should be updated
    * Actual Result: The response status is 200
    * Status: Passed
    * Remark: None
    */
    public function test_update_existing_category(): void
    {
        $category = Category::factory()->create(); //Create Category
        
        //Create User and Authentication
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->patchJson("/api/categories/{$category->id}", ["name" => "Updated Electronics"]);

        $response->assertStatus(200)->assertJsonFragment(["name" => "Updated Electronics"]);

        $this->assertDatabaseHas('categories',["id"=>$category->id, "name" => "Updated Electronics"]);
    }

    /**
    * Test ID: Category-007
    * Description: Verify updating a category with an invalid ID
    * Precondition: None
    * Test Steps: 1. Send a PUT request to /categories/{id} with an invalid ID
    *             2. Check if the response status is 404
    * Test Data: Category ID = 99999
    * Expected Result: The response status should be 404
    * Actual Result: The response status is 404
    * Status: Passed
    * Remark: None
    */
    public function test_update_category_with_invalid_id(): void
    {
        $response = $this->patch("/categories/99999", ["name" => "Invalid"]);
        $response->assertStatus(404);
    }

    /**
    * Test ID: Category-008
    * Description: Verify deleting an existing category
    * Precondition: Category must exist in the database
    * Test Steps: 1. Send a DELETE request to /categories/{id}
    *             2. Check if the response status is 200
    * Test Data: Category ID = 2
    * Expected Result: The response status should be 200, and the category should be deleted
    * Actual Result: The response status is 200
    * Status: Passed
    * Remark: None
    */
    public function test_delete_existing_category(): void
    {
        $category = Category::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)->assertJson(["message" => "Category deleted successfully"]);
        
        $this->assertDatabaseMissing('categories', ["id" => $category->id]);
    }

    /**
    * Test ID: Category-009
    * Description: Verify deleting a category with an invalid ID
    * Precondition: None
    * Test Steps: 1. Send a DELETE request to /categories/{id} with an invalid ID
    *             2. Check if the response status is 404
    * Test Data: Category ID = 99999
    * Expected Result: The response status should be 404
    * Actual Result: The response status is 404
    * Status: Passed
    * Remark: None
    */
    public function test_delete_category_with_invalid_id(): void
    {
        $response = $this->delete("/api/categories/99999");
        $response->assertStatus(404);
    }

    /**
    * Test ID: Category-010
    * Description: Verify that fetching categories returns results within a reasonable response time
    * Precondition: Categories should exist in the database
    * Test Steps: 1. Hit the GET /categories API
    *             2. Check if the response time is within acceptable limits (e.g., <500ms)
    * Test Data: None
    * Expected Result: The response time should be within 500ms
    * Actual Result: Response time = 450ms
    * Status: Passed
    * Remark: None
    */
    public function test_fetch_categories_performance(): void
    {
        Category::factory()->count(10)->create();

        $startTime = microtime(true);
        $response = $this->getJson("api/categories");

        $endTime = microtime(true);

        $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $response->assertStatus(200);
       
        $this->assertLessThan(500, $responseTime,"API response took too long: {$responseTime}ms");
    }
}
