<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\GalleryUserComment;
use App\Models\NewsUserComment;
use App\Models\TeacherComment;
use Illuminate\Support\Facades\DB;

class MigrateOldComments extends Command
{
    protected $signature = 'comments:migrate';
    protected $description = 'Migrate old comments to new polymorphic comments table';

    public function handle()
    {
        $this->info('Starting comment migration...');
        
        // Migrate Gallery Comments
        if (DB::getSchemaBuilder()->hasTable('gallery_user_comments')) {
            $galleryComments = GalleryUserComment::all();
            $this->info("Migrating {$galleryComments->count()} gallery comments...");
            
            foreach ($galleryComments as $old) {
                Comment::create([
                    'user_id' => $old->user_id,
                    'commentable_type' => 'App\Models\GalleryItem',
                    'commentable_id' => $old->gallery_item_id,
                    'comment' => $old->comment,
                    'is_approved' => $old->is_approved ?? true,
                    'created_at' => $old->created_at,
                    'updated_at' => $old->updated_at,
                ]);
            }
        }
        
        // Migrate News Comments
        if (DB::getSchemaBuilder()->hasTable('news_user_comments')) {
            $newsComments = NewsUserComment::all();
            $this->info("Migrating {$newsComments->count()} news comments...");
            
            foreach ($newsComments as $old) {
                Comment::create([
                    'user_id' => $old->user_id,
                    'commentable_type' => 'App\Models\News',
                    'commentable_id' => $old->news_id,
                    'comment' => $old->comment,
                    'is_approved' => $old->is_approved ?? true,
                    'created_at' => $old->created_at,
                    'updated_at' => $old->updated_at,
                ]);
            }
        }
        
        // Migrate Teacher Comments
        if (DB::getSchemaBuilder()->hasTable('teacher_comments')) {
            $teacherComments = TeacherComment::all();
            $this->info("Migrating {$teacherComments->count()} teacher comments...");
            
            foreach ($teacherComments as $old) {
                Comment::create([
                    'user_id' => $old->user_id,
                    'commentable_type' => 'App\Models\Teacher',
                    'commentable_id' => $old->teacher_id,
                    'comment' => $old->comment,
                    'is_approved' => $old->is_approved ?? true,
                    'created_at' => $old->created_at,
                    'updated_at' => $old->updated_at,
                ]);
            }
        }
        
        $total = Comment::count();
        $this->info("Migration completed! Total comments: {$total}");
        
        return 0;
    }
}
