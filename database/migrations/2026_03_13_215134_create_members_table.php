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
        Schema::create('members', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');

    $table->string('name');
    $table->string('mobile');
    $table->string('email')->nullable();
    $table->string('gender')->nullable();
    $table->integer('age')->nullable();
    $table->text('address')->nullable();

    $table->string('plan')->nullable();
    $table->string('batch')->nullable();
    $table->string('trainer')->nullable();

    $table->integer('total_fees')->nullable();
    $table->integer('paid_amount')->nullable();
    $table->integer('pending_amount')->nullable();
    $table->string('payment_mode')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
