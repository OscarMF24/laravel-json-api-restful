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
}
