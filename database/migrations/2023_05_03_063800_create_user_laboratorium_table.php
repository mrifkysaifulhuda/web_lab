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
        Schema::create('user_laboratorium', function (Blueprint $table) {
            $table->string('id_user_laboratorium',36)->charset('utf8')->collation('utf8_general_ci');
            $table->bigInteger('id_user')->unsigned();
            $table->string('nm_laboratorium', 50);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->primary('id_user_laboratorium');
            $table->timestamps();
        });

        Schema::table('user_laboratorium', function($table) {
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
        Schema::dropIfExists('user_laboratorium');
    }
};
