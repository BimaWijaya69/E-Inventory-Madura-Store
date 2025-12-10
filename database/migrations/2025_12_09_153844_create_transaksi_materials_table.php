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
        Schema::create('transaksi_materials', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->date('tanggal');
            $table->enum('jenis', ['0', '1']);
            $table->string('nama_pihak_transaksi');
            $table->enum('keperluan', ['0', '1', '2', '3'])->comment("0=YANBUNG','1=P2TL','2=GANGGUAN','3=PLN");
            $table->string('nomor_pelanggan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->string('foto_sr_sebelum')->nullable();
            $table->string('foto_sr_sesudah')->nullable();
            $table->foreignId('dibuat_oleh')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_materials');
    }
};
