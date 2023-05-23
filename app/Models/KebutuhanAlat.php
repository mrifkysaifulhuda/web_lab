<?php

namespace App\Models;

class KebutuhanAlat extends BaseModel
{
    // nama tabel di database
	protected $table = 'kebutuhan_alat';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_kebutuhan_alat';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['id_kebutuhan_alat'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'jumlah_ajuan' => 'required',
        'id_alat' => 'required',
        'id_praktikum' => 'required'

	];

}