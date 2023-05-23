<?php

namespace App\Models;

class Praktikum extends BaseModel
{
    // nama tabel di database
	protected $table = 'praktikum';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_praktikum';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['nm_praktikum', 'keterangan'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'nm_praktikum' => 'required'
	];

}