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
        Schema::create('MH_Questions', function (Blueprint $table) {
            $table->id();
            $table->string('prompt', 300); // the question text
            $table->enum('type', ['scale','single','multi','text'])->default('text'); // type of question
            $table->boolean('is_active')->default(true); // active or not
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mh_questions');
    }
};
