<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Histori extends Model
{
    protected $table = 'tbl_histori';
    protected $primaryKey = 'id_histori';
    public $timestamps = true;

    protected $fillable = [
        'id_asetsblm',
        'st_histori',
        'id_regis',
        'tanggal_sblm',
        'tanggal',
        'name_brg',
        'jenis_brg',
        'th_oleh',
        'r_sebelum',
        'r_sesudah',
        'hkondisi',
        'hmerk',
        'hseri',
        'hfoto',
        'hbahan',
        'hsize',
        'hjum_brg',
        'hprice',
        'ket',
        'is_delete',
    ];

    protected $casts = [
        'st_histori' => 'string',
        'tanggal_sblm' => 'date',
        'tanggal'    => 'date',
        'th_oleh'    => 'date',
        'id_regis'   => 'string',
        'is_delete'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    public function ruangSebelum()
    {
        return $this->belongsTo(KodeRuang::class, 'r_sebelum', 'name');
    }

    public function ruangSesudah()
    {
        return $this->belongsTo(KodeRuang::class, 'r_sesudah', 'code');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'jenis_brg', 'nama');
    }

    // Dalam model Aset.php
    public function histori()
    {
        return $this->hasOne(Histori::class, 'id_asetsblm', 'id_aset');
    }

    public function editByAset($id_aset)
    {
        $histori = Histori::where('id_asetsblm', $id_aset)->firstOrFail();
        return redirect()->route('histori.edit', ['histori' => $histori->id]);
    }

    public function dataAset()
    {
        return $this->belongsTo(Aset::class, 'id_asetsblm', 'id_aset')->where('is_delete', '!=', 1);
    }


    public function ambilAset($id)
    {
        return DB::table('tbl_histori')
            ->where('tbl_histori.id_histori', $id)
            ->join('tbl_ruang', 'tbl_ruang.id_ruang', '=', 'tbl_histori.id_ruang')
            ->join('tbl_kategori', 'tbl_kategori.id_kategori', '=', 'tbl_histori.id_kategori')
            ->select(
                'tbl_histori.*',
                'tbl_ruang.code as kd_ruang',
                'tbl_kategori.kode as jenis_brg'
            )
            ->first(); // ambil satu data saja
    }

    public function dataAsetWithRelations($id)
    {
        $data = Histori::with(['dataAset', 'kategori', 'ruangSebelum', 'ruangSesudah'])->find($id);
        return response()->json($data);
    }

    public function ruang()
    {
        return $this->ruangSesudah(); // alias dari ruangSesudah
    }

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'id_asetsblm', 'id_aset');
    }
}
