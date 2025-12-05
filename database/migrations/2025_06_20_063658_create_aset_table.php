<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_aset', function (Blueprint $table) {
            $table->id('id_aset');
            $table->string('nama_brg', 50);
            $table->string('merk');
            $table->string('seri');
            $table->string('foto');
            $table->string('bahan');
            $table->decimal('ukuran', 8, 2)->default(0.00);
            $table->date('periode')->nullable();
            $table->string('barang_id', 50)->nullable();
            $table->date('date')->nullable()->comment('tanggal transaksi');
            $table->text('keterangan')->nullable();
            $table->string('jumlah_brg');
            $table->decimal('harga', 15, 2)->default(0.00);
            $table->enum('kondisi', ['Baik', 'Kurang Baik', 'Rusak Berat']);
            $table->string('code_ruang', 20)->nullable();
            $table->string('code_kategori', 20)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->tinyInteger('is_delete')->default(0);

            // Indexes
            $table->index('nama_brg');
            $table->index('date');
            $table->index('code_ruang');
            $table->index('kondisi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_aset');
    }
}
