<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;
use App\Models\UserVoucher;
use Carbon\Carbon;

class UserVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $post = [
        //     [
        //         'username' => 'zeus2085',
        //         'platform_id' => 1,
        //         'code_voucher' => '9v1BjGvEGj1YOLd6Ak',
        //         'issued_by' => 1,
        //         'set_prob' => 'prob_one',
        //         'is_available' => false,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'username' => 'cleophatra2',
        //         'platform_id' => 1,
        //         'code_voucher' => 'rRsplWUnNCcKHzOPLp',
        //         'issued_by' => 2,
        //         'set_prob' => 'prob_two',
        //         'is_available' => true,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        // ];
        // UserVoucher::insert($post);

    }
}