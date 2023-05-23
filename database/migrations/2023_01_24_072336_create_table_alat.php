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
        Schema::create('alat', function (Blueprint $table) {
            $table->string('id_alat',36)->charset('utf8')->collation('utf8_general_ci');
            $table->primary('id_alat');
            $table->string('nm_alat',255)->charset('utf8')->collation('utf8_general_ci');
            $table->string('merek',255)->charset('utf8')->collation('utf8_general_ci');
            $table->text('profil');
            $table->text('instruksi');
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
        Schema::dropIfExists('alat');
    }
};
