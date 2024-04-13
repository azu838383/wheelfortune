<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrizeItem extends Model
{
    use HasFactory;
    protected $table = 'prize_item';

    protected $fillable = [
        'title',
        'value',
        'probability',
        'cat_id',
        'updated_at',
    ];

    public function spinHistories()
    {
        return $this->hasMany(RewardDelivery::class, 'prize_id');
    }

    public function catPrize()
    {
        return $this->belongsTo(CatPrize::class, 'cat_id');
    }
}
