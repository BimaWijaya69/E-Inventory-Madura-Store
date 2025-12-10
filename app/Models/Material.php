<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    protected $fillable = [
        'kode_material',
        'nama_material',
        'satuan',
        'stok_awal',
        'min_stok',
        'stok',
        'delet_at'
    ];

    public function detail_transaksi_material(): HasMany
    {
        return $this->hasMany(DetailTransaksiMaterial::class, 'material_id');
    }
}
