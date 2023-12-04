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
        Schema::create('list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_case_id')->constrained('list_cases')->onDelete('cascade')->onUpdate('cascade');
            $table->text('description')->nullable();
            $table->string('chamfer')->nullable();
            $table->string('gazor_hinge')->nullable();
            $table->string('groove')->nullable();
            $table->string('pvc')->nullable();
            $table->tinyInteger('qty')->default(1);
            $table->string('dimensions');
            $table->integer('sortable')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_items');
    }
};
