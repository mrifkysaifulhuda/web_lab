<?php

namespace App\Models;

use Closure;
use App\Uuids;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BaseModel extends Model
{
	use SoftDeletes, Uuids;

	// untuk model yang menggunakan composite key
	protected $compositeKeys = [];

	// apabila ada kolom yang tidak boleh diupdate secara masif
	protected $guarded = [];

	// selalu pakai timestamp (created_at dan updated_at), otomatis
	public $timestamps = true;

	// desc kolom untuk combo
	protected $descColumns = [];

	// disable increment karena kita pakai GUID
	public $incrementing = false;

	// rules untuk validasi sebelum data dibuat/diupdate
	// https://laravel.com/docs/5.7/validation#available-validation-rules
	public $rules = [];

	// apakah primary key otomatis? (GUID)
	public $isAutoPrimary = true;

	// apakah sudah diubah manual? Supaya tidak tertimpa
	public $isCustom = false;

	// apakah ada file yang terkait dengan model ini?
	public $hasFile = false;

	// apakah multiple file?
	public $multipleFile = false;

	// kolom default order by?
	public $defaultOrder = [];

	// rules untuk dokumen
	public $rules_dokumen = 'required|mimes:pdf,jpg,png|max:2000';

	/**
	 * @return array
	 */
	public function getCompositeKeys()
	{
		return $this->compositeKeys;
	}

	public function getDescColumns()
	{
		return $this->descColumns;
	}

	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	public function hapusData($id)
	{
		$model = $this->find($id);

		if (is_null($model)) {
			return array(false, "Data not found");
		} else {
			$model->delete();
			return array(true, "Data sukses dihapus");
		}
	}


	public function getDataById($id)
	{
		return $this->find($id);
	}

	public function updateData($request, $model, $data, $data_dokumen = false, $id)
	{
		$model->update($data);

		if (isset($data_dokumen) && $data_dokumen) {
			$data_dokumen['model_id'] = $id;
			$this->saveDokumen($request->file('dokumen'), $data_dokumen, false);
		}
		return $model;
	}

	public function simpanData($request, $data, $data_dokumen = false)
	{
		$primary = $this->getPrimaryKey();
		$done = false;

		# && !is_array($primary) && isset($data[$primary])
		if ($this->isAutoPrimary == false) {
			$cek = null;
			// cek apakah sudah ada tapi soft deleted
			$composite = $this->getCompositeKeys();
			if (count($composite) > 0) {
				// ada composite keynya
				$key_ok = true;
				foreach ($composite as $key) {
					if (!isset($data[$key])) {
						$key_ok = false;
					}
				}
				if ($key_ok) {
					$cek = $this->withTrashed();
					foreach ($composite as $key) {
						$cek->where($key, $data[$key]);
					}
					$cek = $cek->first();
				}
			} elseif (isset($data[$primary])) {
				$cek = $this->withTrashed()->find($data[$primary]);
			}

			if (!is_null($cek)) {
				$cek->restore();
				$cek->update($data);
				$done = true;
				$model = $cek;
			}
		}
		if ($done == false)
			$model = $this->create($data);

		if (isset($data_dokumen) && $data_dokumen) {
			$data_dokumen['model_id'] = $model->$primary;
			$this->saveDokumen($request->file('dokumen'), $data_dokumen, false);
		}
		return $model;
	}

	public function pre_process($data)
	{
		$rules = $this->rules;
		foreach ($rules as $k => $v) {
			if (stristr($v, "numeric") !== false) {
				if (isset($data[$k]) && $data[$k] != "")
					$data[$k] = str_replace(",", "", $data[$k]);
			}
		}
		return $data;
	}

	public function getRelationInfo()
	{
		$q = \DB::select("select * from information_schema.KEY_COLUMN_USAGE where TABLE_SCHEMA='" . env('DB_DATABASE') . "' and TABLE_NAME='" . $this->table . "' and REFERENCED_TABLE_NAME is not null");

		$ret = [];
		foreach ($q as $row) {
			if(ucfirst(Str::camel($row->REFERENCED_TABLE_NAME)) != "Users"){
				$mdl = "\\App\\Models\\" . ucfirst(Str::camel($row->REFERENCED_TABLE_NAME));
				$mdl = new $mdl;
				$ret[$row->COLUMN_NAME] = [$row->REFERENCED_TABLE_NAME, $row->REFERENCED_COLUMN_NAME, $mdl->getDescColumns()];
			}
			
		}
		return $ret;
	}

	/**
	 * List data komplit  
	 * @param  $perpage Per halaman
	 * @return array object hasil pencarian
	 */
	public function getListData($perpage = 0)
	{
		$relations = $this->getRelationInfo();
		$defaultOrder = $this->defaultOrder;

		$q = \DB::table($this->table . " as a");
		$q->select('a.*');
		foreach ($relations as $col => $arr) {
			#$q->leftJoin($arr[0],$arr[0].".".$arr[1],"=","a.".$col);

			$q->leftJoin($arr[0] . " as " . $arr[0] . "_" . $col, $arr[0] . "_" . $col . "." . $arr[1], "=", "a." . $col);

			foreach ($arr[2] as $desc) {
				$q->addSelect(\DB::raw($arr[0] . "_" . $col . "." . $desc . " as " . $col));
				break;
			}
		}
		$q->whereNull('a.deleted_at');
		if (is_array($defaultOrder) && sizeof($defaultOrder) == 2)
			$q->orderBy('a.' . $defaultOrder[0], $defaultOrder[1]);
		if ($perpage > 0)
			return $q->paginate($perpage);
		return $q->get();
	}

	/**
	 * @param  $keyword berupa array pencarian
	 * @return array object hasil pencarian
	 */
	public function searchByKeywords($keywords, $perpage = 0)
	{
		$orderBy = array();
		$with_foreign = false;

		$q = \DB::table($this->table . " as a");

		$q->whereNull('a.deleted_at');

		$q->where(function ($w) use ($keywords, &$orderBy, &$with_foreign) {
			foreach ($keywords as $k => $v) {
				if ($k == "query") {
					$desc = $this->descColumns;

					$w->where(function ($w2) use ($desc, $v) {
						foreach ($desc as $dc) {
							$w2->orWhere("a." . $dc, 'like', '%' . $v . '%');
						}
					});
				} elseif ($k == "orderBy") {
					$ex_obb = is_array($v) ? $v : explode("#", $v);
					foreach ($ex_obb as $v) {
						$ex_ob = explode(",", $v);
						if (count($ex_ob) == 1)
							$ex_ob[1] = "asc";
						$orderByKey = isset($ex_ob[2]) ? $ex_ob[2] . "." . $ex_ob[0] : "a." . $ex_ob[0];
						$orderBy[$orderByKey] = $ex_ob[1];
					}
				} elseif ($k == "withForeign") {
					$with_foreign = true;
				} else {
					if ($v == "-1" && $k == "induk") {
						$w->whereNull("a." . $k);
					} else {
						$w->where("a." . $k, $v);
					}
				}
			}
		});

		if ($perpage > 0 || $with_foreign) {
			$relations = $this->getRelationInfo();

			$q->select('a.*');
			foreach ($relations as $col => $arr) {
				$q->leftJoin($arr[0] . " as " . $arr[0] . "_" . $col, $arr[0] . "_" . $col . "." . $arr[1], "=", "a." . $col);

				foreach ($arr[2] as $desc) {
					$q->addSelect(\DB::raw($arr[0] . "_" . $col . "." . $desc . " as " . $col));
					$q->addSelect(\DB::raw("a." . $col . " as raw_" . $col));
					break;
				}
			}
		}

		if (isset($orderBy)) {
			foreach ($orderBy as $k => $v) {
				$q->orderBy($k, $v);
			}
		}

		if ($perpage > 0)
			return $q->paginate($perpage);
		return $q->get();
	}

	/**
	 * @param  $keyword berupa array pencarian
	 * @return array combo siap pakai
	 */
	public function getCombo($keyword = '')
	{
		$defaultOrder = $this->defaultOrder;
		$q = \DB::table($this->table);

	
		if ($keyword) {
			if (is_array($keyword)) {
				foreach ($keyword as $k => $v) {
					if (is_array($v)) {
						if (count($v) > 0) {
							$q->whereIn($k, $v);
						}
					} else {
						$q->where($k, 'like', '%' . $v . '%');
					}
				}
			} else {
				foreach ($this->descColumns as $col) {
					$q->orWhere($col, 'like', '%' . $keyword . '%');
					//$q->where(function ($w) use ($col, $keyword) {
					//	$w->orWhere($col, 'like', '%' . $keyword . '%');
					//});
				}
			}
		}
		$q->whereNull('deleted_at');
		if (is_array($defaultOrder) && sizeof($defaultOrder) == 2)
			$q->orderBy($defaultOrder[0], $defaultOrder[1]);

		$q = $q->get();

		$data = array();

		

		foreach ($q as $row) {
			$desc = [];
			foreach ($this->descColumns as $col) {
				$desc[] = $row->$col;
			}
			$pri = $this->primaryKey;
			$data[$row->$pri] = implode(" - ", $desc);
		}

		return $data;
	}

	public function saveDokumen($file, $data, $is_multiple = true)
	{
		$dokumen = new \App\Models\Common\Dokumen;

		$status = true;
		$model = null;
		$message = "";
		try {
			// simpan di dalam filesystem
			$basepath = config('cnf.basepath_file_upload');

			$full_path = get_file_upload_path($basepath);
			$file_path = str_replace($basepath, "", $full_path);

			// proses pemindahan file
			$mimetype = $file->getClientMimeType();
			$size = $file->getClientSize();
			$filename = time() . "_" . $file->getClientOriginalName();
			$data = array_merge($data, array(
				'file_path'	=> $file_path,
				'file_name' => $filename,
				'file_type'	=> $mimetype,
				'file_size' => round($size / 1024, 2),
			));
			$file->move($full_path, $filename);

			if ($is_multiple) {
				$model = $dokumen::create($data);
			} else {
				$model = $dokumen->where('model', $data['model'])
					->where('model_id', $data['model_id'])
					->whereNull('deleted_at')
					->first();
				if (is_null($model)) {
					$model = $dokumen::create($data);
				} else {
					$model = $model->update($data);
				}
			}

			$message = "Dokumen sukses disimpan";
		} catch (\Illuminate\Database\QueryException $e) {
			$status = false;
			$message = $e->getMessage();
		} catch (PDOException $e) {
			$status = false;
			$message = $e->getMessage();
		}

		return [$status, $message, $model];
	}

	public function getComboAjax($term, $perpage = null, $initial_select = null, $with_foreign = null,Closure $interceptor = null)
	{
		$perpage = $perpage ? $perpage : 10;
		$with_foreign = $with_foreign === null ? false : $with_foreign;

		$q = \DB::table($this->table . " as a");

		$q->whereNull('a.deleted_at');
		$cols = [];
		if ($with_foreign) {
			$relations = $this->getRelationInfo();

			$q->select('a.*');
			foreach ($relations as $col => $arr) {
				$q->leftJoin($arr[0] . " as " . $arr[0] . "_" . $col, $arr[0] . "_" . $col . "." . $arr[1], "=", "a." . $col);

				foreach ($arr[2] as $desc) {
					$cols[] = $arr[0] . "_" . $col . "." . $desc;
					$q->addSelect(\DB::raw($arr[0] . "_" . $col . "." . $desc . " as " . $col));
					$q->addSelect(\DB::raw("a." . $col . " as raw_" . $col));
					break;
				}
			}
		}

		if ($term !== null) {
			$q->where(function ($q) use ($term, &$with_foreign, &$cols, $initial_select) {
				if ($term !== null) {
					foreach ($this->descColumns as $col) {
						$q->orWhere('a.' . $col, 'like', '%' . $term . '%');
					}
				}

				if ($with_foreign) {
					foreach ($cols as $col) {
						$q->orWhere($col, 'like', '%' . $term . '%');
					}
				}

				if ($initial_select !== null && $initial_select !== '') {
					$q->orWhere('a.' . $this->getPrimaryKey(), $initial_select);
				}
			});
		}




		if ($initial_select !== null && $initial_select !== '') {
			$q->orderByRaw(
				"
				(`a`.`{$this->getPrimaryKey()}` = ? ) DESC
				",
				[$initial_select]
			);
		}
		if ($interceptor != null) {
			$q = $interceptor($q, $this);
		}
		// dd($q->toSql());
		$data = $q->simplePaginate();
		// dd($data);
		$result = $data->map(function ($item) {
			$values = [];

			foreach ($this->descColumns as $desc) {
				$values[] = $item->{$desc};
			}

			return [
				'id' => $item->{$this->getPrimaryKey()},
				'text' => implode(" - ", $values)
			];
		});
		return [
			'success' => true,
			'results' => $result,
			'pagination' => [
				'more' => $data->hasMorePages(),
				'next_page' => $data->currentPage() + 1,
				'count' => $data->count(),
				'currentPage' => $data->currentPage()
			]
		];
	}

	public function scopeSearchIn($query, $columns, $keyword)
	{
		if ($keyword !== null && $keyword !== '') {
			$columns = $this->generateColumns($columns);


			$whereCondition = function ($query) use ($columns, $keyword) {
				foreach ($columns as $relation => $koloms) {
					if ($relation === '') {
						foreach ($koloms as $kolom) {
							$query->orWhere($kolom['column'], 'LIKE', '%' . $keyword . '%');
						}
					} else {
						$query->orWhereHas($relation, function ($q) use ($koloms, $keyword) {
							$q->where(function ($q) use ($koloms, $keyword) {
								foreach ($koloms as $kolom) {
									$q->orWhere($kolom['column'], 'LIKE', '%' . $keyword . '%');
								}
							});
						});
					}
				}
			};
			return $query->where($whereCondition);
		}
		return $query;
	}

	public function generateSelectForSearch($fields, $exceptRelation = null)
	{
		$except_field = [];
		if ($exceptRelation) {
			$except_field = array_map(function ($item) {
				return $item['field'];
			}, $exceptRelation);
		}
		if (count($fields) > 0) {
			$fields = array_filter($fields, function ($item) use ($except_field) {
				return !in_array($item, $except_field);
			});
			$ar = array_merge(
				array_map(function ($field) {
					return DB::raw($this->table . "." . $field);
				}, $fields),
				[
					DB::raw($this->table . ".created_at")
				]
			);

			return $ar;
		}
		$select = [DB::raw($this->table . "." . $this->primaryKey)];
		foreach ($this->fillable as $field) {
			if (!in_array($field, $this->hidden) && !in_array($field, $except_field)) {
				$select[] = DB::raw($this->table . "." . $field);
			}
		}
		$select[] = DB::raw($this->table . ".created_at");
		return $select;
	}

	public function showWithForeign($id, $selectOnly = null, Closure $queryInterceptor = null)
	{
		if (is_array($id)) {
			$builder = $this->where($this->table . "." . $id['field'], $id['value']);
		} else {
			$builder = $this->where($this->table . "." . $this->primaryKey, $id);
		}

		$relations = $this->getRelationInfo();
		if ($selectOnly) {
			$builder = $builder->select($this->generateSelectForSearch($selectOnly));
		} else {
			$builder = $builder->select(DB::raw($this->table . ".*"));
		}

		foreach ($relations as $col => $arr) {
			$builder->leftJoin($arr[0] . " as " . $arr[0] . "_" . $col, $arr[0] . "_" . $col . "." . $arr[1], "=", $this->table . "." . $col);

			foreach ($arr[2] as $desc) {
				$builder->addSelect(\DB::raw($arr[0] . "_" . $col . "." . $desc . " as " . $col));
				$builder->addSelect(\DB::raw($this->table . "." . $col . " as raw_" . $col));
				break;
			}
		}

		if ($queryInterceptor != null) {
			$builder = $queryInterceptor($builder, $this);
		}
		// return $builder->toSql();
		return $builder->first();
	}

	public function getDataTable($keywords, Closure $queryInterceptor = null)
	{
		$orderBy = array();
		$with_foreign = false;
		\DB::statement(DB::raw('set @no=0'));
		$q = \DB::table($this->table . " as a");

		$q->whereNull('a.deleted_at');

		$q->where(function ($w) use ($keywords, &$orderBy, &$with_foreign) {
			foreach ($keywords as $k => $v) {
				if ($k == "orderBy") {
					$ex_obb = is_array($v) ? $v : explode("#", $v);
					foreach ($ex_obb as $v) {
						$ex_ob = explode(",", $v);
						if (count($ex_ob) == 1)
							$ex_ob[1] = "asc";
						$orderByKey = isset($ex_ob[2]) ? $ex_ob[2] . "." . $ex_ob[0] : "a." . $ex_ob[0];
						$orderBy[$orderByKey] = $ex_ob[1];
					}
				} elseif ($k == "withForeign") {
					$with_foreign = true;
				} else {
					if ($v == "-1" && $k == "induk") {
						$w->whereNull("a." . $k);
					} else {
						$w->where("a." . $k, $v);
					}
				}
			}
		});

		$relations = $this->getRelationInfo();

		$q->select('a.*');
		foreach ($relations as $col => $arr) {
			$q->leftJoin($arr[0] . " as " . $arr[0] . "_" . $col, $arr[0] . "_" . $col . "." . $arr[1], "=", "a." . $col);

			foreach ($arr[2] as $desc) {
				$q->addSelect(\DB::raw($arr[0] . "_" . $col . "." . $desc . " as " . $col));
				$q->addSelect(\DB::raw("a." . $col . " as raw_" . $col));
				break;
			}
		}
		if ($queryInterceptor != null) {
			$q = $queryInterceptor($q, $this);
		}
		if (isset($orderBy)) {
			foreach ($orderBy as $k => $v) {
				$q->orderBy($k, $v);
			}
		}
		$q->addSelect(\DB::raw('@no  := @no  + 1 AS no'));
		return $q->get();
	}
}
