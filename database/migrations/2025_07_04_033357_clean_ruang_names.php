<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanRuangNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Membersihkan spasi di awal dan akhir nama ruang
        DB::statement("UPDATE tbl_ruang SET name = TRIM(name)");

        // Membersihkan spasi di awal dan akhir r_sebelum dan r_sesudah di tabel histori
        DB::statement("UPDATE tbl_histori SET r_sebelum = TRIM(r_sebelum), r_sesudah = TRIM(r_sesudah)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
