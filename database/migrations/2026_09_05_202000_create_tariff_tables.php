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
            'tariff_versions' => [
                'chk_tv_dates' => 'effective_until IS NULL OR effective_until >= effective_from',
            ],
            'tariff_items' => [
                'chk_ti_amount' => 'amount_xaf > 0',
            ],
        ], function (): void {
            Schema::create('tariff_versions', function (Blueprint $table): void {
                $table->id();
                $table->string('label', 64);
                $table->string('status', 32);
                $table->date('effective_from');
                $table->date('effective_until')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->unsignedBigInteger('published_by')->nullable();
                $table->timestamps();

                $table->unique('label', 'uq_tariff_versions_label');
                $table->index(['status', 'effective_from', 'effective_until'], 'idx_tariff_versions_status_effective');
                $table->index('status', 'idx_tariff_versions_status');
                $table->foreign('reviewed_by', 'fk_tariff_versions_reviewed_by')
                    ->references('id')->on('users')
                    ->nullOnDelete();
                $table->foreign('published_by', 'fk_tariff_versions_published_by')
                    ->references('id')->on('users')
                    ->nullOnDelete();
            });

            Schema::create('tariff_items', function (Blueprint $table): void {
                $table->id();
                $table->unsignedBigInteger('tariff_version_id');
                $table->unsignedBigInteger('vehicle_category_id');
                $table->unsignedBigInteger('service_id')->nullable();
                $table->unsignedInteger('amount_xaf');
                $table->json('validity_notes')->nullable();
                $table->unsignedSmallInteger('sort_order');
                $table->timestamps();

                $table->foreign('tariff_version_id', 'fk_tariff_items_tariff_versions')
                    ->references('id')->on('tariff_versions')
                    ->cascadeOnDelete();
                $table->foreign('vehicle_category_id', 'fk_tariff_items_vehicle_categories')
                    ->references('id')->on('vehicle_categories')
                    ->restrictOnDelete();
                $table->foreign('service_id', 'fk_tariff_items_services')
                    ->references('id')->on('services')
                    ->restrictOnDelete();
                $table->index(['tariff_version_id', 'sort_order'], 'idx_tariff_items_version_sort');
                $table->index('vehicle_category_id', 'idx_tariff_items_category');
                $table->index('service_id', 'idx_tariff_items_service');
            });

            Schema::create('tariff_item_centre', function (Blueprint $table): void {
                $table->unsignedBigInteger('tariff_item_id');
                $table->unsignedBigInteger('centre_id');

                $table->primary(['tariff_item_id', 'centre_id']);

                $table->foreign('tariff_item_id', 'fk_tariff_item_centre_tariff_items')
                    ->references('id')->on('tariff_items')
                    ->cascadeOnDelete();
                $table->foreign('centre_id', 'fk_tariff_item_centre_centres')
                    ->references('id')->on('centres')
                    ->restrictOnDelete();
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariff_item_centre');
        Schema::dropIfExists('tariff_items');
        Schema::dropIfExists('tariff_versions');
    }
};
