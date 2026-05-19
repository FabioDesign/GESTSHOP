<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS valid_cashes;
            CREATE TRIGGER valid_cashes
            AFTER UPDATE ON cashes
            FOR EACH ROW
            BEGIN

                DECLARE old_year INT;
                DECLARE old_month INT;
                DECLARE new_year INT;
                DECLARE new_month INT;

                SET old_year = YEAR(OLD.date_at);
                SET old_month = MONTH(OLD.date_at);

                SET new_year = YEAR(NEW.date_at);
                SET new_month = MONTH(NEW.date_at);

                -- RETRAIT ANCIENNE VALEUR
                IF OLD.status = 2 THEN
                    UPDATE monthly_stats
                    SET cash_in = cash_in - OLD.cash_in,
                        cash_out = cash_out - OLD.cash_out
                    WHERE years = old_year AND months = old_month;

                    UPDATE annual_stats
                    SET cash_in = cash_in - OLD.cash_in,
                        cash_out = cash_out - OLD.cash_out
                    WHERE years = old_year;
                END IF;

                IF NEW.status = 2 THEN
                    -- UPDATE STATS MONTHS
                    INSERT INTO monthly_stats (years, months, cash_in, cash_out, created_at)
                    VALUES (new_year, new_month, NEW.cash_in, NEW.cash_out, NOW())
                    ON DUPLICATE KEY UPDATE
                        cash_in = cash_in + NEW.cash_in,
                        cash_out = cash_out + NEW.cash_out;

                    -- UPDATE STATS YEARS
                    INSERT INTO annual_stats (years, cash_in, cash_out, created_at)
                    VALUES (new_year, NEW.cash_in, NEW.cash_out, NOW())
                    ON DUPLICATE KEY UPDATE
                        cash_in = cash_in + NEW.cash_in,
                        cash_out = cash_out + NEW.cash_out;
                        
                    -- UPDATE STOCK PRODUCT
                    UPDATE products p
                    JOIN transactions t ON t.product_id = p.id
                    SET p.stock = p.stock + 
                        CASE 
                            WHEN t.category_id = 1 THEN t.quantity
                            WHEN t.category_id = 2 THEN -t.quantity
                            ELSE 0
                        END
                    WHERE t.cash_id = NEW.id;
                END IF;

            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS cashes");
    }
};