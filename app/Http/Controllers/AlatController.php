<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Alat;
use App\Models\StokAlat;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AlatController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new Alat;
    }

    public function index()
	{
		return view('alat/index')->with('type_menu', 'alat');
	}

    public function create()
    {
        return view('alat/form')->with('type_menu', 'alat');
    }

    public function store (Request $request)
    {
        $data = $request->except(['_token']);
        $data['id_laboratorium'] = Session::get('selected-lab');
        $this->model->simpanData(null,$data);
        return redirect('/alat');
    }

    public function edit($id)
    {
        $data = $this->model->getDataById($id);
        return view('alat/form')->with('data', $data)->with('type_menu', 'alat');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);
        $model = $this->model->getDataById($id);
        $model->update($data);
        
        return redirect('/alat');
    }

    public function destroy($id)
    {
        $model = $this->model->hapusData($id);
        return redirect('/alat');
    }

    public function show($id)
    {
        $data = $this->model->getDataById($id);
        return view('alat/detail')->with('data', $data)->with('type_menu', 'alat');
    }

    public function stok($id)
    {
        $data = $this->model->getDataById($id);
        return view('alat/stok')->with('data', $data)->with('type_menu', 'alat');
    }

    public function getListOfAlat(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model->searchByKeywords(['id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn ='<a href="alat/show/'.$row->id_alat. '"class="btn btn-success btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="far fa-file"></i></a> <a href="alat/edit/'.$row->id_alat. '"class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="alat/destroy/'.$row->id_alat.'" class="btn btn-danger btn-action" data-toggle="tooltip" title=""  data-original-title="Delete"><i class="fas fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getComboAlat(Request $request)
    {
        $q = $request->q;
        $data = $this->model->getCombo($q);
        $result = array();
        foreach($data as $key => $val){
            array_push($result, array('id' => $key, 'text' => $val));
        }
        return response()->json($result);
    }

    public function storeStok(Request $request)
    {
        $data = $request->except(['_token']);
        $stokAlatModel = new StokAlat;
        $stokAlatModel->simpanData(null,$data);
        return response()->json(['success'=>'Data Stok Alat Berhasil Disimpan.']);
    }

    public function getListOfStok($id)
    {
        
        $statement = DB::statement("set @TamSum := 0");
        $statement = DB::statement("set @KurSum := 0");
        $data = DB::select("select id_stok_alat, created_at, jumlah, tipe, keterangan,
        if(tipe= 'penambahan',@TamSum := @TamSum + jumlah,@TamSum) as total_penambahan,
        if(tipe= 'pengurangan',@KurSum := @KurSum + jumlah,@KurSum) as total_pengurangan,
        @TamSum - @KurSum as stok_akhir
        from `stok_alat` where id_alat = ? and deleted_at is null",[$id]);
        //$data = StokAlat::orderBy('created_at', 'ASC')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function($data){ $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i'); return $formatedDate; })
            ->addColumn('penambahan', function($row){
                if($row->tipe == "penambahan"){
                    return $row->jumlah;
                }else{
                    return '-';
                }
            })
            ->addColumn('pengurangan', function($row){
                if($row->tipe == "pengurangan"){
                    return $row->jumlah;
                }else{
                    return '-';
                }
            })
            ->addColumn('action', function($row){
                $actionBtn ='<button class="btn btn-danger trigger--fire-modal-1"
                onclick="openDeleteStok(\''.$row->id_stok_alat.'\')"; ><i class="fas fa-trash"></i></button> ';
                return $actionBtn;
            })
            ->rawColumns(['action','penambahan', 'pengurangan'])
            ->make(true);
        
    }

    public function destroyStok(Request $request)
    {
        $id = $request['id_deleted_stok'];
        $stokAlatModel = new StokAlat;
        $model = $stokAlatModel->hapusData($id);
        return response()->json(['success'=>'Data Bahan Berhasil Dihapus.']);

    }
}