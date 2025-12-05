<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_histori', function (Blueprint $table) {
            $table->id('id_histori');
            $table->string('id_asetsblm', 50)->nullable();
            $table->enum('st_histori', ['BL', 'HBH', 'PDH', 'RSK', 'LLG']);
            $table->string('id_regis', 50)->nullable();
            $table->date('tanggal')->nullable()->comment('tanggal transaksi');
            $table->string('name_brg', 50);
            $table->string('jenis_brg', 50);
            $table->date('th_oleh')->nullable();
            $table->string('r_sebelum', 50);
            $table->string('r_sesudah', 50);
            $table->string('hmerk');
            $table->string('hseri');
            $table->longtext('hfoto');
            $table->string('hbahan');
            $table->decimal('hsize', 8, 2)->default(0.00);
            $table->text('ket')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->tinyInteger('is_delete')->default(0);

            // Indexes
            $table->index('name_brg');
            $table->index('tanggal');
            $table->index('id_regis');
            $table->index('st_histori');
            $table->index('r_sebelum');
            $table->index('r_sesudah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_histori');
    }
}
