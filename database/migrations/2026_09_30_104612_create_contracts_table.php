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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rental_request_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('property_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('tenant_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('landlord_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->date('start_date');

            $table->date('end_date')
                ->nullable();

            $table->decimal('rental_price', 12, 2);

            $table->string('currency', 3);

            $table->string('status')
                ->default('draft');

            $table->string('contract_document_path')
                ->nullable();

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
        Schema::dropIfExists('contracts');
    }
};
