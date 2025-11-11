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
        // Table for likes/dislikes on gallery items
        Schema::create('gallery_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gallery_item_id');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();
            
            // User can only have one reaction per gallery item
            $table->unique(['user_id', 'gallery_item_id']);
            $table->index('user_id');
            $table->index('gallery_item_id');
        });

        // Table for comments on gallery items
        Schema::create('gallery_user_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('gallery_item_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('gallery_item_id');
        });

        // Table for likes/dislikes on news
        Schema::create('news_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_id');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();
            
            $table->unique(['user_id', 'news_id']);
            $table->index('user_id');
            $table->index('news_id');
        });

        // Table for comments on news
        Schema::create('news_user_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('news_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('news_id');
        });

        // Table for likes/dislikes on teachers
        Schema::create('teacher_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('teacher_id');
            $table->enum('type', ['like', 'dislike']);
            $table->timestamps();
            
            $table->unique(['user_id', 'teacher_id']);
            $table->index('user_id');
            $table->index('teacher_id');
        });

        // Table for comments on teachers
        Schema::create('teacher_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('teacher_id');
            $table->text('comment');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('teacher_id');
        });

        // Table for download tracking
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('downloadable_type'); // GalleryItem, News, Teacher
            $table->unsignedBigInteger('downloadable_id');
            $table->string('file_path');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index(['downloadable_type', 'downloadable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('teacher_comments');
        Schema::dropIfExists('teacher_reactions');
        Schema::dropIfExists('news_user_comments');
        Schema::dropIfExists('news_reactions');
        Schema::dropIfExists('gallery_user_comments');
        Schema::dropIfExists('gallery_reactions');
    }
};
