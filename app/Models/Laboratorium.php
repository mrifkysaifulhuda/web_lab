<?php

namespace App\Models;

class Laboratorium extends BaseModel
{
    // nama tabel di database
	protected $table = 'laboratorium';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_laboratorium';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['nm_laboratorium'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [];

}