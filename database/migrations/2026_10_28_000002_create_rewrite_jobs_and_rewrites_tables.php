<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewrite_jobs', function (Blueprint $table): void {
            $table->id();
            $table->text('source');
            $table->string('status')->default('complete');
            $table->timestamps();
        });

        Schema::create('rewrites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rewrite_job_id')->constrained()->cascadeOnDelete();
            $table->text('rewritten');
            $table->json('kept_facts');
            $table->boolean('is_valid')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewrites');
        Schema::dropIfExists('rewrite_jobs');
    }
};
