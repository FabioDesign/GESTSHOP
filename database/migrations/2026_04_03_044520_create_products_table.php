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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->string('libelle');
            $table->decimal('prix_achat', 10, 0)->default('0');
            $table->decimal('prix_vente', 10, 0)->default('0');
            $table->decimal('seuil', 5, 0)->default('0');
            $table->decimal('stock', 5, 0)->default('0');
            $table->text('photo');
            $table->text('description');
            $table->tinyInteger('status')->default('1');
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->tinyInteger('category_id')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
