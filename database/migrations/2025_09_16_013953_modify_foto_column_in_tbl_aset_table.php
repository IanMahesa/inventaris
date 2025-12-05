<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFotoColumnInTblAsetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_aset', function (Blueprint $table) {
            // Ubah kolom foto dari VARCHAR(255) menjadi TEXT untuk menyimpan JSON array yang panjang
            $table->text('foto')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_aset', function (Blueprint $table) {
            // Kembalikan kolom foto ke VARCHAR(255)
            $table->string('foto')->change();
        });
    }
}
