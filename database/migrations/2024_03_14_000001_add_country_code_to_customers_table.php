<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers') || Schema::hasColumn('customers', 'country_code')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->string('country_code')->default('+57')->after('phone');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('customers') || !Schema::hasColumn('customers', 'country_code')) {
            return;
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('country_code');
        });
    }
};