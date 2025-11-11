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
        Schema::create('majors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode jurusan seperti PPLG, TJKT
            $table->string('name'); // Nama jurusan
            $table->string('full_name'); // Nama lengkap jurusan
            $table->text('description'); // Deskripsi jurusan
            $table->string('image')->nullable(); // Gambar jurusan
            $table->string('category')->default('technology'); // Kategori seperti teknologi, jaringan
            $table->integer('student_count')->default(0); // Jumlah siswa
            $table->boolean('is_active')->default(true); // Status aktif/tidak
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
