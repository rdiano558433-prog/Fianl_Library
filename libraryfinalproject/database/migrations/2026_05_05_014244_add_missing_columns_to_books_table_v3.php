<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'isbn')) {
                $table->string('isbn')->unique()->nullable();
            }
            if (!Schema::hasColumn('books', 'published_year')) {
                $table->integer('published_year')->nullable();
            }
            if (!Schema::hasColumn('books', 'total_copies')) {
                $table->integer('total_copies')->default(0);
            }
            if (!Schema::hasColumn('books', 'available_copies')) {
                $table->integer('available_copies')->default(0);
            }
            if (!Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['isbn', 'published_year', 'total_copies', 'available_copies', 'description']);
        });
    }
};