<?php

namespace Database\Seeders;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $postCat = [
            [
                'set_for' => 'seo_title',
                'value' => 'Risegroup Spining Wheel Fortune',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'seo_key_words',
                'value' => 'risegroup, spining, fortune, buah4d, ransjitu,',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'seo_description',
                'value' => 'Risegroup Spining Wheel Fortune is apps for share reward to player',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'hyperlink',
                'value' => 'https://heylink.com',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'ip_white_list',
                'value' => '127.0.0.1,120.28.56.131,103.239.54.133,103.88.155.212',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'favicon',
                'value' => null,
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'win_text',
                'value' => 'Selamat kepada <span id="reward-username"></span>! Anda berhak atas Hadiah <span id="reward-value"></span>! Kami dengan senang hati akan segera memproses hadiah Anda dalam waktu 1 hari kerja. Terima kasih telah memilih <span id="reward-platform"></span> untuk hiburan Anda! Teruslah bermain dan menangkan lebih banyak hadiah menarik bersama kami!',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'lose_text',
                'value' => 'Wah, sepertinya diputaran ini <span id="lose-username"></span> kurang beruntung, tapi jangan khawatir! Teruslah bermain dan raih hadiah-hadiah fantastis yang menanti Anda. Terima kasih telah memilih <span id="lose-platform"></span> untuk hiburan Anda.',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'guide_text',
                'value' => '<p>Cara mendapatkan kupon?</p><ul><li>Blaablaablaa</li><li>Blaablaablaa</li><li>Blaablaablaa</li></ul><p>Cara mendapatkan kupon?</p><ul><li>Blaablaablaa</li><li>Blaablaablaa</li><li>Blaablaablaa</li></ul>',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'set_for' => 'wellcome_text',
                'value' => '<p>Selamat datang di Roullete keberuntungan</p>',
                'updated_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        Setting::insert($postCat);
    }
}
