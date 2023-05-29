<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Bahan;
use App\Models\StokBahan;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BahanController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new Bahan;
    }

    public function index()
	{
		return view('bahan/index')->with('type_menu', 'bahan');
	}

    public function create()
    {
        return view('bahan/form')->with('type_menu', 'bahan');
    }

    public function store (Request $request)
    {
        $data = $request->except(['_token']);
        $data['id_laboratorium'] = Session::get('selected-lab');
        $this->model->simpanData(null,$data);
        return redirect('/bahan');
    }

    public function show($id)
    {
        $data = $this->model->getDataById($id);
        return view('bahan/detail')->with('data', $data)->with('type_menu', 'bahan');
    }

    public function stok($id)
    {
        $data = $this->model->getDataById($id);
        return view('bahan/stok')->with('data', $data)->with('type_menu', 'bahan');
    }


    public function edit($id)
    {
        $data = $this->model->getDataById($id);
        return view('bahan/form')->with('data', $data)->with('type_menu', 'bahan');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token']);
        $model = $this->model->getDataById($id);
        $model->update($data);
        
        return redirect('/bahan');
    }

    public function destroy($id)
    {
        $model = $this->model->hapusData($id);
        return redirect('/bahan');
    }

    public function getListOfBahan(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model->searchByKeywords(['id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn ='<a href="bahan/show/'.$row->id_bahan. '"class="btn btn-success btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="far fa-file"></i></a><a href="bahan/edit/'.$row->id_bahan. '"class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a>
                    <a href="bahan/destroy/'.$row->id_bahan.'" class="btn btn-danger btn-action" data-toggle="tooltip" title=""  data-original-title="Delete"><i class="fas fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getComboBahan(Request $request)
    {
        $q = $request->q;
        $data = $this->model->getCombo(['nm_bahan'=> $q, 'id_laboratorium' => Session::get('selected-lab')]);
        $result = array();
        foreach($data as $key => $val){
            array_push($result, array('id' => $key, 'text' => $val));
        }
        return response()->json($result);
    }

    public function storeStok(Request $request)
    {
        $data = $request->except(['_token']);
        $stokBahanModel = new StokBahan;
        $stokBahanModel->simpanData(null,$data);
        return response()->json(['success'=>'Data Stok Bahan Berhasil Disimpan.']);
    }

    public function getListOfStok($id)
    {
        
        $statement = DB::statement("set @TamSum := 0");
        $statement = DB::statement("set @KurSum := 0");
        $data = DB::select("select sb.id_stok_bahan, sb.created_at, sb.jumlah, tipe, sb.keterangan,
        if(tipe= 'penambahan',@TamSum := @TamSum + jumlah,@TamSum) as total_penambahan,
        if(tipe= 'pengurangan',@KurSum := @KurSum + jumlah,@KurSum) as total_pengurangan,
        CONCAT(@TamSum - @KurSum , ' ', b.satuan) as stok_akhir
        from `stok_bahan` sb left join `bahan` b on sb.id_bahan = b.id_bahan   where sb.id_bahan = ? and sb.deleted_at is null",[$id]);
       
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
                onclick="openDeleteStok(\''.$row->id_stok_bahan.'\')"; ><i class="fas fa-trash"></i></button> ';
                return $actionBtn;
            })
            ->rawColumns(['action','penambahan', 'pengurangan'])
            ->make(true);
        
    }

    public function destroyStok(Request $request)
    {
        $id = $request['id_deleted_stok'];
        $stokBahanModel = new StokBahan;
        $model = $stokBahanModel->hapusData($id);
        return response()->json(['success'=>'Data Stok Berhasil Dihapus.']);

    }
}