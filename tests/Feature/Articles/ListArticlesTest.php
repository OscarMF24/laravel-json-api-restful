<?php

namespace Tests\Feature\Articles;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
     * Test for creating ListArticlesTest can_fetch_a_single_article.
     *
     * @test
     * @return void
     */
    public function can_fetch_a_single_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->getJson(route('api.v1.articles.show', $article));

        $response->assertJsonApiResource($article, [
            'title' => $article->title,
            'slug' => $article->slug,
            'content' => $article->content
        ]);
    }

    /**
     * Test for creating ListArticlesTest can_fetch_all_articles.
     *
     * @test
     * @return void
     */
    public function can_fetch_all_articles(): void
    {
        $articles = Article::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.articles.index'));

        $response->assertJsonApiResourceCollection($articles, [
            'title', 'slug', 'content'
        ]);
    }
}
