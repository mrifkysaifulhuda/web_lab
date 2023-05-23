<?php

namespace App\Models;

class KebAlatLab extends BaseModel
{
    // nama tabel di database
	protected $table = 'keb_alat_lab';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['nm_alat'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [];

}