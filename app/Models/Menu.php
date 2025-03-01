<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'category_id', 'description', 'price', 'image', 'stock'];
    protected $with = ['category'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}