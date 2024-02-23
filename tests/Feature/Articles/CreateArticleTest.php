<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
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

        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo artículo',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del artículo'
        ])->assertCreated();

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
                    'title' => 'Nuevo artículo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo'
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
        $this->postJson(route('api.v1.articles.store'), [
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('title');
    }

    /**
     * Test for creating CreateArticleTest title_min.
     *
     * @test
     * @return void
     */

    public function title_must_be_at_least_4_characters(): void
    {
        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nue',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('title');
    }

    /**
     * Test for creating CreateArticleTest slug_is_required.
     *
     * @test
     * @return void
     */

    public function slug_is_required(): void
    {
        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'content' => 'Contenido del artículo'
        ])->assertJsonApiValidationErrors('slug');
    }

    /**
     * Test for creating CreateArticleTest slug_is_required.
     *
     * @test
     * @return void
     */

    public function content_is_required(): void
    {
        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo'
        ])->assertJsonApiValidationErrors('content');
    }
}
