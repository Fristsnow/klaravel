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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('edited_url')->nullable();
            $table->string('original_url');
            $table->string('framed_url')->nullable();
            $table->string('frame_name')->nullable();
            $table->string('size')->nullable();
            $table->double('print_price',8,2)->nullable();
            $table->double('frame_price',8,2)->nullable();
            $table->string('status');
            $table->integer('frame_id')->nullable();
            $table->integer('user_id');
            $table->integer('size_id');
            $table->integer('order_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
