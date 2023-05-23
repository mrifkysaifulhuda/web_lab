<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Alat;
use Illuminate\Http\Request;
use App\Models\KebAlatLab;
use App\Models\KebBahanLab;
use App\Models\Praktikum;
use DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Exports\KebutuhanExport;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class LaboratoriumController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new KebAlatLab;
    }

    private  $type_menu = 'lab';

    public function index(Request $request)
	{
        $request->query('tahun');
        $data = array();
        if( $request->query('tahun') == null){
            $data['tahun'] = 2023;
        }else{
            $data['tahun'] =  $request->query('tahun');
        }
       
        $dataKebLab = $this->model->searchByKeywords(['tahun' => $data['tahun'], 'id_laboratorium' => Session::get('selected-lab'), 'status' => 'Disetujui' , 'withForeign' => true]);
       
        if($dataKebLab->count() > 0){
            $data['status'] = 'Disetujui';
        }
        
		return view('lab/index')->with('data', $data)->with('type_menu', $this->type_menu);
	}

    public function getListOfKebAlatLab($tahun)
    {
        $data = $this->model->searchByKeywords(['tahun' => $tahun, 'id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('formated_ajuan', function($row){
               
                if($row->status != "Disetujui" & auth()->user()->role == "kepala laboratorium"){
                    $eip = '<a href="#" class="eip editable editable-click" data-name="jumlah_ajuan" data-url="'.route('keb-alat-lab.update_jumlah_ajuan_alat').'" data-pk="'.$row->id.'">'.$row->jumlah_ajuan.'</a>';
                }else{
                    $eip = $row->jumlah_ajuan;
                }
                
                return $eip;
            })
            ->addColumn('stok', function($row){
                $statement = DB::statement("set @TamSum := 0");
                $statement = DB::statement("set @KurSum := 0");
                $data = DB::select("select stok.stok_akhir from  ( select id_stok_alat, created_at, jumlah, tipe, keterangan,
                if(tipe= 'penambahan',@TamSum := @TamSum + jumlah,@TamSum) as total_penambahan,
                if(tipe= 'pengurangan',@KurSum := @KurSum + jumlah,@KurSum) as total_pengurangan,
                @TamSum - @KurSum as stok_akhir
                from `stok_alat` where id_alat = ? and deleted_at is null ) as stok order by created_at desc limit 1",[$row->id_alat]);
                
                if(count($data) > 0){
                    $stok = $data[0]->stok_akhir;
                }else{
                    $stok = 0;
                }
                
                return $stok;
            })
            ->rawColumns(['formated_ajuan'])
            ->make(true);
    }

    public function getListOfKebBahanLab($tahun)
    {
        $modelKebBahanLab = new KebBahanLab;
        $data = $modelKebBahanLab->searchByKeywords(['tahun' => $tahun, 'id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('formated_ajuan', function($row){
               
                if($row->status != "Disetujui" & auth()->user()->role == "kepala laboratorium" ){
                    $eip = '<a href="#" class="eip editable editable-click" data-name="jumlah_ajuan" data-url="'.route('keb-alat-lab.update_jumlah_ajuan_bahan').'" data-pk="'.$row->id.'">'.$row->jumlah_ajuan.'</a>';
                }else{
                    $eip = $row->jumlah_ajuan;
                }
                
                return $eip;
            })
            ->addColumn('stok', function($row){
                $statement = DB::statement("set @TamSum := 0");
                $statement = DB::statement("set @KurSum := 0");
                $data = DB::select("select stok.stok_akhir from  ( select sb.id_stok_bahan, sb.created_at, sb.jumlah, tipe, sb.keterangan,
                if(tipe= 'penambahan',@TamSum := @TamSum + jumlah,@TamSum) as total_penambahan,
                if(tipe= 'pengurangan',@KurSum := @KurSum + jumlah,@KurSum) as total_pengurangan,
                CONCAT(@TamSum - @KurSum , ' ', b.satuan) as stok_akhir
                from `stok_bahan` sb left join `bahan` b on sb.id_bahan = b.id_bahan   where sb.id_bahan = ? and sb.deleted_at is null ) as stok order by created_at desc limit 1",[$row->id_bahan]);
                
                if(count($data) > 0){
                    $stok = $data[0]->stok_akhir;
                }else{
                    $stok = 0;
                }
                
                return $stok;
            })
            ->rawColumns(['formated_ajuan'])
            ->make(true);
    }

    public function update_jumlah_ajuan_alat(Request $request)
    {
        $model = KebAlatLab::find($request->input('pk'));
        $model->jumlah_ajuan = $request->input('value');
        $model->save();
        return response()->json(['success' => true]);
    }

    public function update_jumlah_ajuan_bahan(Request $request)
    {
        $model = KebBahanLab::find($request->input('pk'));
        $model->jumlah_ajuan = $request->input('value');
        $model->save();
        return response()->json(['success' => true]);
    }

    public function setujui(Request $request)
    {
        $data = $this->model->searchByKeywords(['tahun' => $request->input('selected_year'), 'id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
        
        foreach($data as $item){
            $kal = KebAlatLab::Find($item->id);
            $kal->status = "Disetujui";
            $kal->save();
        }

        $modelKebBahanLab = new KebBahanLab;
        $dataBahan = $modelKebBahanLab->searchByKeywords(['tahun' => $request->input('selected_year'), 'id_laboratorium' => Session::get('selected-lab'), 'withForeign' => true]);
        
        foreach($dataBahan as $item){
            $kbl = KebBahanLab::Find($item->id);
            $kbl->status = "Disetujui";
            $kbl->save();
        }


        return redirect('/lab');
    }

    public function Export(Request $request)
    {
        $tahun = $request->query('selected_year');
       
        return (new InvoicesExport($tahun))->download('invoices.xlsx');
    }

}