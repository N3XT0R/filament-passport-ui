<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('passport_scope_grants', static function (Blueprint $table) {
            $table->foreignId('context_client_id')
                ->nullable()
                ->after('tokenable_type')
                ->constrained('oauth_clients')
                ->cascadeOnDelete();

            $table->index(
                ['context_client_id'],
                'passport_scope_grants_context_client_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::table('passport_scope_grants', static function (Blueprint $table) {
            $table->dropForeign(['context_client_id']);
            $table->dropColumn('context_client_id');
        });
    }
};
