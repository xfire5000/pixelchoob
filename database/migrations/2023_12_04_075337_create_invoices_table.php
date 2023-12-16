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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_case_id')->constrained('list_cases')->onDelete('cascade')->onUpdate('cascade');
            $table->text('description')->nullable();
            $table->integer('sumPVC');
            $table->integer('sumCutting');
            $table->integer('countParts');
            $table->integer('sumChamfers');
            $table->integer('sumGroove');
            $table->string('pvc')->default(json_encode([
                'size_1' => 0,
                'size_2' => 0,
            ]));
            $table->integer('groove');
            $table->integer('chamfer');
            $table->integer('cutting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
