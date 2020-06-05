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


class ajaxController extends Controller
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
    public function update_avatar_user( Request $request )
    {
        //
        $response = array();
        $response['estado'] = 'OK';

        $validator  = Validator::make($request->all(), [
            'avatar' => 'required'
        ]);

        if ($validator->fails())
        {
            $response['estado'] ='ERROR';
            $response['errores'] =$validator->errors();
        }else{
            # Actualizar foto del usuario
            $userC = User::find( Auth::user()->id );
            $userC->fill( $request->all() );
            $userC->save();
        }

        return response()->json( $response , 200 );
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
}