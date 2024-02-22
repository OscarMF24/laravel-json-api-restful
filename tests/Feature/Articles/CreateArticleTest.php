<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class CreateArticleTest
 *
 * @package Tests\Feature\Articles
 * @author Oscar Muñoz Franco Web developer
 */

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating CreateArticleTest can_create_articles.
     *
     * @test
     * @return void
     */

    public function can_create_articles(): void
    {
        $this->withExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido de articulo'
                ]
            ]
        ]);

        $response->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido de articulo'
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }

    /**
     * Test for creating CreateArticleTest title_is_required.
     *
     * @test
     * @return void
     */

    public function title_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido de articulo'
                ]
            ]
        ]);

        $response->assertJsonStructure([
            'errors' => [
                ['title', 'detail', 'source' => ['pointer']]
            ]
        ])->assertJsonFragment([
            'source' => ['pointer' => '/data/attributes/title']
        ])->assertHeader(
            'content-type',
            'application/vnd.api+json'
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        //$response->assertJsonValidationErrors('data.attributes.title');
    }

    /**
     * Test for creating CreateArticleTest title_min.
     *
     * @test
     * @return void
     */

    public function title_min(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'ABC',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido de articulo'
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.title');
    }

    /**
     * Test for creating CreateArticleTest slug_is_required.
     *
     * @test
     * @return void
     */

    public function slug_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'content' => 'Contenido de articulo'
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.slug');
    }

    /**
     * Test for creating CreateArticleTest slug_is_required.
     *
     * @test
     * @return void
     */

    public function content_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                ]
            ]
        ]);

        $response->assertJsonValidationErrors('data.attributes.content');
    }
}
