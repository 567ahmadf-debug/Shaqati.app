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
        Schema::create('identity_documents', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete() ; 
            $table->string('document_path') ; 
            $table->string('document_type') ; 
            $table->string('status') ->default('pending') ; 
            $table->string('rejection_reason') ->nullable() ;
            $table->timestamp('verified_at') ->nullable() ; 
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_documents');
    }
};
