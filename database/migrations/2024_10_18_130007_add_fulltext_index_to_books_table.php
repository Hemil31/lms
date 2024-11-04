<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement('
            ALTER TABLE books ADD COLUMN search_vector tsvector GENERATED ALWAYS AS (
                to_tsvector(\'english\', ISBN::text) || \' \' ||
                to_tsvector(\'english\', status::text) || \' \' ||
                to_tsvector(\'english\', title) || \' \' ||
                to_tsvector(\'english\', author)
            ) STORED;
        ');
        DB::statement('CREATE INDEX books_search_vector_idx ON books USING gin(search_vector);');    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS books_search_vector_idx;');
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('search_vector');
        });
    }
};
