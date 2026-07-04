<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Favorite extends Model
{
protected $fillable = [
    'user_id',
    'meal_id'
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function meal()
{
    return $this->belongsTo(Meal::class);
}}
