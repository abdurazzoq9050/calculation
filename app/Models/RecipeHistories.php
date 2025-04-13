<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeHistories extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'recipe_id',
        'price_before',
        'price_after',
        'quantity_before',
        'quantity_after',
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

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }  
 
}
