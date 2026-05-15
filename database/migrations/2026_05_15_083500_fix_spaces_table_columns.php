<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            if (!Schema::hasColumn('spaces', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('spaces', 'type')) {
                $table->string('type')->nullable();
            }
            if (!Schema::hasColumn('spaces', 'price_per_hour')) {
                $table->decimal('price_per_hour', 8, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('spaces', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'type', 'price_per_hour']);
        });
    }
};