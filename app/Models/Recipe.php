<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'ingredient_id',
        'price',
        'quantity',
        'amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function ingredient(){
        return $this->belongsTo(Ingredients::class);
    }

    public function recipeHistories()
    {
        return $this->hasMany(RecipeHistories::class);
    }  


    
}
