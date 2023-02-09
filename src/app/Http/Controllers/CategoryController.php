<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('children', 'parents')->get();
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        return $category->load('children', 'parents');
    }

    public function show(Category $category)
    {
        return $category->load('children', 'parents');
    }

    public function update(Category $category, Request $request)
    {
        $category->update($request->all());
        return $category->load('children', 'parents');
    }

    public function destroy(Category $category)
    {
        $category->delete();
    }
}
