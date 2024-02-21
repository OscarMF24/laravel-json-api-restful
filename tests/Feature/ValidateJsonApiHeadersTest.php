<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ValidateJsonApiHeadersTest
 *
 * @package Tests\Feature
 * @author Oscar Muñoz Franco Web developer
 */

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up.
     *
     * This method is called before each test method is executed. It's used here to configure
     * the routes needed for testing the ValidateJsonApiHeaders middleware.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Route::any('test_route', fn () => 'OK')->middleware(ValidateJsonApiHeaders::class);
    }

    /**
     * Test for creating ValidateJsonApiHeadersTest accept_header_must_be_present_in_all_requests.
     *
     * @test
     * @method GET
     * @return void
     */
    public function accept_header_must_be_present_in_all_requests()
    {
        $this->get('test_route')->assertStatus(406);

        $this->get('test_route', [
            'accept' => 'application/vnd.api+json',
        ])->assertSuccessful();
    }

    /**
     * Test for creating ValidateJsonApiHeadersTest content_type_header_must_be_present_on_all_post_request.
     *
     * @test
     * @method POST
     * @return void
     */
    public function content_type_header_must_be_present_on_all_post_request(): void
    {
        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);

        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /**
     * Test for creating ValidateJsonApiHeadersTest content_type_header_must_be_present_on_all_patch_request.
     * @test
     * @method PATCH
     * @return void
     */
    public function content_type_header_must_be_present_on_all_patch_request(): void
    {
        $this->patch('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);

        $this->patch('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /**
     * Test for creating ValidateJsonApiHeadersTest content_type_header_must_be_present_in_responses.
     * @method GET | POST | PATCH
     * @test
     * @return void
     */
    public function content_type_header_must_be_present_in_responses(): void
    {
        $this->get('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();

        $this->patch('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /**
     * Test for creating ValidateJsonApiHeadersTest content_type_header_must_be_present_in_empty_responses.
     * @test
     * @method GET | POST | PATCH | DELETE
     * @return void
     */
    public function content_type_header_must_be_present_in_empty_responses(): void
    {
        Route::any('empty_response', fn () => response()->noContent())->middleware(ValidateJsonApiHeaders::class);

        $this->get('empty_response', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->post('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->patch('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->delete('empty_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');
    }
}
