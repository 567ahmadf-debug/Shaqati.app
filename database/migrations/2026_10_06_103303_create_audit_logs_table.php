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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('actor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('action');

            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');

            $table->json('metadata')->nullable();

            $table->ipAddress('ip_address')->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index([
                'auditable_type',
                'auditable_id',
            ]);

            $table->index([
                'actor_id',
                'created_at',
            ]);

            $table->index([
                'action',
                'created_at',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
