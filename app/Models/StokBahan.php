<?php

namespace App\Models;

class StokBahan extends BaseModel
{
    // nama tabel di database
	protected $table = 'stok_bahan';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_stok_bahan';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = [];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'jumlah' => 'required',
        'keterangan' => 'required'
	];

}