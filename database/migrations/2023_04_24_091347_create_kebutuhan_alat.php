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
        Schema::create('kebutuhan_alat', function (Blueprint $table) {
            $table->string('id_kebutuhan_alat',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('id_praktikum',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('id_alat',36)->charset('utf8')->collation('utf8_general_ci');
            $table->integer('jumlah_ajuan');
            $table->integer('jumlah_terima')->nullable();
            $table->string('keterangan',50)->charset('utf8')->collation('utf8_general_ci')->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });

        Schema::table('kebutuhan_alat', function($table) {
            $table->foreign('id_praktikum')->references('id_praktikum')->on('praktikum');
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
        Schema::dropIfExists('kebutuhan_alat');
    }
};
