<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Aset extends Model
{
    protected $table = 'tbl_aset'; // Nama tabel

    protected $primaryKey = 'id_aset'; // Primary key

    public $timestamps = true; // Aktifkan timestamps

    protected $fillable = [
        'st_aset',
        'date',
        'periode',
        'nama_brg',
        'merk',
        'seri',
        'ukuran',
        'jumlah_brg',
        'bahan',
        'harga',
        'kondisi',
        'code_kategori',
        'code_ruang',
        'keterangan',
        'foto', // tetap ada
        'satuan',
    ];


    protected $casts = [
        'date'       => 'date',
        'jumlah_brg' => 'integer',
        'harga'      => 'decimal:2',
        'periode'    => 'date',
        'is_delete'  => 'integer',
        'foto'       => 'array',   // <--- ini penting
    ];


    /**
     * Relasi ke model Barang (jika ada).
     * public function barang()
     * {
     *     return $this->belongsTo(Barang::class);
     * }
     */
    public function ruang()
    {
        return $this->belongsTo(KodeRuang::class, 'code_ruang', 'code')
            ->where('is_delete', 0);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'code_kategori', 'kode')
            ->where('is_delete', 0);
    }
    public function histori()
    {
        return $this->hasMany(Histori::class, 'id_asetsblm', 'id_aset');
    }
    public function latestHistori()
    {
        return $this->hasOne(Histori::class, 'id_asetsblm', 'id_aset')->latestOfMany('tanggal');
    }

    public function ambilAset($id)
    {
        return DB::table('tbl_aset')
            ->where('tbl_aset.id_aset', $id)
            ->join('tbl_ruang', 'tbl_ruang.code', '=', 'tbl_aset.code_ruang')
            ->join('tbl_kategori', 'tbl_kategori.kode', '=', 'tbl_aset.code_kategori')
            ->select(
                'tbl_aset.*',
                'tbl_ruang.code as code_ruang',
                'tbl_ruang.name',
                'tbl_kategori.kode as code_kategori',
                'tbl_kategori.nama'
            )
            ->first();
    }
}
