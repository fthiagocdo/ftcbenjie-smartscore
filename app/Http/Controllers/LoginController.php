<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use App\Competition;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        try {
            $competition = Competition::orderBy('id')->first();
            if(isset($competition)) {
                session(['competition_name' => $competition->competition_name]);
            } else {
                session(['competition_name' => null]);
            }
        } catch(Exception $e) {
            Log::error('LoginController.index: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 501)!')
                ->autoClose(10000);
        }

        session(['page' => 'login']);
        return view('login');
    }

    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return redirect()->intended('/');
            } else {
                Alert::error('Erro', 'Email/Senha incorretos.')
                    ->autoClose(10000);
                return redirect()->back();
            }
        } catch(Exception $e) {
            Log::error('LoginController.authenticate: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 502)!')
                ->autoClose(10000);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->intended('/');
    }
}
