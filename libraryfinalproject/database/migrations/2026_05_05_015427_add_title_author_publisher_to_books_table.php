<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('author')->after('title');
            $table->string('publisher')->nullable()->after('published_year');
            $table->string('cover_image')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['title', 'author', 'publisher', 'cover_image']);
        });
    }
};