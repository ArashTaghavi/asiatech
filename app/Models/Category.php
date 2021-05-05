<?php

namespace App\Models;

use App\EloquentHelpers\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasImage;

    protected $fillable = ['title', 'image'];

    public $image_path = 'categories';

    /**
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
