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
        Schema::create('annual_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->year('years', 4)->unique();
            $table->decimal('cash_in', 10, 0);
            $table->decimal('cash_out', 10, 0);
            $table->datetime('created_at');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_stats');
    }
};
