<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('author');
        $table->string('isbn')->unique();
        $table->string('category')->nullable();
        $table->string('publisher')->nullable();
        $table->integer('published_year')->nullable();
        $table->integer('total_copies');
        $table->integer('available_copies');
        $table->text('description')->nullable();
        $table->string('cover_image')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
    Schema::table('books', function (Blueprint $table) {
        if (Schema::hasColumn('books', 'isbn')) {
            $table->dropColumn('isbn');
        }
        if (Schema::hasColumn('books', 'published_year')) {
            $table->dropColumn('published_year');
        }
        if (Schema::hasColumn('books', 'total_copies')) {
            $table->dropColumn('total_copies');
        }
        if (Schema::hasColumn('books', 'available_copies')) {
            $table->dropColumn('available_copies');
        }
        if (Schema::hasColumn('books', 'description')) {
            $table->dropColumn('description');
        }
    });
}
};