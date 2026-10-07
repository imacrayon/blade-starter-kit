<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role');
            $table->timestamps();
            $table->unique(['team_id', 'user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->after('role', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            });
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->after('role', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained('teams')->cascadeOnDelete();
            });
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('team_id');
        });

        Schema::dropIfExists('memberships');

        Schema::dropIfExists('teams');
    }
};
