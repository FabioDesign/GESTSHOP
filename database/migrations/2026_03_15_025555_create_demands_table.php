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
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid');
            $table->string('code', 50)->unique();
            $table->tinyInteger('day');
            $table->decimal('amount', 10, 0);
            $table->text('motif')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->date('delivered_at');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('transmitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('rejeted_at')->nullable();
            $table->timestamp('retrieved_at')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->foreignId('transmitted_by')->nullable();
            $table->foreignId('validated_by')->nullable();
            $table->foreignId('rejeted_by')->nullable();
            $table->foreignId('retrieved_by')->nullable();
            $table->foreignId('user_id');
            $table->integer('document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
