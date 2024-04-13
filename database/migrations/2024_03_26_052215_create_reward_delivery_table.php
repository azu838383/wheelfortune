<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reward_delivery', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->unsignedBigInteger('prize_id');
            $table->foreign('prize_id')->references('id')->on('prize_item')->onDelete('cascade');
            $table->string('prize_title');
            $table->integer('prize_value');
            $table->string('prize_cat');
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('voucher_id');
            $table->foreign('voucher_id')->references('id')->on('user_voucher')->onDelete('cascade');
            $table->boolean('delivery_status')->default(false);
            $table->unsignedBigInteger('proced_by')->nullable();
            $table->foreign('proced_by')->references('id')->on('users')->onDelete('cascade');
            $table->integer('count_changes')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_delivery');
    }
};
