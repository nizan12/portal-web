<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('t_link')) {
            return;
        }

        Schema::table('t_link', function (Blueprint $table) {
            if (! Schema::hasColumn('t_link', 'status_checked_at')) {
                $table->timestamp('status_checked_at')->nullable()->after('status');
            }

            if (! Schema::hasColumn('t_link', 'status_http_code')) {
                $table->unsignedSmallInteger('status_http_code')->nullable()->after('status_checked_at');
            }

            if (! Schema::hasColumn('t_link', 'status_response_time_ms')) {
                $table->unsignedInteger('status_response_time_ms')->nullable()->after('status_http_code');
            }

            if (! Schema::hasColumn('t_link', 'status_summary')) {
                $table->string('status_summary', 255)->nullable()->after('status_response_time_ms');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('t_link')) {
            return;
        }

        Schema::table('t_link', function (Blueprint $table) {
            $columns = [
                'status_checked_at',
                'status_http_code',
                'status_response_time_ms',
                'status_summary',
            ];

            $existingColumns = array_values(array_filter($columns, fn (string $column) => Schema::hasColumn('t_link', $column)));

            if ($existingColumns !== []) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
