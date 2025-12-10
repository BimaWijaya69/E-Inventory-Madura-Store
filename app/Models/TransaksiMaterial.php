<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransaksiMaterial extends Model
{
    use HasFactory;
    protected $table = 'transaksi_materials';
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'jenis',
        'nama_pihak_transaksi',
        'keperluan',
        'nomor_pelanggan',
        'foto_bukti',
        'foto_sr_sebelum',
        'foto_sr_sesudah',
        'dibuat_oleh'
    ];

    public function dibuat_oleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detail_transaksi(): HasMany
    {
        return $this->hasMany(DetailTransaksiMaterial::class, 'transaksi_id');
    }

    public function verifikasi_transaksi(): HasOne
    {
        return $this->hasOne(VerifikasiTraksaksi::class, 'transaksi_id');
    }
}
