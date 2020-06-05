<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;
use App\archivosModel;
use App\auditoriaModel;


use DB;
use Validator;
use Redirect;
use Notification;
use carbon;
use Session;
use Auth;
use Exception;
use Storage;
use Image;

use Str;

class adjuntoController extends Controller
{
    /* ----------------------------------------------------------------- */
    public function __construct()
    {
        $session_token = session('session_token');
        if( $session_token == '' )
        {
            session()->put('session_token', str_random( 64 ) );
        }
        $this->middleware('auth');
    }
    # -----------------------------------------------------------------
    public function foto_perfil_usuario( Request $request )
    {
        # Vamos a adjuntar un archivo a la intranet

        $response           = array();
        $response['estado'] = 'ERROR';
        $response['data']   =  array();
        $response['detalle']=  array();
        $session_token      = session('session_token');
        $extension          = $request->formData->getClientOriginalExtension();
        $extension          = strtolower( $extension );
        $preFolder          = 'assets/adjunto';
        $fullURL            = public_path( $preFolder );
        $imagen400          = '';
        $imagen40           = '';
        $exten_imagen = array( 'jpg' , 'png' , 'jpeg' );
        $extensiones = array( 'jpg' , 'png' , 'gif' , 'jpeg' );

        $porcentaje_reduccion = ( 10 / 100 );

        if (in_array( $extension , $exten_imagen) )
        {
            # validar dimension de imagen
            $validator  = Validator::make($request->all(), [ 
                'formData' => 'dimensions:min_width=400,min_height=400'
            ]);

            if ($validator->fails())
            {
                $response['estado'] ='ERROR';
                $response['errores'] =$validator->errors();
                return $response;
            }
        }
  
        # Se guarda en la carpeta STORAGE por no se archivo prioritario
        $guardado_en        = 'ADJUNTO';
        if(! file_exists( $fullURL ) )
        {
            mkdir( $fullURL );
        }
        #
        if (in_array( $extension , $extensiones) ) {
            # se guarda en storage
            $imagenOriginal = 'Archivo_Origin_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).'.'.$extension;
            $archivoNombre  = 'Archivo_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).'.'.$extension;
            $imagen400      = 'Archivo_400_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).'.'.$extension;
            $imagen40       = 'Archivo_40_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).'.'.$extension;
            # #############################################
            $request->formData->move( $fullURL , $imagenOriginal );
            # #############################################
            list($width, $height, $type, $attr) = getimagesize( $fullURL . '/' . $imagenOriginal );
            $response['detalles'] = [ 'width' => $width, 'height' => $height, 'type' => $type, 'attr' => $attr ];
            $width  = intval( $width - ( $width * $porcentaje_reduccion ) );
            $height = intval( $height - ( $height * $porcentaje_reduccion ) );
            $dataInsert = array();
            #
            if (in_array( $extension , $exten_imagen) )
            {
                // Reduciendo al tamaño requerido *********************************
                // open an image file 400
                $img = Image::make( $fullURL . '/' . $imagenOriginal );
                $img->resize( 400 , 400 );
                $img->orientate();
                $img->save( $fullURL . '/' . $imagen400 );
                // open an image file 40
                $img = Image::make( $fullURL . '/' . $imagenOriginal );
                $img->resize( 40 , 40 );
                $img->orientate();
                $img->save( $fullURL . '/' . $imagen40 );
                # Borrando imagen original subida... DEBIDO A LA ALTA CALIDAD Y EL POCO ESPACIO EN 
                $sizeBytes = filesize( $fullURL . '/' . $imagenOriginal );
                $dataInsert['url']              = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_400']          = URL_HOME . 'assets/adjunto/' . $imagen400;
                $dataInsert['url_40']           = URL_HOME . 'assets/adjunto/' . $imagen40;
                $dataInsert['ruta_fisica']      = $fullURL.'/'.$imagenOriginal;
                $dataInsert['nombre_fisico']    = $imagenOriginal;
                
            }else{
                $sizeBytes = filesize( $fullURL . '/' . $imagenOriginal );
                $dataInsert['url']              = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_400']          = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_40']           = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['ruta_fisica']      = $fullURL.'/'.$imagenOriginal;
                $dataInsert['nombre_fisico']    = $imagenOriginal;
                
            }
            #
            $Filesize = $this->formatSizeUnits( $sizeBytes );

            $dataInsert['uu_id']            = strtoupper(Str::random(25).'-'.Str::random(25).'-'.Str::random(25));
            $dataInsert['formulario']       = 'IMAGEN_PERFIL';
            $dataInsert['nombre_archivo']   = $request->formData->getClientOriginalName();
            # 
            $dataInsert['peso']             = $Filesize;
            $dataInsert['extension']        = $extension;
            # 
            $dataInsert['tipo_documento']   = $extension;
            $dataInsert['tipo']   			= $extension;

            $dataInsert['token']            = $request['token'];

            $dataInsert['guardado_en']      = $guardado_en;
            $dataInsert['id_usuario']       = Auth::user()->id;
            $dataInsert['usuario']          = Auth::user()->name;
            #
            $dataInsertada = archivosModel::create( $dataInsert );
            $this->addLog(['act'=>'IMAGEN_PERFIL','g'=>'Foto de perfil','req'=>$request->all(),'id'=>0,'m'=>'FOTO_PERFIL']);
            if( $dataInsertada )
            {
                $response['estado'] = 'OK';
                $response['data'] = $dataInsertada;

                if( $request['IdReq'] == 0 )
                {
                    # buscamos por token
                    $datilla = archivosModel::where([
                        [ 'token' ,'=', $request['token'] ] , 
                        [ 'formulario' ,'=', 'FOTO_PERFIL' ]
                    ])->get();
                }else{
                    # Buscamos por ID de requerimiento de dinero
                    $datilla = archivosModel::where([
                        [ 'correlativo' ,'=', $request['IdReq'] ] , 
                        [ 'formulario' ,'=', 'IMAGEN_PERFIL' ]
                    ])->get();
                }
                $response['detalle'] = $datilla;
            }
            $response['errores'] = '';

        }else{
            $response['errores'] = [ 'tipo' => [ 'Sólo archivos de tipo: ' . implode( ', ' , $extensiones ) ] ];
            return response()->json( $response , 400 );
        }

        return $response;
    }
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    # -----------------------------------------------------------------
    public function del_archivo_adjunto(Request $request)
    {
        # id, uu_id
        $dataEntidad = archivosModel::where([
                        [ 'id' , '=', $request['id'] ] , 
                        [ 'uu_id' , '=' , $request['uu_id'] ]
                    ])->first();
        #
        archivosModel::where([
            [ 'id' , '=', $request['id'] ] , 
            [ 'uu_id' , '=' , $request['uu_id'] ]
        ])->delete();
        return [ 'msg' => 'OK' ];
    }
    # -----------------------------------------------------------------
    function formatSizeUnits( $bytes )
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
    # -----------------------------------------------------------------
    public function addLog( $param = null )
    {
        # act, g, req, id, s
        # f, t, td
        # $this->userRequest
        $formulario = 'REQ_DINERO';
        if( isset($param['f']) ){
            $formulario = $param['f'];
        }
        $dataInsert = array(
            "modulo"      => $param['m'] , 
            "formulario"  => $formulario , 
            "accion"      => $param['act'] , 
            "glosa"       => $param['g'] , 
            "json"        => json_encode( $param['req'] ) , 
            "correlativo" => $param['id'] , 
            "id_user"     => Auth::user()->id , 
            "usuario"     => Auth::user()->name , 
            "id_empresa"  => Auth::user()->id_empresa , 
            "empresa"     => Auth::user()->empresa 
        );
        if (isset( $param['s'] )) {
            $dataInsert['serie'] = $param['s'];
        }
        if (isset( $param['t'] )) {
            $dataInsert['token'] = $param['t'];
        }
        if (isset( $param['td'] )) {
            $dataInsert['tipo_doc'] = $param['td'];
        }
        auditoriaModel::create( $dataInsert );
    }
    # -----------------------------------------------------------------
}