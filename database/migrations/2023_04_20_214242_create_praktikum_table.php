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
        Schema::create('praktikum', function (Blueprint $table) {
            $table->string('id_praktikum',36)->charset('utf8')->collation('utf8_general_ci');
            $table->string('nm_praktikum',255)->charset('utf8')->collation('utf8_general_ci');
            $table->string('status',50)->charset('utf8')->collation('utf8_general_ci');
            $table->text('keterangan');
            $table->bigInteger('id_user')->unsigned();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->primary('id_praktikum');
            $table->timestamps();
           
        });

        Schema::table('praktikum', function($table) {
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('praktikum');
    }
};
