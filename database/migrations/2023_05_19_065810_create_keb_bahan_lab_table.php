<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keb_bahan_lab', function (Blueprint $table) {
            $table->string('id',36)->charset('utf8')->collation('utf8_general_ci');
            $table->primary('id');
            $table->string('id_laboratorium',36)->charset('utf8')->collation('utf8_general_ci');
            $table->foreign('id_laboratorium')->references('id_laboratorium')->on('laboratorium');
            $table->string('nm_bahan',255)->charset('utf8')->collation('utf8_general_ci');
            $table->string('satuan',50)->charset('utf8')->collation('utf8_general_ci');
            $table->text('deskripsi');
            $table->integer('jumlah_ajuan');
            $table->integer('tahun');
            $table->text('praktikum');
            $table->string('status',50)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->string('id_bahan',36)->charset('utf8')->collation('utf8_general_ci');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
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
        Schema::dropIfExists('keb_bahan_lab');
    }
};
