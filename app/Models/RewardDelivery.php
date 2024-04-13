<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RewardDelivery extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'reward_delivery';
    protected $fillable = [
        'username',
        'prize_id',
        'prize_title',
        'prize_value',
        'prize_cat',
        'platform_id',
        'voucher_id',
        'delivery_status',
        'count_changes',
        'proced_by',
    ];

    public function userVoucher()
    {
        return $this->belongsTo(UserVoucher::class, 'voucher_id');
    }

    public function Platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'proced_by');
    }

    public function realPrize()
    {
        return $this->belongsTo(PrizeItem::class, 'prize_id');
    }
}
