<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('centres', function (Blueprint $table): void {
            $table->string('secondary_email')->nullable()->after('email');
        });

        DB::table('centres')
            ->where('email', 'g3sarl1@gmail.com')
            ->whereNull('secondary_email')
            ->update(['secondary_email' => 'admin@g3control.com']);
    }

    public function down(): void
    {
        Schema::table('centres', function (Blueprint $table): void {
            $table->dropColumn('secondary_email');
        });
    }
};
