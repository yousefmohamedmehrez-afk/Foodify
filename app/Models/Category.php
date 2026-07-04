<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
        use HasFactory;
protected $fillable = [
    'name',
    'image',
    'description'
];

public function meals()
{
    return $this->hasMany(Meal::class);
}
}
