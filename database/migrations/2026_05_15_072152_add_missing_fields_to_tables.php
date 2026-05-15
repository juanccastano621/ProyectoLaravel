<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            if (!Schema::hasColumn('spaces', 'type')) $table->string('type')->nullable();
            if (!Schema::hasColumn('spaces', 'price_per_hour')) $table->decimal('price_per_hour', 8, 2)->default(0);
        });

        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'slug')) $table->string('slug')->unique()->nullable();
            if (!Schema::hasColumn('reservations', 'notes')) $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn(['type', 'price_per_hour']);
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['slug', 'notes']);
        });
    }
};