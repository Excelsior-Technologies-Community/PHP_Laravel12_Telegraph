<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('telegraph_bot_commands')) {
            Schema::create('telegraph_bot_commands', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('telegraph_bot_id');
                $table->string('command');
                $table->string('description');
                $table->timestamps();

                $table->foreign('telegraph_bot_id')->references('id')->on('telegraph_bots')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('telegraph_auto_replies')) {
            Schema::create('telegraph_auto_replies', function (Blueprint $table) {
                $table->id();
                $table->string('keyword');
                $table->text('reply_text');
                $table->string('match_type')->default('exact');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'telegraph_chat_id')) {
                $table->unsignedBigInteger('telegraph_chat_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('messages', 'direction')) {
                $table->string('direction')->default('inbound')->after('message');
            }
            if (!Schema::hasColumn('messages', 'file_type')) {
                $table->string('file_type')->nullable()->after('direction');
            }
            if (!Schema::hasColumn('messages', 'file_path')) {
                $table->string('file_path')->nullable()->after('file_type');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegraph_bot_commands');
        Schema::dropIfExists('telegraph_auto_replies');
        
        Schema::table('messages', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('messages', 'telegraph_chat_id')) {
                $columnsToDrop[] = 'telegraph_chat_id';
            }
            if (Schema::hasColumn('messages', 'direction')) {
                $columnsToDrop[] = 'direction';
            }
            if (Schema::hasColumn('messages', 'file_type')) {
                $columnsToDrop[] = 'file_type';
            }
            if (Schema::hasColumn('messages', 'file_path')) {
                $columnsToDrop[] = 'file_path';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};