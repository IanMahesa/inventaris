<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \Illuminate\Support\Collection $aset_tahun_lalu
 * @property \Illuminate\Support\Collection $aset_tahun_ini
 * @property array $aset_per_tahun
 */
class KodeRuang extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit jika tidak mengikuti konvensi Laravel
    protected $table = 'tbl_ruang';

    // Menentukan primary key jika bukan 'id'
    protected $primaryKey = 'code';

    // Menyatakan bahwa primary key bukan auto-incrementing
    public $incrementing = false;

    // Menentukan tipe data primary key (karena code bertipe string)
    protected $keyType = 'string';

    // Field yang bisa diisi (mass assignment)
    protected $fillable = [
        'code',
        'name',
        'is_delete',
    ];

    protected $casts = [
        'is_delete' => 'integer',
    ];

    // Property untuk menyimpan data aset per tahun (tidak disimpan ke database)
    public $aset_per_tahun = [];

    // Relasi ke Aset
    public function asets()
    {
        return $this->hasMany(Aset::class, 'code_ruang', 'code');
    }
}
