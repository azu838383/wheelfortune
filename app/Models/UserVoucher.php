<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVoucher extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'user_voucher';
    protected $fillable = [
        'username',
        'code_voucher',
        'status_voucher',
        'platform_id',
        'is_available',
        'issued_by',
        'updated_by',
    ];

    public function spinHistories()
    {
        return $this->hasMany(RewardDelivery::class, 'voucher_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function Platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }
}
