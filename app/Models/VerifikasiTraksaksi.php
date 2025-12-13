<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerifikasiTraksaksi extends Model
{
    use HasFactory;
    protected $table = 'verifikasi_traksaksis';
    protected $fillable = [
        'transaksi_id',
        'status',
        'diverifikasi_oleh',
        'alasan_pengembalian'
    ];

    public function transaksi_material(): BelongsTo
    {
        return $this->belongsTo(TransaksiMaterial::class, 'transaksi_id');
    }

    public function penverifikasi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}
