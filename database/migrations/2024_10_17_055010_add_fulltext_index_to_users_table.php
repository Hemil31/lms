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
        DB::statement('ALTER TABLE users ADD COLUMN search_vector tsvector GENERATED ALWAYS AS (
            to_tsvector(\'english\', role_id::text) || \' \' ||
            to_tsvector(\'english\', name) || \' \' ||
            to_tsvector(\'simple\', email)
        ) STORED;');

        DB::statement('CREATE INDEX users_search_vector_idx ON users USING gin(search_vector);');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP INDEX IF EXISTS users_search_vector_idx;");
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('search_vector');
        });
    }
};
