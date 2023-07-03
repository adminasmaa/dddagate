<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->string('address')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('code')->unique()->nullable();
            $table->string('car_type')->nullable();
            $table->boolean('status')->default(1);

            $table->foreignId('zone_id')->constrained()->nullable();

            $table->string('image_profile')->default('avatar.png');
            $table->string('image_idt_front')->nullable();
            $table->string('image_idt_back')->nullable();

            $table->string('ip_address')->nullable();
            $table->dateTime('login_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
