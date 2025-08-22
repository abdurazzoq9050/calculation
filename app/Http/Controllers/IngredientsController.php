<?php

namespace App\Http\Controllers;

use App\Models\Ingredients;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    // NAME MUST BE UNIQUE PLS

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredients::all();
        return response()->json($ingredients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // если форма не нужна (например, API), можно оставить пустым или удалить
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|in:кг,шт,литр,метр',
        ]);

        $ingredient = Ingredients::create($validated);
        return response()->json($ingredient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredients $ingredient)
    {
        if(!$ingredient){
            return response()->json('Ingredient not found',  400);
        }
        return response()->json($ingredient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredients $ingredients)
    {
        // если форма не нужна (например, API), можно оставить пустым или удалить
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredients $ingredient)
    {
        // Валидируем только те поля, которые передаются в запросе
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'unit' => 'sometimes|in:кг,шт,литр,метр',
        ]);

        $ingredient->update($validated);
        // Возвращаем обновленный объект в JSON формате
        return response()->json($ingredient);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredients $ingredient)
    {
        if(!$ingredient){
            return response()->json(['message' => 'Not found!'], 400);
        }
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted successfully']);
    }


    public function ingredients()
    {
        $ingredients = Ingredients::orderBy('name','ASC')->get();
        $title = 'Ингредиенты';
        return view('ingredients.index', compact('ingredients', 'title'));
    }

    public function storev2(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|in:кг,шт,литр,метр',
            'type' => 'required|in:специя,сырье,cкрытое',
        ]);

        $ingredient = Ingredients::create($validated);
        return redirect()->route('ingredients.index')->with('success', 'Ингредиент успешно добавлен');
    }

    public function updatev2(Request $request)
    {
        
        $validated = $request->validate([
            'id' => 'required|exists:ingredients,id',
            'name' => 'nullable|string|max:255',
            'unit' => 'nullable|in:кг,шт,литр,метр',
            'type' => 'nullable|in:специя,сырье,cкрытое',
        ]);

        $ingredient = Ingredients::find($validated['id']);
        if (!$ingredient) {
            return redirect()->route('ingredients.index')->with('error', 'Ингредиент не найден');
        }

        if ($request->name != null && $request->name != $ingredient->name) {
            $validated['name'] = $request->name;
        }
        if ($request->unit != null && $request->unit != $ingredient->unit) {
            $validated['unit'] = $request->unit;
        }
        if ($request->type != null && $request->type != $ingredient->type) {
            $validated['type'] = $request->type;
        }

        $ingredient->update($validated);
        return redirect()->route('ingredients.index')->with('success', 'Ингредиент успешно обновлен');
    }

    public function destroyv2($id)
    {
        $ingredient = Ingredients::find($id);
        if (!$ingredient) {
            return redirect()->route('ingredients.index')->with('error', 'Ингредиент не найден');
        }

        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ингредиент успешно удален');
    }
}
