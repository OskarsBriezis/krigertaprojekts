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
        Schema::create('mouse_movements', function (Blueprint $table) {
            $table->id();
            $table->unique(['user_id', 'name']);
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->json('movements');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mouse_movements', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
        });
    }
};
