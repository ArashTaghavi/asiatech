<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\EloquentHelpers\HasImage;

class Food extends Model
{
    use HasImage;

    public $image_path = 'foods';

    protected $table = 'foods';

    protected $fillable = ['title', 'price', 'discount', 'description', 'stock', 'sub_category_id', 'image'];
}
