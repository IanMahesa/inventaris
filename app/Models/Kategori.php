<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit jika tidak mengikuti konvensi Laravel
    protected $table = 'tbl_kategori';

    // Menentukan primary key jika bukan 'id'
    protected $primaryKey = 'kode';

    // Menyatakan bahwa primary key bukan auto-incrementing
    public $incrementing = false;

    // Menentukan tipe data primary key (karena code bertipe string)
    protected $keyType = 'string';

    // Field yang bisa diisi (mass assignment)
    protected $fillable = [
        'kode',
        'nama',
    ];
}
