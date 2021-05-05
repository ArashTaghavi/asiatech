<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['title', 'image'];

    /**
     * @return HasMany
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
