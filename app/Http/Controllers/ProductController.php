<?php

namespace App\Http\Controllers;

use App\Models\Ingredients;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\RecipeHistories;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::with('recipes')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load('recipes.ingredient');

        return response()->json($product);
    }

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

        return redirect()->route('products.show', $product->id)->with('success', 'Продукт успешно обновлен');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(null, 204);
    }

    public function products(){
        $products = Product::get();
        $title = "Продукты";
        return view('products.index', compact('products', 'title'));
    }

    public function create(){
        $ingredients = Ingredients::get();
        $title = 'Создание продуктов';
        return view('products.create', compact('title', 'ingredients'));
    }

    public function storev2(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ingredient_id.*' => 'required|exists:ingredients,id',
            'quantity.*' => 'required|numeric|min:0',
            'price.*' => 'required|numeric|min:0',
        ]);
        // dd($validated);

        $product = Product::create(
            [
                'name' => $validated['name'],
            ]
        );
        
        if (!$product) {
            return redirect()->back()->withErrors(['product' => 'Ошибка при создании продукта']);
        }

        foreach($validated['ingredient_id'] as $key => $ingredientId) {
            $validated['ingredients'][$key]['ingredient_id'] = $ingredientId;
            $ingredient = Ingredients::find($ingredientId);
            if (!$ingredient) {
                return redirect()->back()->withErrors(['ingredient_id' => 'Ингредиент не найден']);
            }

            $recipe = Recipe::create(
                [
                    'product_id' => $product->id,
                    'ingredient_id' => $ingredientId,
                    'quantity' => $validated['quantity'][$key],
                    'price' => $validated['price'][$key],
                ]
            );
            if (!$recipe) {
                return redirect()->back()->withErrors(['recipe' => 'Ошибка при создании рецепта']);
            }
            $recipeH = RecipeHistories::create([
                'recipe_id' => $recipe->id,
                'price_before' => $recipe->price,
                'price_after'=> null,
                'quantity_before' => $recipe->quantity,
                'quantity_after' => null,
            ]);
            if (!$recipeH) {
                return redirect()->back()->withErrors(['recipeH' => 'Ошибка при создании истории рецепта']);
            }

        }
        // $product = Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Продукт успешно создан');
    }

    public function edit(int $id){
        $product = Product::with('recipes.ingredient')->find($id);
        if (!$product) {
            return redirect()->route('products.index')->withErrors(['message' => 'Продукт не найден']);
        }

        $totalPrice = $product->recipes->sum(function ($recipe) {
                $recipe->amount = $recipe->price * $recipe->quantity;
                return $recipe->price * $recipe->quantity; 
        });
        
        
        $totalQuantitySpecias = $product->recipes->sum(function ($recipe) {
            if($recipe->ingredient->type == 'специя') {
                return $recipe->quantity; 
            }
        });
        $totalQuantitySiryo = $product->recipes->sum(function ($recipe) {
            if($recipe->ingredient->type != 'специя') {
                return $recipe->quantity; 
            }
        });

        $totalQuantity = [ 
            'specias' => $totalQuantitySpecias,
            'siryo' => $totalQuantitySiryo,
        ];

        $ingredients = Ingredients::get();
        $title = 'Редактирование продукта';
        return view('products.edit', compact('product', 'title', 'ingredients', 'totalPrice','totalQuantity'));
    }
}
