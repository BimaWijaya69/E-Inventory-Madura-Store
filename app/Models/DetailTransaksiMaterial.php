<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksiMaterial extends Model
{
    use HasFactory;
    protected $table = 'detail_transaksi_materials';
    protected $fillable = [
        'transaksi_id',
        'material_id',
        'jumlah'
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    public function transaksi_material(): BelongsTo
    {
        return $this->belongsTo(TransaksiMaterial::class, 'transaksi_id');
    }
}
