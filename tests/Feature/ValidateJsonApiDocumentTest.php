<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Class ValidateJsonApiDocumentTest
 *
 * @package Tests\Feature
 * @author Oscar Muñoz Franco Web developer
 */

class ValidateJsonApiDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutJsonApiDocumentFormatting();

        Route::any('test_route', fn () => 'OK')
            ->middleware(ValidateJsonApiDocument::class);
    }

    /**
     * Test for creating ValidateJsonApiDocumentTest only_accepts_valid_json_api_document.
     *
     * @test
     * @return void
     */
    public function only_accepts_valid_json_api_document(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();

        $this->patchJson('test_route', [
            'data' => [
                'id' => '1',
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();
    }

    /** @test */
    public function data_is_required()
    {
        $this->postJson('test_route', [])
            ->assertJsonApiValidationErrors('data');

        $this->patchJson('test_route', [])
            ->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_must_be_an_array()
    {
        $this->postJson('test_route', [
            'data' => 'string'
        ])->assertJsonApiValidationErrors('data');

        $this->patchJson('test_route', [
            'data' => 'string'
        ])->assertJsonApiValidationErrors('data');
    }

    /** @test */
    public function data_type_is_required()
    {
        $this->postJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_type_must_be_a_string()
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'test']
            ]
        ])->assertJsonApiValidationErrors('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'test']
            ]
        ])->assertJsonApiValidationErrors('data.type');
    }

    /** @test */
    public function data_attributes_is_required()
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_attributes_must_be_an_array()
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])->assertJsonApiValidationErrors('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])->assertJsonApiValidationErrors('data.attributes');
    }

    /** @test */
    public function data_id_is_required()
    {
        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.id');
    }

    /** @test */
    public function data_id_must_be_a_string()
    {
        $this->patchJson('test_route', [
            'data' => [
                'id' => 1,
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationErrors('data.id');
    }
}
