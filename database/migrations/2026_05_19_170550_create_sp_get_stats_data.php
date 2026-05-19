<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_get_stats_data;
            CREATE sp_get_stats_data(
            )
BEGIN
                SELECT years, cash_in, cash_out
                FROM annual_stats
				WHERE id=1;
                SELECT username, profil, action, libelle, color, avatar, created_at
                FROM logs
                ORDER BY created_at DESC;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_stats_data");
    }
};