<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_get_logs;
            CREATE PROCEDURE sp_get_logs()
            BEGIN
                SELECT username, profil, action, libelle, color, avatar, created_at
                FROM logs
                ORDER BY created_at DESC;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_logs");
    }
};