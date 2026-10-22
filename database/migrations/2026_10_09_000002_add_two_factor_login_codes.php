<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('two_factor_login_tokens')->delete();

        Schema::table('two_factor_login_tokens', function (Blueprint $table) {
            $table->string('code_hash')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('two_factor_login_tokens', function (Blueprint $table) {
            $table->dropUnique('two_factor_login_tokens_user_id_unique');
            $table->dropColumn(['code_hash', 'attempts']);
        });
    }
};
