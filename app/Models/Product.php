<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'size', 'price', 'stock', 'image', 'colors', 'shape', 'category'];

    public function movements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}