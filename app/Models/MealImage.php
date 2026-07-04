<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MealImage extends Model
{
        use HasFactory;
protected $fillable = [
    'meal_id',
    'image'
];

public function meal()
{
    return $this->belongsTo(Meal::class);
}
protected $appends = ['image_url'];

public function getImageUrlAttribute()
{
    return asset('storage/' . $this->image);
}
}
