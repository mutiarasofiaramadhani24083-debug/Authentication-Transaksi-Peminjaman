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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();                                        // bigint, primary key, auto increment
            $table->string('nama_kategori', 50)->unique();       // unique, not null
            $table->text('deskripsi')->nullable();               // nullable
            $table->string('icon', 50)->nullable();              // untuk icon Bootstrap
            $table->string('warna', 20)->nullable();             // untuk badge color
            $table->timestamps();                                // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};