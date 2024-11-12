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
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->nullable()->unique();
            $table->string('phone')->unique();
            $table->integer('city')->nullable();
            $table->integer('integer')->nullable();
            $table->string('birthday')->nullable();
            $table->integer('height')->nullable();
            $table->string('weight')->nullable();
            $table->integer('pincode')->nullable();
            $table->integer('sex')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login');
            $table->dropColumn('phone');
            $table->dropColumn('city');
            $table->dropColumn('integer');
            $table->dropColumn('birthday');
            $table->dropColumn('height');
            $table->dropColumn('weight');
            $table->dropColumn('pincode');
            $table->dropColumn('sex');
        });
    }
};
