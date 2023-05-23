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
        Schema::table('user_laboratorium', function (Blueprint $table) {
            $table->dropColumn('nm_laboratorium');
            $table->string('id_laboratorium',36)->charset('utf8')->collation('utf8_general_ci');
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
        Schema::table('user_laboratorium', function (Blueprint $table) {
            $table->dropColumn('id_laboratorium');
        });
    }
};
