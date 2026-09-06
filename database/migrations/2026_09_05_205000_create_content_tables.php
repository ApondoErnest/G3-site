<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_blocks', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 128);
            $table->string('page', 64);
            $table->unsignedTinyInteger('schema_version');
            $table->json('content');
            $table->json('locale_status');
            $table->boolean('is_published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique('key', 'uq_content_blocks_key');
            $table->index(['page', 'is_published'], 'idx_content_blocks_page_published');
        });

        Schema::create('faq_entries', function (Blueprint $table): void {
            $table->id();
            $table->string('category_code', 64);
            $table->json('question');
            $table->json('answer');
            $table->unsignedSmallInteger('sort_order');
            $table->boolean('is_published');
            $table->timestamps();

            $table->index(['category_code', 'sort_order', 'is_published'], 'idx_faq_entries_category_sort');
        });

        Schema::create('team_members', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->json('role_title');
            $table->json('bio')->nullable();
            $table->boolean('display_publicly');
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();

            $table->index(['display_publicly', 'sort_order'], 'idx_team_members_public_sort');
        });

        Schema::create('road_safety_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('anchor', 64);
            $table->json('title');
            $table->json('body');
            $table->unsignedSmallInteger('sort_order');
            $table->boolean('is_published');
            $table->timestamps();

            $table->unique('anchor', 'uq_road_safety_sections_anchor');
            $table->index(['is_published', 'sort_order'], 'idx_road_safety_sections_published_sort');
        });

        Schema::create('equipment', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 64);
            $table->json('label');
            $table->unsignedSmallInteger('sort_order');
            $table->timestamps();

            $table->unique('code', 'uq_equipment_code');
        });

        Schema::create('centre_equipment', function (Blueprint $table): void {
            $table->unsignedBigInteger('centre_id');
            $table->unsignedBigInteger('equipment_id');

            $table->primary(['centre_id', 'equipment_id']);

            $table->foreign('centre_id', 'fk_centre_equipment_centres')
                ->references('id')->on('centres')
                ->cascadeOnDelete();
            $table->foreign('equipment_id', 'fk_centre_equipment_equipment')
                ->references('id')->on('equipment')
                ->cascadeOnDelete();
        });

        Schema::create('page_seo', function (Blueprint $table): void {
            $table->string('page', 64);
            $table->json('seo_title');
            $table->json('seo_description');
            $table->timestamp('updated_at');

            $table->primary('page');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo');
        Schema::dropIfExists('centre_equipment');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('road_safety_sections');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('faq_entries');
        Schema::dropIfExists('content_blocks');
    }
};
