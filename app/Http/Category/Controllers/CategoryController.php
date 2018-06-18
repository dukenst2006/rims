<?php

namespace Rims\Http\Category\Controllers;

use Rims\Domain\Categories\Models\Category;
use Illuminate\Http\Request;
use Rims\App\Controllers\Controller;
use Rims\Domain\Categories\Resources\CategoryCollection;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index()
    {
        $categories = Category::get()->toTree();

        return new CategoryCollection($categories);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Rims\Domain\Categories\Models\Category $category
     * @return CategoryCollection
     */
    public function show(Category $category)
    {
        $categories = Category::descendantsOf($category->id)->toTree($category->id);

        return new CategoryCollection($categories);
    }
}
