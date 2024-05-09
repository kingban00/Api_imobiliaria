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
        Schema::table('real_state', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable()->references('id')->on('addresses')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('real_state', function (Blueprint $table) {
            $table->dropForeign(['real_state_address_id_foreign']);
            $table->dropColumn('address_id');
        });
    }
};
