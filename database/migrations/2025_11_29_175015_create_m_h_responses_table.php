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
        Schema::create('mh_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // link to user
            $table->foreignId('question_id')->constrained('mh_questions')->onDelete('cascade'); // link to question
            $table->text('response_text')->nullable(); // text or single choice
            $table->integer('response_number')->nullable(); // for scale answers
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mh_responses');
    }
};
