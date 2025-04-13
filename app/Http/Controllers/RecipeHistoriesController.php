<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RecipeHistories;
use Illuminate\Http\Request;

class RecipeHistoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('recipes')->get();
        $recipeId = [];
        foreach ($products as $product) {
            foreach ($product->recipes as $recipe) {
                $recipeId[] = $recipe->id;
            }
        }
        // $recipeH = RecipeHistories::whereIn('recipe_id', $recipeId)->get();
        $recipeH = RecipeHistories::whereIn('recipe_id', $recipeId)
        ->orderBy('created_at', 'desc') // или 'asc' для старейших сначала
        ->get();
        $recipeH->map(function ($item) {
            $item->product = $item->recipe->product;
            $item->ingredient = $item->recipe->ingredient;
            return $item;
        });

        $title = "История рецептов";
        return view('recipeHistory.index', compact('products', 'recipeH', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipeHistories $recipeHistories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecipeHistories $recipeHistories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecipeHistories $recipeHistories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipeHistories $recipeHistories)
    {
        //
    }
}
