<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Audit Logs Table (t_audit_log)
        if (!Schema::hasTable('t_audit_log')) {
            Schema::create('t_audit_log', function (Blueprint $table) {
                $table->id();
                $table->string('table_name', 50);
                $table->string('action', 20);
                $table->integer('record_id');
                $table->text('old_value')->nullable();
                $table->text('new_value')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 2. Drop existing triggers/procedures/functions if any
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_link_update");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_dashboard_statistics");
        DB::unprepared("DROP FUNCTION IF EXISTS sf_get_category_link_count");

        // 3. Create Trigger: AFTER UPDATE on t_link to log changes in t_audit_log
        DB::unprepared("
            CREATE TRIGGER trg_after_link_update
            AFTER UPDATE ON t_link
            FOR EACH ROW
            BEGIN
                IF OLD.status <> NEW.status OR OLD.url <> NEW.url THEN
                    INSERT INTO t_audit_log (table_name, action, record_id, old_value, new_value)
                    VALUES (
                        't_link',
                        'UPDATE',
                        OLD.id_link,
                        CONCAT('status: ', OLD.status, ', url: ', OLD.url),
                        CONCAT('status: ', NEW.status, ', url: ', NEW.url)
                    );
                END IF;
            END
        ");

        // 4. Create Stored Procedure: sp_get_dashboard_statistics
        // Calculates total links, active links, avg response time, and most active category using a subquery and aggregate.
        DB::unprepared("
            CREATE PROCEDURE sp_get_dashboard_statistics(
                OUT out_total_links INT,
                OUT out_active_links INT,
                OUT out_avg_response_time INT,
                OUT out_most_active_category VARCHAR(100)
            )
            BEGIN
                -- Aggregate query
                SELECT 
                    COUNT(*),
                    SUM(IF(status = 'aktif', 1, 0)),
                    AVG(IF(status_response_time_ms IS NOT NULL, status_response_time_ms, 0))
                INTO 
                    out_total_links,
                    out_active_links,
                    out_avg_response_time
                FROM t_link;

                -- Subquery with aggregates to find the category with the most links
                BEGIN
                    DECLARE max_cat_id INT;
                    SELECT id_kategori INTO max_cat_id
                    FROM t_terdaftar 
                    GROUP BY id_kategori 
                    ORDER BY COUNT(id_link) DESC 
                    LIMIT 1;

                    IF max_cat_id IS NOT NULL THEN
                        SELECT nama_kategori INTO out_most_active_category
                        FROM t_kategori
                        WHERE id_kategori = max_cat_id
                        LIMIT 1;
                    ELSE
                        SET out_most_active_category = 'Tidak Ada';
                    END IF;
                END;
            END
        ");

        // 5. Create Stored Function: sf_get_category_link_count
        DB::unprepared("
            CREATE FUNCTION sf_get_category_link_count(cat_id INT)
            RETURNS INT
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE link_count INT;
                SELECT COUNT(*) INTO link_count
                FROM t_terdaftar
                WHERE id_kategori = cat_id;
                RETURN link_count;
            END
        ");

        // 6. Add CHECK Constraint to t_link status column (MySQL 8.0 support check constraints natively)
        // Ensure status can only be 'aktif' or 'bermasalah'
        try {
            DB::unprepared("
                ALTER TABLE t_link
                ADD CONSTRAINT chk_link_status
                CHECK (status IN ('aktif', 'bermasalah'))
            ");
        } catch (\Throwable $e) {
            // Log or fallback if some older MySQL version doesn't support CHECK constraint natively
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_after_link_update");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_dashboard_statistics");
        DB::unprepared("DROP FUNCTION IF EXISTS sf_get_category_link_count");

        try {
            DB::unprepared("ALTER TABLE t_link DROP CONSTRAINT IF EXISTS chk_link_status");
        } catch (\Throwable $e) {}

        Schema::dropIfExists('t_audit_log');
    }
};
