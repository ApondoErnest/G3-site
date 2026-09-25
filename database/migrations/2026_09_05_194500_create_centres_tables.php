<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        require_once __DIR__.'/support/check_constraints.php';

        g3_check_constraints([
            'centre_weekly_hours' => [
                'chk_cwh_open_times' => '(is_open = 0) OR (opens_at IS NOT NULL AND closes_at IS NOT NULL AND opens_at < closes_at)',
            ],
        ], function (): void {
            Schema::create('centres', function (Blueprint $table): void {
                $table->id();
                $table->string('code', 64);
                $table->json('name');
                $table->json('address');
                $table->json('landmark');
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->string('email');
                $table->string('postal_code', 32)->nullable();
                $table->string('status', 32);
                $table->unsignedSmallInteger('sort_order');
                $table->boolean('holiday_default_open');
                $table->json('seo_title')->nullable();
                $table->json('seo_description')->nullable();
                $table->timestamps();

                $table->unique('code', 'uq_centres_code');
                $table->index(['status', 'sort_order'], 'idx_centres_status_sort');
            });

            Schema::create('centre_phones', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('centre_id');
                $table->string('label', 64);
                $table->string('e164', 20);
                $table->boolean('is_whatsapp')->default(false);
                $table->unsignedTinyInteger('sort_order');
                $table->timestamps();

                $table->foreign('centre_id', 'fk_centre_phones_centres')
                    ->references('id')->on('centres')
                    ->cascadeOnDelete();
                $table->index(['centre_id', 'sort_order'], 'idx_centre_phones_centre_sort');
            });

            Schema::create('centre_weekly_hours', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('centre_id');
                $table->unsignedTinyInteger('weekday');
                $table->boolean('is_open');
                $table->time('opens_at')->nullable();
                $table->time('closes_at')->nullable();
                $table->timestamps();

                $table->foreign('centre_id', 'fk_centre_weekly_hours_centres')
                    ->references('id')->on('centres')
                    ->cascadeOnDelete();
                $table->unique(['centre_id', 'weekday'], 'uq_centre_weekly_hours_centre_weekday');
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centre_weekly_hours');
        Schema::dropIfExists('centre_phones');
        Schema::dropIfExists('centres');
    }
};
