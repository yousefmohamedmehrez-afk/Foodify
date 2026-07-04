<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
protected $fillable = [
        'user_id',
        'title',
        'city',
        'street',
        'building',
        'floor',
        'apartment',
        'notes',
    ];


public function user()
{
    return $this->belongsTo(User::class);
}}
