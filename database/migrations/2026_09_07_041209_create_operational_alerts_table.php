<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_alerts', function (Blueprint $table): void {
            $table->id();
            $table->string('severity', 32);
            $table->json('message');
            $table->unsignedBigInteger('centre_id')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('centre_id', 'fk_operational_alerts_centres')
                ->references('id')->on('centres')
                ->nullOnDelete();
            $table->index(['is_active', 'starts_at', 'expires_at'], 'idx_operational_alerts_active_window');
            $table->index('centre_id', 'idx_operational_alerts_centre');
        });

        DB::statement(
            'ALTER TABLE operational_alerts ADD CONSTRAINT chk_oa_severity '
            ."CHECK (severity IN ('info', 'warning', 'critical'))",
        );

        DB::statement(
            'ALTER TABLE operational_alerts ADD CONSTRAINT chk_oa_dates '
            .'CHECK (expires_at IS NULL OR expires_at >= starts_at)',
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_alerts');
    }
};
