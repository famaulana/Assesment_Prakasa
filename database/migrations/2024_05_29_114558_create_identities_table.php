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
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->tinyText('first_name');
            $table->tinyText('middle_name');
            $table->tinyText('last_name');
            $table->tinyText('phone');
            $table->integer('gender')->comment('0: male, 1: female');
            $table->longText('profile_img')->comment('storage path');
            $table->longText('address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity');
    }
};
