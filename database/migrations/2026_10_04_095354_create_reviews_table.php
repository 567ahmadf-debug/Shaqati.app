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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('tenant_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('contract_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedTinyInteger('rating');

            $table->text('comment')->nullable();

            $table->string('status')->default('published');

            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['rating', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
