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
        Schema::create('stok_bahan', function (Blueprint $table) {
            $table->string('id_stok_bahan',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('id_bahan',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('tipe',36)->charset('utf8')->collation('utf8_general_ci');
            $table->integer('jumlah');
            $table->string('keterangan',255)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });

        Schema::table('stok_bahan', function($table) {
            $table->foreign('id_bahan')->references('id_bahan')->on('bahan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_bahan');
    }
};
