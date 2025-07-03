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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreign('team_id')
                ->constrained('teams')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->enum('role', ['owner', 'member']);
            $table->unique(['team_id', 'user_id']);;
            $table->index('created_by');
            $table->timestamp('joined_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
