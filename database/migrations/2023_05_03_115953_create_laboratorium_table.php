<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Laboratorium;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laboratorium', function (Blueprint $table) {
            $table->string('id_laboratorium',36)->charset('utf8')->collation('utf8_general_ci');
            $table->primary('id_laboratorium');
            $table->string('nm_laboratorium');
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });

        $data =  array(
            [
                'name' => 'Lab. Instrumentasi',
            ],
            [
                'name' => 'Lab. Pengembangan Sistem Proses Dan Produk',
            ],
            [
                'name' => 'Lab. Rekayasa Energi',
            ],
            [
                'name' => 'Lab. Praktikum Dasar Teknik Kimia',
            ],
            [
                'name' => 'Lab. Praktikum Pengantar Teknik Kimia',
            ],
            [
                'name' => 'Lab. Komputasi Proses',
            ],
            [
                'name' => 'Lab. Teknologi Material dan Teknik Pemisahan',
            ],
            [
                'name' => 'Lab. Praktikum Pengantar Teknik Kimia II',
            ],
        );
        foreach ($data as $datum){
            $laboratorium = new Laboratorium(); //The Category is the model for your migration
            $laboratorium->nm_laboratorium =$datum['name'];
            $laboratorium->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laboratorium');
    }
};
