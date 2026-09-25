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
            'required_documents' => [
                'chk_rd_target' => 'vehicle_category_id IS NOT NULL OR service_id IS NOT NULL',
            ],
        ], function (): void {
            Schema::create('vehicle_categories', function (Blueprint $table): void {
                $table->id();
                $table->string('code', 64);
                $table->json('label');
                $table->json('examples')->nullable();
                $table->json('description')->nullable();
                $table->unsignedSmallInteger('sort_order');
                $table->boolean('is_published');
                $table->timestamps();

                $table->unique('code', 'uq_vehicle_categories_code');
                $table->index(['is_published', 'sort_order'], 'idx_vehicle_categories_published_sort');
            });

            Schema::create('services', function (Blueprint $table): void {
                $table->id();
                $table->string('code', 64);
                $table->json('title');
                $table->json('summary')->nullable();
                $table->json('body')->nullable();
                $table->string('icon', 64)->nullable();
                $table->unsignedSmallInteger('sort_order');
                $table->boolean('is_published');
                $table->timestamps();

                $table->unique('code', 'uq_services_code');
                $table->index(['is_published', 'sort_order'], 'idx_services_published_sort');
            });

            Schema::create('centre_service', function (Blueprint $table): void {
                $table->unsignedBigInteger('centre_id');
                $table->unsignedBigInteger('service_id');

                $table->primary(['centre_id', 'service_id']);

                $table->foreign('centre_id', 'fk_centre_service_centres')
                    ->references('id')->on('centres')
                    ->cascadeOnDelete();
                $table->foreign('service_id', 'fk_centre_service_services')
                    ->references('id')->on('services')
                    ->cascadeOnDelete();
            });

            Schema::create('service_vehicle_category', function (Blueprint $table): void {
                $table->unsignedBigInteger('service_id');
                $table->unsignedBigInteger('vehicle_category_id');

                $table->primary(['service_id', 'vehicle_category_id']);

                $table->foreign('service_id', 'fk_service_vehicle_category_services')
                    ->references('id')->on('services')
                    ->cascadeOnDelete();
                $table->foreign('vehicle_category_id', 'fk_service_vehicle_category_vehicle_categories')
                    ->references('id')->on('vehicle_categories')
                    ->cascadeOnDelete();
            });

            Schema::create('required_documents', function (Blueprint $table): void {
                $table->id();
                $table->json('label');
                $table->unsignedBigInteger('vehicle_category_id')->nullable();
                $table->unsignedBigInteger('service_id')->nullable();
                $table->unsignedSmallInteger('sort_order');
                $table->timestamps();

                $table->foreign('vehicle_category_id', 'fk_required_documents_vehicle_categories')
                    ->references('id')->on('vehicle_categories')
                    ->cascadeOnDelete();
                $table->foreign('service_id', 'fk_required_documents_services')
                    ->references('id')->on('services')
                    ->cascadeOnDelete();
                $table->index(['vehicle_category_id', 'sort_order'], 'idx_required_documents_category');
                $table->index(['service_id', 'sort_order'], 'idx_required_documents_service');
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('required_documents');
        Schema::dropIfExists('service_vehicle_category');
        Schema::dropIfExists('centre_service');
        Schema::dropIfExists('services');
        Schema::dropIfExists('vehicle_categories');
    }
};
