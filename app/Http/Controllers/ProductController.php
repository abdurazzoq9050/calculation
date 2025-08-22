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
        return response()->json(Product::with('recipes')->orderBy('name','ASC')->get());
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
            'losses' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $product = Product::findOrFail($validated['id']);
        if($request->name != null && $request->name != $product->name) {
            $product->name = $request->name;
        }
        if($request->losses != null && $request->losses != $product->losses) {
            $product->losses = $request->losses;
        }
        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', 'Продукция успешно обновлена');
    }

    public function destroy(int $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукция успешно удалена');

    }

    public function products(){
        $products = Product::orderBy('name','ASC')->get();
        $title = "Продукции";
        return view('products.index', compact('products', 'title'));
    }

    public function create(){
        $ingredients = Ingredients::get();
        $title = 'Создание продукции';
        return view('products.create', compact('title', 'ingredients'));
    }

    public function storev2(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'losses' => 'required|numeric|min:0|max:100',
            'ingredient_id.*' => 'required|exists:ingredients,id',
            'quantity.*' => 'required|numeric|min:0',
            'price.*' => 'required|numeric|min:0',
        ]);

        $product = Product::create(
            [
                'name' => $validated['name'],
                'losses' => $validated['losses'],
            ]
        );
        
        if (!$product) {
            return redirect()->back()->withErrors(['product' => 'Ошибка при создании продукции']);
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

        return redirect()->route('products.index')->with('success', 'Продукция успешно создана');
    }

    public function edit(int $id)
    {
        $product = Product::with(['recipes.ingredient'])->findOrFail($id);
        
        // Группируем рецепты по типу ингредиента и сразу вычисляем amount
        $groupedRecipes = $product->recipes->groupBy(function ($recipe) {
            $recipe->amount = $recipe->price * $recipe->quantity;
            return $recipe->ingredient->type;
        })->map(function ($group) {
            return $group->sortBy(function ($recipe) {
                return $recipe->ingredient->name;
            })->values();
        });
        
        // Получаем приоритетные группы (сырье и специи)
        $priorityGroups = [
            'сырье' => $groupedRecipes->get('сырье', collect()),
            'специя' => $groupedRecipes->get('специя', collect()),
            'скрытое' => $groupedRecipes->get('скрытое', collect()),
        ];
        
        // Все остальные группы (кроме сырья и специй)
        $otherGroups = $groupedRecipes->except(['сырье', 'специя', 'скрытое']);
        
        // Суммарные значения
        $totalPrice = $product->recipes->sum('amount');

        $totalQuantity = [
            'specias' => $priorityGroups['специя']->filter(function ($recipe) {
                return in_array($recipe->ingredient->unit, ['кг', 'литр']);
            })->sum('quantity'),
        
            'siryo' => $priorityGroups['сырье']->filter(function ($recipe) {
                return in_array($recipe->ingredient->unit, ['кг', 'литр']);
            })->sum('quantity'),

            'secret' => $priorityGroups['скрытое']->filter(function ($recipe) {
                return in_array($recipe->ingredient->unit, ['кг', 'литр']);
            })->sum('quantity'),
        ];
          
        $losses =  ($product->losses * ($totalQuantity['specias'] + $totalQuantity['siryo'])) / 100;

        return view('products.edit', [
            'product' => $product,
            'losses' => $losses,
            'specias' => $priorityGroups['специя'],
            'siryo' => $priorityGroups['сырье'],
            'secret' => $priorityGroups['скрытое'],
            'otherGroups' => $otherGroups,
            'title' => 'Редактирование продукции',
            'ingredients' => Ingredients::all(),
            'totalPrice' => $totalPrice,
            'totalQuantity' => $totalQuantity
        ]);
    }
}
