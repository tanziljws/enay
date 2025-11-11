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
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('activities', 'description')) {
                $table->text('description');
            }
            if (!Schema::hasColumn('activities', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('activities', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('activities', 'category')) {
                $table->enum('category', ['academic', 'sports', 'cultural', 'social', 'other'])->default('other');
            }
            if (!Schema::hasColumn('activities', 'status')) {
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            }
            if (!Schema::hasColumn('activities', 'start_date')) {
                $table->datetime('start_date');
            }
            if (!Schema::hasColumn('activities', 'end_date')) {
                $table->datetime('end_date')->nullable();
            }
            if (!Schema::hasColumn('activities', 'organizer')) {
                $table->string('organizer')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            //
        });
    }
};
