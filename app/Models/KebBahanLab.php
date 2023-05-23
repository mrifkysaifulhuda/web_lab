<?php

namespace App\Models;

class KebBahanLab extends BaseModel
{
    // nama tabel di database
	protected $table = 'keb_bahan_lab';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['nm_bahan'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [];

}