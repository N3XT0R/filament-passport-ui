<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('passport_scope_resources')) {
            Schema::create('passport_scope_resources', static function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('passport_scope_actions')) {
            Schema::create('passport_scope_actions', static function (Blueprint $table) {
                $table->id();
                $table->foreignId('resource_id')
                    ->nullable()
                    ->constrained('passport_scope_resources')
                    ->cascadeOnDelete();
                $table->string('name')->unique();
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('passport_scope_grants')) {
            Schema::create('passport_scope_grants', static function (Blueprint $table) {
                $table->id();
                $table->string('tokenable_id');
                $table->string('tokenable_type');
                $table->foreignId('context_client_id')
                    ->nullable()
                    ->constrained('oauth_clients')
                    ->cascadeOnDelete();
                $table->foreignId('resource_id')
                    ->constrained('passport_scope_resources')
                    ->cascadeOnDelete();
                $table->foreignId('action_id')
                    ->constrained('passport_scope_actions')
                    ->cascadeOnDelete();
                $table->timestamps();

                $table->index(['context_client_id'], 'passport_scope_grants_context_client_idx');

                $table->unique(
                    [
                        'tokenable_type',
                        'tokenable_id',
                        'resource_id',
                        'action_id',
                    ],
                    'passport_scope_grant_unique'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_scope_grants');
        Schema::dropIfExists('passport_scope_actions');
        Schema::dropIfExists('passport_scope_resources');
    }
};
