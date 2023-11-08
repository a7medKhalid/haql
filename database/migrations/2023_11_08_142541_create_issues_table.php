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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->text('description');
            $table->string('status')->default(\App\Enums\Issue\IssueStatus::Open);

            $table->foreignId('user_id')->nullable();
            $table->foreignId('project_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
