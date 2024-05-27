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
        Schema::create('barbecues_friendships', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('barbecue_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('guest_id')->constrained('users');
            $table->boolean('is_admin')->default(false);
            $table->boolean('accepted')->default(false);
            $table->boolean('invitation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbecues_friendships');
    }
};
