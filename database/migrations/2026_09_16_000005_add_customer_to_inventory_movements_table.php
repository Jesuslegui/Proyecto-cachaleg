<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('inventory_movements', 'customer_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->foreignId('customer_id')->nullable()->after('product_id')->constrained('customers')->nullOnDelete();
                $table->index(['customer_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('inventory_movements', 'customer_id')) {
            Schema::table('inventory_movements', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);
                $table->dropIndex(['customer_id', 'created_at']);
                $table->dropColumn('customer_id');
            });
        }
    }
};