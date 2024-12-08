<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMovement extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'movement_type'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
