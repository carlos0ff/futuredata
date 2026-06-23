<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('bot_sessions') && ! Schema::hasColumn('bot_sessions', 'channel')) {
            Schema::table('bot_sessions', function (Blueprint $table) {
                $table->string('channel', 20)->default('whatsapp')->after('ordem_id');
                $table->unique(['phone', 'channel']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bot_sessions', 'channel')) {
            Schema::table('bot_sessions', function (Blueprint $table) {
                $table->dropUnique(['phone', 'channel']);
                $table->dropColumn('channel');
            });
        }
    }
};
