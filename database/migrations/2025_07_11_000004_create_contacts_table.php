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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['new', 'in_progress', 'resolved'])->default('new');
            
            // Admin response fields
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->unsignedBigInteger('responded_by')->nullable();
            $table->boolean('is_read')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('email');
            $table->index('status');
            $table->index('is_read');
            $table->index('responded_at');
            
            // Foreign keys
            $table->foreign('responded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};