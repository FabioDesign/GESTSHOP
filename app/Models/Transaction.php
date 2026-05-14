<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relation avec la categorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
