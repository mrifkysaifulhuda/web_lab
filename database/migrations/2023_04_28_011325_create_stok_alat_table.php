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
        Schema::create('stok_alat', function (Blueprint $table) {
            $table->string('id_stok_alat',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('id_alat',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('tipe',36)->charset('utf8')->collation('utf8_general_ci');
            $table->integer('jumlah');
            $table->string('keterangan',255)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });

        Schema::table('stok_alat', function($table) {
            $table->foreign('id_alat')->references('id_alat')->on('alat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok_alat');
    }
};
