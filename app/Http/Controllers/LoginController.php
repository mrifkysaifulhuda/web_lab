<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\UserLaboratorium;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if(!Auth::validate($credentials)):
            return redirect()->to('login')
                ->withErrors(trans('auth.failed'));
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);
        Auth::login($user);

        $userLaboratorium = new UserLaboratorium;
        $laboratorium = $userLaboratorium->getCombo(['id_user' => $user->id]);

        
        $data = $userLaboratorium->searchByKeywords(['id_user' =>  $user->id, 'withForeign' => true]);

        $arrayLabs = array();

        foreach ($data as $item) {
            $arrayLabs[$item->raw_id_laboratorium] = $item->id_laboratorium;
        }

        Session::put('list-lab',$arrayLabs);
        if (!empty($arrayLabs)) {
            Session::put('selected-lab', array_key_first($arrayLabs));
        }
       
        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user) 
    {
        return redirect()->intended();
    }
}
