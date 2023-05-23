<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Bahan;
use Illuminate\Http\Request;
use App\Models\KebutuhanBahan;
use App\Models\Praktikum;
use DataTables;

class KebutuhanBahanController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new KebutuhanBahan;
    }

    public function getListOfKebBahan($id)
    {
        $data = $this->model->searchByKeywords(['id_praktikum' => $id, 'withForeign' => true]);
       
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('formated_ajuan', function($row){
                $praktikum = Praktikum::find($row->raw_id_praktikum);
                if($praktikum->status == "Draf"){
                    $eip = '<a href="#" class="eip editable editable-click" data-url="'.route('keb-bahan.update_jumlah_ajuan').'" data-name="jumlah_ajuan" data-pk="'.$row->id_kebutuhan_bahan.'">'.$row->jumlah_ajuan.'</a>';
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
                        onclick="openDeleteKebBahan(\''.$row->id_kebutuhan_bahan.'\', \''.$row->id_bahan.'\')"; ><i class="fas fa-trash"></i></button> ';
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
        return response()->json(['success'=>'Data Kebutuhan Bahan Berhasil Disimpan.']);

    }

    public function destroy(Request $request)
    {
        $id = $request['id_deleted_keb_bahan'];
        $model = $this->model->hapusData($id);
        return response()->json(['success'=>'Data Kebutuhan Bahan Berhasil Dihapus.']);

    }

    public function update_jumlah_ajuan(Request $request)
    {
        $model = KebutuhanBahan::find($request->input('pk'));
        $model->jumlah_ajuan = $request->input('value');
        $model->save();
        return response()->json(['success' => true]);
    }
}
