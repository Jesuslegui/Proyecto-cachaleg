<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('image')->nullable()->after('product_description');
        });
    }

    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};