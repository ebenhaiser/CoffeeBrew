<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $fillable = ['table_number', 'qr_code'];
    protected $with = ['order'];

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
