<?php

namespace App\Models;

class KebutuhanBahan extends BaseModel
{
    // nama tabel di database
	protected $table = 'kebutuhan_bahan';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_kebutuhan_bahan';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['id_kebutuhan_bahan'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'jumlah_ajuan' => 'required',
        'id_bahan' => 'required',
        'id_praktikum' => 'required'

	];

}