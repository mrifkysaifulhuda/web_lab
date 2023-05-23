<?php

namespace App\Models;

class UserLaboratorium extends BaseModel
{
    // nama tabel di database
	protected $table = 'user_laboratorium';

	// primary key untuk model/tabel ini. 
	protected $primaryKey = 'id_user_laboratorium';

	// composite key (jika ada)
	protected $compositeKeys = [];

	// desc kolom untuk combo
	protected $descColumns = ['id_laboratorium'];

	// rule validasi untuk model ini. Referensi bisa dilihat di:
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [
		'id_user' => 'required',
        'id_laboratorium' => 'required'
	];
}