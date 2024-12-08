<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    // Relación con la categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación uno a muchos
    public function movements()
    {
        return $this->hasMany(ProductMovement::class);
    }
}
