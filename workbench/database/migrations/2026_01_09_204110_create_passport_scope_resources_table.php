<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('passport_scope_resources')) {
            return;
        }
        Schema::create('passport_scope_resources', static function (Blueprint $table) {
            $table->id();
            // resource name (e.g., account, billing, orders)
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passport_scope_resources');
    }
};
