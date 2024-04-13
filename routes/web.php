<?php

use App\Http\Controllers\Admin\ErrorLogsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpinConfiguration;
use App\Http\Controllers\Admin\VoucherGenerate;
use App\Http\Controllers\Admin\UserManagement;
use App\Http\Controllers\Front\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('guest')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::post('/verify/voucher', [LandingController::class, 'verify'])->name('landing.verify');
    Route::post('/spin-reward', [LandingController::class, 'spin'])->name('landing.spin');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('system')->middleware('auth')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::post('/update', [SettingController::class, 'update'])->name('setting.update');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/management', [UserManagement::class, 'index'])->name('user.management');
        Route::delete('/{user}', [UserManagement::class, 'destroy'])->name('users.destroy');
        Route::post('/update', [UserManagement::class, 'update'])->name('users.update');
    });

    Route::prefix('spin-config')->group(function () {
        Route::get('/', [SpinConfiguration::class, 'index'])->name('spinconfig.index');
        Route::post('/update', [SpinConfiguration::class, 'updateCat'])->name('spinconfig.update');
        Route::post('/prize/update', [SpinConfiguration::class, 'updatePrize'])->name('prize.update');
    });

    Route::prefix('voucher')->group(function () {
        Route::get('/', [VoucherGenerate::class, 'index'])->name('voucher.index');
        Route::post('/store', [VoucherGenerate::class, 'store'])->name('voucher.store');
        Route::post('/update', [VoucherGenerate::class, 'update'])->name('voucher.update');
        Route::post('/search', [VoucherGenerate::class, 'search'])->name('voucher.search');
        Route::post('/search/bydate', [VoucherGenerate::class, 'search_bydate'])->name('voucher.search.bydate');
        Route::delete('/{voucher}', [VoucherGenerate::class, 'destroy'])->name('voucher.destroy');
        Route::post('/store/platform', [VoucherGenerate::class, 'store_platform'])->name('voucher.store.platform');
        Route::post('/update/platform', [VoucherGenerate::class, 'update_platform'])->name('voucher.update.platform');
        Route::delete('/platform/{platform}', [VoucherGenerate::class, 'destroy_platform'])->name('voucher.destroy.platform');
    });

    Route::prefix('reward')->group(function () {
        Route::get('/', [RewardController::class, 'index'])->name('reward.index');
        Route::post('/delivery', [RewardController::class, 'delivery'])->name('reward.delivery');
        Route::post('/cancel', [RewardController::class, 'cancel_delivery'])->name('cancel.delivery');
        Route::post('/search', [RewardController::class, 'search'])->name('reward.search');
        Route::post('/search/bydate', [RewardController::class, 'search_bydate'])->name('reward.search.bydate');
    });

    Route::prefix('error-logs')->group(function () {
        Route::get('/', [ErrorLogsController::class, 'index'])->name('errorlogs.index');
    });
});

require __DIR__ . '/auth.php';
