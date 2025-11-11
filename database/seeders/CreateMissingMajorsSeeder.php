<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class CreateMissingMajorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all images in storage
        $storageImages = glob(storage_path('app/public/images/majors/*.jpg'));
        
        // Get existing images in database
        $dbImages = Major::whereNotNull('image')->pluck('image')->toArray();
        
        // Find orphaned images
        $orphanedImages = [];
        foreach ($storageImages as $imagePath) {
            $relativePath = 'images/majors/' . basename($imagePath);
            if (!in_array($relativePath, $dbImages)) {
                $orphanedImages[] = $relativePath;
            }
        }
        
        if (count($orphanedImages) === 0) {
            $this->command->info('No orphaned images found. All images are linked to majors.');
            return;
        }
        
        $this->command->info('Found ' . count($orphanedImages) . ' orphaned images.');
        $this->command->info('Creating placeholder majors...');
        
        $sortOrder = Major::max('sort_order') ?? 0;
        $created = 0;
        
        foreach ($orphanedImages as $index => $image) {
            $sortOrder++;
            $majorNumber = $index + 2;
            
            Major::create([
                'code' => 'MAJOR' . $majorNumber,
                'name' => 'Jurusan ' . $majorNumber,
                'full_name' => 'Program Keahlian Jurusan ' . $majorNumber,
                'description' => 'Deskripsi untuk Jurusan ' . $majorNumber . '. Silakan edit data ini melalui Admin Panel.',
                'image' => $image,
                'category' => 'Teknologi',
                'student_count' => 0,
                'is_active' => true,
                'sort_order' => $sortOrder
            ]);
            
            $created++;
            $this->command->info("Created major #{$majorNumber} with image: " . basename($image));
        }
        
        $this->command->info("\nSuccessfully created {$created} placeholder majors!");
        $this->command->warn('IMPORTANT: Please edit these majors through Admin Panel to add proper names and descriptions.');
    }
}
