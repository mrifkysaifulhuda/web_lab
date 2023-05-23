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
        Schema::table('alat', function (Blueprint $table) {
            $table->string('id_laboratorium',36)->charset('utf8')->collation('utf8_general_ci');
        });

        Schema::table('alat', function($table) {
            $table->foreign('id_laboratorium')->references('id_laboratorium')->on('laboratorium');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->dropColumn('id_laboratorium');
        });
    }
};
