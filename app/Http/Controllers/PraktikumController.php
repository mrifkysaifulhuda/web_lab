<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Praktikum;
use App\Models\KebutuhanAlat;
use App\Models\KebAlatLab;
use App\Models\KebutuhanBahan;
use App\Models\KebBahanLab;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use App\Models\Alat;
use App\Models\Bahan;
use Illuminate\Support\Facades\Session;

class PraktikumController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new Praktikum;
    }

    private  $type_menu = 'mdpraktikum';

    public function index()
	{
		return view('mdpraktikum/index')->with('type_menu', $this->type_menu);
	}

    public function getListOfMdPraktikum(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model->searchByKeywords(['id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i'); return $formatedDate; })
                ->addColumn('formated_status', function($data){
                    switch ($data->status){
                        case "Draf":
                            $badge = 'badge-warning';
                            break;
                        default:
                            $badge = 'badge-success';
                    }
                    $htmlBadge = '<div class="badge '.$badge.'">'.$data->status.'</div>';
                    return $htmlBadge;
                })
                ->addColumn('action', function($row){
                    $actionBtn ='<a href="mdpraktikum/show/'.$row->id_praktikum. '"class="btn btn-success btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="far fa-file"></i></a>  <a href="#" class="btn btn-primary btn-action mr-1 edit-md" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                    <button class="btn btn-danger trigger--fire-modal-1"
                    onclick="openDeleteMdPraktikum(\''.$row->id_praktikum.'\', \''.$row->nm_praktikum.'\')"; ><i class="fas fa-trash"></i></button> ';
                    return $actionBtn;
                })
                ->rawColumns(['action','formated_status'])
                ->make(true);
        }
    }

    public function store(Request $request) 
    {
    	$data = $request->only(['nm_praktikum', 'keterangan', 'id_praktikum']);
        $data['id_laboratorium'] = Session::get('selected-lab');
        if(empty($data['id_praktikum'])){
            $data["status"] = "Draf";
            $data["id_user"] = 1;
            $this->model->simpanData(null,$data);

        }else{
            $model = $this->model->getDataById($data['id_praktikum']);
            $model->update($data);
        }

        return response()->json(['success'=>'Data Modul Praktikum Berhasil Disimpan.']);
    }

    public function destroy(Request $request)
    {
        $id = $request['id_deleted_praktikum'];
        $model = $this->model->hapusData($id);
        return response()->json(['success'=>'Data Modul Praktikum Berhasil Dihapus.']);

    }

    public function show($id)
    {
        $data = $this->model->getDataById($id);
        if($data['status'] == 'Diajukan'){
            $data['allow_edit'] = false;
        }else{
            $data['allow_edit'] = true; 
        }
        return view('mdpraktikum/detail')->with('data', $data)->with('type_menu', $this->type_menu);
    }

    public function ajukan(Request $request)
    {
        $id_praktikum = $request->input('id_praktikum');

        //$this->insertKebAlatLab($id_praktikum);
        $this->insertKebBahanLab($id_praktikum);
        $model = Praktikum::find($id_praktikum);
        $model->status = 'Diajukan';
        $model->save();

        return to_route('mdpraktikum.show', ['id' => $id_praktikum]);
    }

    private function insertKebAlatLab($id_praktikum)
    {
        $modelKebutuhanAlat = new KebutuhanAlat;
        $modelKebAlatLab = new KebAlatLab;
        
        $year = Carbon::now()->format('Y');
        $data = $modelKebutuhanAlat->searchByKeywords(['id_praktikum' => $id_praktikum, 'withForeign' => true]);
        
        foreach($data as $item){

            $alat = Alat::find($item->raw_id_alat);
            $kebAlatLab = $modelKebAlatLab->searchByKeywords(['id_laboratorium' => Session::get('selected-lab'), 'nm_alat' => $alat->nm_alat,'tahun' => $year,'withForeign' => true]);

            if($kebAlatLab->count() == 0){
                $kal = array();
                $kal['id_laboratorium'] = Session::get('selected-lab');
                $kal['id_alat'] = $alat->id_alat;
                $kal['nm_alat'] = $alat->nm_alat;
                $kal['merek'] = $alat->merek;
                $kal['profil'] = $alat->profil;
                $kal['jumlah_ajuan'] = $item->jumlah_ajuan;
                $kal['praktikum'] = $item->id_praktikum;
                $kal['tahun'] = $year;

                $modelKebAlatLab->simpanData(null,$kal);

            }else{
                $kal = KebAlatLab::Find($kebAlatLab->first()->id);
                $kal->jumlah_ajuan = $kal->jumlah_ajuan + $item->jumlah_ajuan;
                $kal->praktikum = $kal->praktikum.";".$item->id_praktikum;
                $kal->save();
            } 
        }
    }

    private function insertKebBahanLab($id_praktikum)
    {
        $modelKebutuhanBahan = new KebutuhanBahan;
        $modelKebBahanLab = new KebBahanLab;
        
        $year = Carbon::now()->format('Y');
        $data = $modelKebutuhanBahan->searchByKeywords(['id_praktikum' => $id_praktikum, 'withForeign' => true]);

        foreach($data as $item){
            $bahan = Bahan::find($item->raw_id_bahan);
            $kebBahanLab = $modelKebBahanLab->searchByKeywords(['id_laboratorium' => Session::get('selected-lab'), 'id_bahan' => $bahan->id_bahan,'tahun' => $year,'withForeign' => true]);

            if($kebBahanLab->count() == 0){
                $kbl = array();
                $kbl['id_laboratorium'] = Session::get('selected-lab');
                $kbl['id_bahan'] = $bahan->id_bahan;
                $kbl['nm_bahan'] = $bahan->nm_bahan;
                $kbl['satuan'] = $bahan->satuan;
                $kbl['deskripsi'] = $bahan->deskripsi;
                $kbl['jumlah_ajuan'] = $item->jumlah_ajuan;
                $kbl['praktikum'] = $item->id_praktikum;
                $kbl['tahun'] = $year;

                $modelKebBahanLab->simpanData(null,$kbl);

            }else{
                $kbl = KebBahanLab::Find($kebBahanLab->first()->id);
                $kbl->jumlah_ajuan = $kbl->jumlah_ajuan + $item->jumlah_ajuan;
                $kbl->praktikum = $kbl->praktikum.";".$item->id_praktikum;
                $kbl->save();
            } 

        }
    }

}