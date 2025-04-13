<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeHistories;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    // Display a listing of the recipes
    public function index()
    {
        $recipes = Recipe::with(['product', 'ingredient', 'recipeHistories'])->get();
        return response()->json($recipes);
    }

    // Store a newly created recipe
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            // 'amount' => 'required|numeric',
        ]);

        $recipe = Recipe::create($validated);
        $recipeH = RecipeHistories::create([
            'recipe_id' => $recipe->id,
            'product_id' => $recipe->product_id,
            'ingredient_id' => $recipe->ingredient_id,
            'price_before' => $recipe->price,
            'price_after'=> null,
            'quantity_before' => $recipe->quantity,
            'quantity_after' => null,
            // 'amount_before' => $recipe->amount,
            // 'amount_after' => null,
        ]);

        return response()->json($recipe, 201);
    }

    public function storev2(Request $request){
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'ingredient_id' => 'required|exists:ingredients,id',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);

        $recipe = Recipe::create($validated);
        $recipeH = RecipeHistories::create([
            'recipe_id' => $recipe->id,
            'price_before' => $recipe->price,
            'price_after'=> null,
            'quantity_before' => $recipe->quantity,
            'quantity_after' => null,
        ]);

        return redirect()->route('products.show', $recipe->product_id)->with('success', 'Рецепт успешно обновлен');
    }

    // make updatev2
    public function updatev2(Request $request, int $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'ingredient_id' => 'nullable|exists:ingredients,id',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
        ]);
        $recipeH = RecipeHistories::create([
            'recipe_id' => $recipe->id,
            'price_before' => $recipe->price,
            'price_after'=> $validated['price'] ?? null,
            'quantity_before' => $recipe->quantity,
            'quantity_after' => $validated['quantity'] ?? null,
        ]);

        if ($request->product_id != null && $request->product_id != $recipe->product_id) {
            $recipe->product_id = $request->product_id;
        }
        
        if ($request->recipe_id != null && $request->recipe_id != $recipe->recipe_id) {
            $recipe->recipe_id = $request->recipe_id;
        }
        if ($request->ingredient_id != null && $request->ingredient_id != $recipe->ingredient_id) {
            $recipe->ingredient_id = $request->ingredient_id;
        }
        if ($request->price != null && $request->price != $recipe->price) {
            $recipe->price = $request->price;
        }
        if ($request->quantity != null && $request->quantity != $recipe->quantity) {
            $recipe->quantity = $request->quantity;
        }    

        $recipe->save();

        
        if (!$recipeH) {
            return redirect()->back()->withErrors(['recipeH' => 'Ошибка при создании истории рецепта']);
        }

        return redirect()->route('products.show', $recipe->product_id)->with('success', 'Рецепт успешно обновлен');
    }

    // Display the specified recipe
    public function show($id)
    {
        $recipe = Recipe::with(['product', 'ingredient', 'recipeHistories'])->findOrFail($id);
        return response()->json($recipe);
    }

    // Update the specified recipe
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:products,id',
            'name' => 'nullable|string|max:255',
        ]);
        
        $product = Product::findOrFail($validated['id']);
        if($request->name != null && $request->name != $product->name) {
            $product->name = $request->name;
        }
        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', 'Рецепт успешно обновлен');
    }

    // Remove the specified recipe
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);
        $pId = $recipe->product_id;
        $recipe->delete();

        return redirect()->route('products.show', $pId)->with('success', 'Рецепт успешно удален');
        // return response()->json(null, 204);
    }
}
