<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;
    protected $table = 'platform';

    protected $fillable = [
        'name',
        'logo',
        'is_active',
    ];

    public function Platform()
    {
        return $this->hasMany(RewardDelivery::class, 'platform_id');
    }
}
