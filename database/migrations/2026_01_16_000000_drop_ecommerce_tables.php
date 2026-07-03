<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the ecommerce subsystem (cart / order / wallet).
     *
     * Guard-safe so it works both on a fresh test database (tables were
     * never created) and on an existing production database.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach (['order_items', 'orders', 'cart_items', 'carts', 'wallet_transactions'] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();

        if (Schema::hasColumn('users', 'balance')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('balance');
            });
        }
    }

    /**
     * These tables are intentionally gone; nothing to restore.
     */
    public function down(): void
    {
        // no-op
    }
};
