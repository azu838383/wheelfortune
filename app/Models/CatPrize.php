<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatPrize extends Model
{
    use HasFactory;
    protected $table = 'cat_prize';
    protected $fillable = [
        'title',
        'unit',
    ];

    public function prizeItems()
    {
        return $this->hasMany(PrizeItem::class, 'cat_id');
    }
}
