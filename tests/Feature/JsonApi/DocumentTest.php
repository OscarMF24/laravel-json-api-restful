<?php

namespace Tests\Feature\JsonApi;

use Tests\TestCase;
use App\JsonApi\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DocumentTest
 *
 * @package Tests\Feature\JsonApi
 * @author Oscar Muñoz Franco Web developer
 */

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating DocumentTest can_create_json_api_documents.
     *
     * @test
     * @return void
     */
    public function can_create_json_api_documents(): void
    {
        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article title'
            ])->toArray();

        $expected = [
            'data' => [
                'type' => 'articles',
                'id' => 'article-id',
                'attributes' => [
                    'title' => 'Article title'
                ]
            ]
        ];

        $this->assertEquals($expected, $document);
    }
}
