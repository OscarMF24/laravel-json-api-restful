<?php

namespace Tests\Feature\Categories;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ListCategoriesTest
 *
 * @package Tests\Feature\Categories
 * @author Oscar Muñoz Franco Web developer
 */

class ListCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating ListCategoriesTest can_fetch_a_single_category.
     *
     * @test
     * @return void
     */
    public function can_fetch_a_single_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson(route('api.v1.categories.show', $category));

        $response->assertJsonApiResource($category, [
            'name' => $category->name
        ]);
    }

    /** @test */
    public function can_fetch_all_categories()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.categories.index'));

        $response->assertJsonApiResourceCollection($categories, [
            'name'
        ]);
    }
}
