<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function show($category): JsonResource
    {
        $category = Category::where('slug', $category)->firstOrFail();

        return CategoryResource::make($category);
    }

    public function index(): AnonymousResourceCollection
    {
        $categories = Category::jsonPaginate();

        return CategoryResource::collection($categories);
    }
}
