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
        Schema::create('user_manager', function (Blueprint $table) {
            // $table->id();
            // $table->timestamps();
            $table->string('user_managers_type')->index();
            $table->integer('user_managers_id');
            $table->bigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_manager');
    }
};
