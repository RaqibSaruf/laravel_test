<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->enum('account_type', config('common.accountType'))
                    ->comment(config('common.accountType.individual') . '=individual, ' . config('common.accountType.business') . '=business');
                $table->double('balance')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_type', 'balance']);
        });
    }
};