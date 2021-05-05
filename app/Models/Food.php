<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $fillable = ['title', 'price', 'discount', 'description', 'stock', 'sub_category_id', 'image'];
}