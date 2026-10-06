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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            $table->foreignId('landlord_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            $table->string('property_type');

            $table->decimal('rental_price', 12, 2);
            $table->string('currency', 3);

            $table->decimal('area', 10, 2)
                ->nullable();

            $table->unsignedSmallInteger('bedrooms')
                ->nullable();

            $table->unsignedSmallInteger('bathrooms')
                ->nullable();

            $table->string('address');

            $table->string('city');

            $table->decimal('latitude', 10, 7)
                ->nullable();

            $table->decimal('longitude', 10, 7)
                ->nullable();

            $table->string('status')
                ->default('draft');

            $table->string('availability_status')
                ->default('available');

            $table->timestamps();

            $table->softDeletes();

            $table->index(['landlord_id', 'status']);
            $table->index(['status', 'availability_status']);
            $table->index('rental_price');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
