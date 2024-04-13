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
        Schema::create('user_voucher', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('code_voucher');
            $table->boolean('is_available')->default(true);
            $table->integer('spin_balance')->nullable();
            $table->integer('spin_used')->nullable();
            $table->string('set_prob')->nullable();
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('issued_by');
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_voucher');
    }
};
