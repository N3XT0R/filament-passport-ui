<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('passport_scope_actions')) {
            return;
        }

        Schema::create('passport_scope_actions', static function (Blueprint $table) {
            $table->id();
            /**
             * Limit the action to a specific resource (e.g., "users", "orders", "invoices").
             */
            $table->foreignId('resource_id')->nullable()
                ->constrained('passport_scope_resources')
                ->cascadeOnDelete();
            // technical action name (e.g. "read", "write", "export")
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_scope_actions');
    }
};
