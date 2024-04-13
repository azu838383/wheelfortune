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
        Schema::create('prize_item', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('value');
            $table->string('image')->nullable();
            $table->decimal('first_prob');
            $table->decimal('second_prob');
            $table->unsignedBigInteger('cat_id');
            $table->foreign('cat_id')->references('id')->on('cat_prize')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prize_item');
    }
};
