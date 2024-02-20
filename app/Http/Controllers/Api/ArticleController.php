<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function show(Article $article): JsonResponse
    {
        return new JsonResponse([
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
    }
}
