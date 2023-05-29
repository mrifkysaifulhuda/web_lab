<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\RegisterRequest;
use App\Models\UserLaboratorium;
use Illuminate\Support\Facades\Session;
use App\Models\Laboratorium;

class UserController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->model = new User;
    }

    private  $type_menu = 'users';

    public function index()
	{
        
		return view('user/index')->with('type_menu', $this->type_menu)->with('success', Session::get('success'));
	}

    public function show($id)
    {
        $data = $this->model->find($id);
        return view('user/detail')->with('data', $data)->with('type_menu', $this->type_menu);
    }

    public function laboratorium($id)
    {
        $laboratorium = new Laboratorium;

        $data = $this->model->find($id);
        $data['listLab'] = $laboratorium->getCombo();
        return view('user/lab')->with('data', $data)->with('type_menu', $this->type_menu);
    }

    public function getListOfUser(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn ='<a href="user/show/'.$row->id. '"class="btn btn-success btn-action mr-1" data-toggle="tooltip" title="" data-original-title="Edit"><i class="far fa-file"></i></a> 
                    <a href="user/destroy/'.$row->id.'" class="btn btn-danger btn-action" data-toggle="tooltip" title=""  data-original-title="Delete"><i class="fas fa-trash"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('user/form')->with('type_menu', $this->type_menu);
    }

    public function store(RegisterRequest $request) 
    {
        $user = User::create($request->validated());

        return redirect('/user')->with('success', "Account successfully registered.");
    }

    public function destroy($id)
    {
        $user = User::find($id);    
        $user->delete();
        return redirect('/user')->with('success', "Account successfully deleted.");
    }

    public function getListOfUserLab($id)
    {
        $userLabModel = new UserLaboratorium;
        $data = $userLabModel->searchByKeywords(['id_user' => $id, 'withForeign' => true]);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn ='<button class="btn btn-danger trigger--fire-modal-1"
                    onclick="openDeleteLab(\''.$row->id_user_laboratorium.'\')"; ><i class="fas fa-trash"></i></button> ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);

    }

    public function storeLab(Request $request)
    {
        $data = $request->except(['_token']);
        $stokAlatModel = new UserLaboratorium;
        $stokAlatModel->simpanData(null,$data);
        return response()->json(['success'=>'Data Lab Berhasil Disimpan.']);
    }

    public function destroyLab(Request $request)
    {
        $id = $request['id_deleted_lab'];
        $stokAlatModel = new UserLaboratorium;
        $model = $stokAlatModel->hapusData($id);
        return response()->json(['success'=>'Data Lab Berhasil Dihapus.']);

    }

    public function changeLab(Request $request)
    {
        $id = $request['id_selected_lab'];
        Session::put('selected-lab',$id);

        return redirect('/alat');
    }

}