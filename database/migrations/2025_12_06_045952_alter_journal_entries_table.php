<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            // Make quote_id nullable
            $table->unsignedBigInteger('quote_id')->default(1)->change();

            // Add soft deletes
            $table->softDeletes();

            // Add sharing flag
            $table->boolean('shared_with_consultant')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('quote_id')->default(1)->change();
            $table->dropSoftDeletes();
            $table->dropColumn('shared_with_consultant');
        });
    }
};
