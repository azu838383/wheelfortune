<?php

namespace App\Console\Commands;

use App\Models\UserVoucher;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->tz('Asia/Bangkok')->toDateString();
        $expire = UserVoucher::whereDate('created_at', '<', $today)
            ->where('is_available', 'available')
            ->get();

        foreach ($expire as $voucher) {
            $voucher->created_at->tz('Asia/Bangkok');
            $voucher->update(['is_available' => 'expire']);
        }
    }
}
