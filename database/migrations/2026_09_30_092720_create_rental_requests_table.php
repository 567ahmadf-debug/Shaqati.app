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
        Schema::create('rental_requests', function (Blueprint $table) {
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

            $table->string('status')
                ->default('pending');

            $table->text('message')
                ->nullable();

            $table->timestamp('submitted_at');

            $table->timestamp('responded_at')
                ->nullable();

            $table->text('rejection_reason')
                ->nullable();

            $table->timestamps();

            $table->index(['property_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['landlord_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_requests');
    }
};
