<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ruang', function (Blueprint $table) {
            $table->id('id_ruang'); // Primary key
            $table->string('code')->unique(); // Kolom kode akun, misalnya: 1-100
            $table->string('name'); // Nama akun, misalnya: Kas, Piutang
            $table->integer('is_delete')->default(0); // Soft delete manual
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_ruang');
    }
}
