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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');

            $table->string('reason');

            $table->text('description')->nullable();

            $table->string('status')->default('pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index([
                'reportable_type',
                'reportable_id'
            ]);

            $table->index([
                'status',
                'created_at'
            ]);

            $table->index('reporter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
