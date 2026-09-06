<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_exceptions', function (Blueprint $table): void {
            $table->id();
            $table->boolean('applies_to_all_centres');
            $table->unsignedBigInteger('centre_id')->nullable();
            $table->date('starts_on');
            $table->date('ends_on')->nullable();
            $table->boolean('is_open');
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->json('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('centre_id', 'fk_schedule_exceptions_centres')
                ->references('id')->on('centres')
                ->restrictOnDelete();
            $table->foreign('created_by', 'fk_schedule_exceptions_users')
                ->references('id')->on('users')
                ->nullOnDelete();
            $table->index(['centre_id', 'starts_on', 'ends_on'], 'idx_schedule_exceptions_centre_dates');
            $table->index(['applies_to_all_centres', 'starts_on', 'ends_on'], 'idx_schedule_exceptions_global_dates');
        });

        DB::statement(
            'ALTER TABLE schedule_exceptions ADD CONSTRAINT chk_se_scope '
            .'CHECK ((applies_to_all_centres = 1 AND centre_id IS NULL) OR (applies_to_all_centres = 0 AND centre_id IS NOT NULL))',
        );

        DB::statement(
            'ALTER TABLE schedule_exceptions ADD CONSTRAINT chk_se_dates '
            .'CHECK (ends_on IS NULL OR ends_on >= starts_on)',
        );

        DB::statement(
            'ALTER TABLE schedule_exceptions ADD CONSTRAINT chk_se_open_times '
            .'CHECK ((is_open = 0) OR (opens_at IS NOT NULL AND closes_at IS NOT NULL))',
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_exceptions');
    }
};
