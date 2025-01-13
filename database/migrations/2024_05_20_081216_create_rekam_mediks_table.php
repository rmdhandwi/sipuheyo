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
        Schema::create('rekam_mediks', function (Blueprint $table) {
            $table->id();
            $table->string('antrian')->nullable();
            $table->date('tanggal');
            $table->integer('pasien_id');
            $table->integer('dokter_id');
            $table->integer('poli_id');
            $table->json('kondisi')->nullable();
            $table->json('keluhan')->nullable();
            $table->json('penanganan')->nullable();
            $table->json('resep')->nullable();
            $table->string('hasil_lab');
            $table->dateTime('konsultasi_berikut')->nullable();
            $table->dateTime('kirimpesan1')->nullable();
            $table->dateTime('kirimpesan2')->nullable();
            $table->enum('status',['baru','admin','poli','dokter', 'batal'])->default('baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_mediks');
    }
};
