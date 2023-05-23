<?php

namespace App\Models;

class Bahan extends BaseModel
{
    // nama tabel di database
	protected $table = 'bahan';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_bahan';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['nm_bahan', 'deskripsi'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'nm_bahan' => 'required',
		'deskripsi' => 'required'
	];

}