<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];
    // protected $with = ['menu'];
    public function menu(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
