<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\User;
use App\archivosModel;


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
        $guardado_en        = 'ADJUNTOS';
        if(! file_exists( $fullURL ) )
        {
            mkdir( $fullURL );
        }
        #
        if (in_array( $extension , $extensiones) ) {
            # se guarda en storage
            $imagenOriginal = 'Archivo_Origin_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).$extension;
            $archivoNombre  = 'Archivo_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).$extension;
            $imagen400      = 'Archivo_400_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).$extension;
            $imagen40       = 'Archivo_40_'.strtoupper(Str::random(15).'-'.Str::random(15).'-'.Str::random(15)).$extension;
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
                # Borrando imagen original subida... DEBIDO A LA ALTGA CALIDAD Y EL POCO ESPACIO EN EL SERVIDOR.
                # unlink( $fullURL . '/' . $imagenOriginal );
                $sizeBytes = filesize( $fullURL . '/' . $imagenOriginal );
                $dataInsert['url']              = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_400']          = URL_HOME . 'assets/adjunto/' . $imagen400;
                $dataInsert['url_40']           = URL_HOME . 'assets/adjunto/' . $imagen40;
                $dataInsert['nombre_fisico']    = $fullURL.'/'.$imagenOriginal;
            }else{
                $sizeBytes = filesize( $fullURL . '/' . $imagenOriginal );
                $dataInsert['url']              = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_400']          = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['url_40']           = URL_HOME . 'assets/adjunto/' . $imagenOriginal;
                $dataInsert['nombre_fisico']    = $fullURL.'/'.$imagenOriginal;
            }
            #
            $Filesize = $this->formatSizeUnits( $sizeBytes );

            $dataInsert['id_modulo']        = 0;
            $dataInsert['modulo']           = 0;
            $dataInsert['uu_id']            = strtoupper( str_random( 18 ).'-'.str_random( 28 ).'-'.str_random( 18 ) );
            $dataInsert['formulario']       = 'ATENDER_REQ_DINERO';
            $dataInsert['nombre_archivo']   = $request->formData->getClientOriginalName();
            #$dataInsert['nombre_fisico']    = $archivoNombre;
            $dataInsert['size']             = $Filesize;
            $dataInsert['extension']        = $extension;
            $dataInsert['ruta_fisica']      = $fullURL.'/'.$imagenOriginal;
            # $dataInsert['url']              = URL_HOME.$preFolder.'/'.$imagenOriginal;
            # $dataInsert['url_400']          = URL_HOME.$preFolder.'/'.$imagen400;
            # $dataInsert['url_40']           = URL_HOME.$preFolder.'/'.$imagen40;
            $dataInsert['tipo_documento']   = $extension;
            $dataInsert['publico']          = $request['publico'];

            $dataInsert['token']            = $request['token'];
            $dataInsert['correlativo']      = $request['IdReq'];
            $dataInsert['guardado_en']      = $guardado_en;
            $dataInsert['id_usuario']       = Auth::user()->id;
            $dataInsert['usuario']          = Auth::user()->name;
            #
            $dataInsert['id_empresa']       = Auth::user()->id_empresa;
            $dataInsert['empresa']          = Auth::user()->empresa;
            #
            $dataInsertada = adjuntoGoogleModel::create( $dataInsert );
            if( $dataInsertada )
            {
                $response['estado'] = 'OK';
                $response['data'] = $dataInsertada;
                $this->addLog(['act'=>'GUARDAR_REQ_DINERO','g'=>'Agregar archivo adjunto','req'=>$request->all(),'id'=>$request['IdReq']]);
                if( $request['IdReq'] == 0 )
                {
                    # buscamos por token
                    $datilla = adjuntoGoogleModel::where([
                        [ 'token' ,'=', $request['token'] ] , 
                        [ 'formulario' ,'=', 'ARCHIVO_REQ_DINERO' ]
                    ])->get();
                }else{
                    # Buscamos por ID de requerimiento de dinero
                    $datilla = adjuntoGoogleModel::where([
                        [ 'correlativo' ,'=', $request['IdReq'] ] , 
                        [ 'formulario' ,'=', 'ARCHIVO_REQ_DINERO' ]
                    ])->get();
                }
                $response['detalle'] = $this->populateFilesRD( $datilla );
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
}