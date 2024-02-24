<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Http\Requests\SaveArticleRequest;

class ArticleController extends Controller
{
    public function index(Request $request): ArticleCollection
    {
        $sortField = $request->input('sort');

        $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

        $sortField = ltrim($sortField, '-');

        $articles = Article::orderBy($sortField, $sortDirection)->get();

        return ArticleCollection::make($articles);
    }

    public function show(Article $article): ArticleResource
    {
        return ArticleResource::make($article);
    }

    public function store(SaveArticleRequest $request): ArticleResource
    {
        $article = Article::create($request->validated($key = 'data.attributes', $default = null));

        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request): ArticleResource
    {
        $article->update($request->validated($key = 'data.attributes', $default = null));

        return ArticleResource::make($article);
    }

    public function destroy(Article $article): Response
    {
        $article->delete();

        return response()->noContent();
    }
}
