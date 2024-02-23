<?php

namespace Tests\Feature\Articles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Article;
use Tests\TestCase;

/**
 * Class DeleteArticleTest
 *
 * @package Tests\Feature\Articles
 * @author Oscar Muñoz Franco Web developer
 */

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for creating DeleteArticleTest can_delete_articles.
     *
     * @test
     * @return void
     */
    public function can_delete_articles()
    {
        $article = Article::factory()->create();

        $this->deleteJson(route('api.v1.articles.destroy', $article))->assertNoContent();

        $this->assertDatabaseCount('articles', 0);
    }
}
