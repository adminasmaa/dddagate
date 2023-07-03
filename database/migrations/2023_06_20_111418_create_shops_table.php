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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en');

            $table->string('fullname');
            $table->string('phone');
            $table->string('status')->default(1);
            $table->string('address')->nullable();

            $table->foreignId('zone_id')->constrained();

            $table->double('longitude', 8, 2);
            $table->double('latitude', 8, 2);
            $table->double('distance', 8, 2)->nullable();

            $table->string('image_profile')->default('avatar.png');
            $table->string('image_idt_front')->nullable();
            $table->string('image_idt_back')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
