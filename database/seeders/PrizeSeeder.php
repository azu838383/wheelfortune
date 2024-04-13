<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CatPrize;
use App\Models\PrizeItem;
use Carbon\Carbon;

class PrizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postCat = [
            [
                'title' => 'Uang',
                'unit' => 'amount',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Barang',
                'unit' => 'pcs',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        CatPrize::insert($postCat);

        $postPrize = [
            [
                'title' => "Brio RS 2024",
                'value' => 165000000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "NMax 2024",
                'value' => 36000000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Iphone 15 PM",
                'value' => 22000000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Playstation 5",
                'value' => 8000000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Vivo V24",
                'value' => 6000000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Vivo Y17s",
                'value' => 1500000,
                'first_prob' => '0',
                'second_prob' => '0',
                'cat_id' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Rp.200,000",
                'value' => 200000,
                'first_prob' => '10',
                'second_prob' => '3',
                'cat_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Rp.100,000",
                'value' => 100000,
                'first_prob' => '45',
                'second_prob' => '7',
                'cat_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "Rp.50,000",
                'value' => 50000,
                'first_prob' => '45',
                'second_prob' => '45',
                'cat_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => "ZONK",
                'value' => 0,
                'first_prob' => '0',
                'second_prob' => '45',
                'cat_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        PrizeItem::insert($postPrize);
    }
}
