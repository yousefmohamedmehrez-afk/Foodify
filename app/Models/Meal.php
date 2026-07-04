<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meal extends Model
{
        use HasFactory;
protected $fillable = [
    'category_id',
    'name',
    'description',
    'price',
    'image',
    'is_available'
];

public function category()
{
    return $this->belongsTo(Category::class);
}

public function images()
{
    return $this->hasMany(MealImage::class);
}

public function favorites()
{
    return $this->hasMany(Favorite::class);
}
public function cartItems()
{
    return $this->hasMany(CartItem::class);
}
public function reviews()
{
    return $this->hasMany(Review::class);
}
}
