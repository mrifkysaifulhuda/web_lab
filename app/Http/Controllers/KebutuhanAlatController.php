<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Alat;
use Illuminate\Http\Request;
use App\Models\KebutuhanAlat;
use App\Models\Praktikum;
use DataTables;

class KebutuhanAlatController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new KebutuhanAlat;
    }

    public function getListOfKebAlat($id)
    {
        $data = $this->model->searchByKeywords(['id_praktikum' => $id, 'withForeign' => true]);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('formated_ajuan', function($row){
                $praktikum = Praktikum::find($row->raw_id_praktikum);
                if($praktikum->status == "Draf"){
                    $eip = '<a href="#" class="eip editable editable-click" data-name="jumlah_ajuan" data-url="'.route('keb-alat.update_jumlah_ajuan').'" data-pk="'.$row->id_kebutuhan_alat.'">'.$row->jumlah_ajuan.'</a>';
                }else{
                    $eip = $row->jumlah_ajuan;
                }
                
                return $eip;
            })
            ->addColumn('action', function($row){                
                $praktikum = Praktikum::find($row->raw_id_praktikum);
                if($praktikum->status == "Draf"){
                    $actionBtn ='
                    <button class="btn btn-danger trigger--fire-modal-1"
                        onclick="openDeleteKebAlat(\''.$row->id_kebutuhan_alat.'\', \''.$row->id_alat.'\')"; ><i class="fas fa-trash"></i></button> ';
                }else{
                    $actionBtn ='';
                }
                
                return $actionBtn;
            })
            ->rawColumns(['formated_ajuan','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $this->model->simpanData(null,$data);
        return response()->json(['success'=>'Data Kebutuhan Alat Berhasil Disimpan.']);

    }

    public function destroy(Request $request)
    {
        $id = $request['id_deleted_keb_alat'];
        $model = $this->model->hapusData($id);
        return response()->json(['success'=>'Data Kebutuhan Alat Berhasil Dihapus.']);

    }

    public function update_jumlah_ajuan(Request $request)
    {
        $model = KebutuhanAlat::find($request->input('pk'));
        $model->jumlah_ajuan = $request->input('value');
        $model->save();
        return response()->json(['success' => true]);
    }
}
