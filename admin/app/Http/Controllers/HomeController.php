<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;

use DB;
use Validator;
use Redirect;
use Notification;
use carbon;
use Session;
use Auth;
use Exception;


use Str;
use Arr;


class HomeController extends Controller
{
    # ----------------------------------------------------
    public function __construct()
    {
        $session_token = session('session_token');

        if( $session_token == '' )
        {
            session()->put('session_token', Str::random( 64 ) );
        }

        $this->middleware('auth');
    }
    # ----------------------------------------------------
    public function index()
    {
        $data = array();

        $data['vista']      = '';
        $data['subvista']   = '';

        return view( 'home' , compact('data') );
    }
    # ----------------------------------------------------
    public function perfil_usuario( Request $request )
    {
        #
        $data = array();

        $data['vista']      = '';
        $data['subvista']   = '';

        return view( 'perfil_usuario.homePerfilU' , compact('data') );
    }
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
    # ----------------------------------------------------
}
