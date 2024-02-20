<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class ListArticlesTest
 *
 * @package Tests\Feature\Articles
 * @author Oscar Muñoz Franco Web developer
 */

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating ListArticlesTest.
     *
     * @test
     * @return void
     */
    public function can_fetch_a_single_article(): void
    {
        $this->withoutExceptionHandling();

        $article = Article::factory()->create();

        $response = $this->getJson('/api/v1/articles/' . $article->getRouteKey());

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attribute' => [
                    'title' => $article->title,
                    'slug' => $article->slug,
                    'content' => $article->content
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);

        /*
        *  Verifica que el título del artículo está presente en la respuesta JSON recibida.
        ** $response->assertSee($article->title);
        */
    }
}
