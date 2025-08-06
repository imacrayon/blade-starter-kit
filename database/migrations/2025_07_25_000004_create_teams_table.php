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
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams');
            $table->foreignId('user_id')->constrained('users');
            $table->string('role');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->after('role', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained('teams');
            });
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->after('role', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained('teams');
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');

        Schema::dropIfExists('memberships');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });

        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
    }
};
