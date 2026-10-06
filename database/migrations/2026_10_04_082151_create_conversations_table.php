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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('tenant_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('landlord_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('rental_request_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('status')
                ->default('active');

            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['landlord_id', 'status']);
            $table->index(['property_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
