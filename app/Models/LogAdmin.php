<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model
{
    use HasFactory;
    protected $table = 'log_admin_activity';

    protected $fillable = [
        'uid',
        'act_on',
        'activity',
        'detail',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
