<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('passport_scope_grants')) {
            return;
        }

        Schema::create('passport_scope_grants', static function (Blueprint $table) {
            $table->id();
            /**
             * polymorphic relation to the token owner (User, ServiceAccount, etc.)
             */
            $table->string('tokenable_id');
            $table->string('tokenable_type');


            $table->foreignId('resource_id')
                ->constrained('passport_scope_resources')
                ->cascadeOnDelete();

            $table->foreignId('action_id')
                ->constrained('passport_scope_actions')
                ->cascadeOnDelete();

            $table->timestamps();

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

    public function down(): void
    {
        Schema::dropIfExists('passport_scope_grants');
    }
};
