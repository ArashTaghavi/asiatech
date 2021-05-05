<?php

namespace App\Models;

use App\EloquentHelpers\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategory extends Model
{
    use HasImage;

    protected $fillable = ['title', 'category_id', 'image'];

    public $image_path = 'categories';

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
