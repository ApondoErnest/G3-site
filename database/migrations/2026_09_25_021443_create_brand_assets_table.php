<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_assets', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 64);
            $table->timestamps();

            $table->unique('code', 'uq_brand_assets_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_assets');
    }
};
