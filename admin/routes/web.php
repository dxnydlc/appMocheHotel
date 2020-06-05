<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define("URL_HOME", env('APP_URL') );

$fecha      = date('Y-m-d');
$fecha_hora  = date('Y-m-d h:i:s');
$fecha_lat  = date('d/m/Y');# g:i:s a

$nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha ) );
$ayer = date ( 'Y-m-j' , $nuevafecha );
$anio = date ( 'Y' , $nuevafecha );
$mes = date ( 'm' , $nuevafecha );

define( "DATETIME_LOCAL" , date('yyyy-MM-ddThh:mm') );

define( "FECHA_AYER" , $ayer );
define( "FECHA_HOY" , $fecha );
define( "FECHA_HOY_HORA" , date('Y-m-d H:i') );
define( "FECHA_MYSQL" , $fecha );
define( "FECHA_LAT" , $fecha_lat );
define( "ANIO" , $anio );
define( "MES" , $mes );






Route::get('/', function () {
    return view('welcome');
});

Route::get('demo', function () {
    return view('demo.demo');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

# Perfil de usuario
Route::get('perfil/usuario' , 'HomeController@perfil_usuario');


# Adjuntar foto de perfil de usuario
Route::post( 'adjuntar/foto/perfil/usuario' , 'adjuntoController@foto_perfil_usuario' );

##################### AJAX 	#####################

Route::post('update/avatar/usuario' , 'ajaxController@update_avatar_user');











