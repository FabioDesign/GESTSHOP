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
        Schema::create('cashes', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->decimal('cash_in', 10, 0);
            $table->decimal('cash_out', 10, 0);
            $table->date('date_at')->unique();
            $table->text('motif')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('transmitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('rejeted_at')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->foreignId('transmitted_by')->nullable();
            $table->foreignId('validated_by')->nullable();
            $table->foreignId('rejeted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashes');
    }
};
