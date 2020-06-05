<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 


class archivosModel extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_archivos';
    protected $primaryKey = 'id';
  
    protected $fillable = [ 'uu_id','id_documento','url','url_400','url_40','ruta_fisica','tipo','peso','token','extension','tipo_documento','nombre_archivo','nombre_fisico','id_usuario','usuario','formulario','guardado_en' ];
    
    protected $dates = ['deleted_at'];
 
    #public $timestamps = false;
    # ------------------------------------------------------------
    public function getCreatedAtAttribute($valor)
    {
        if( $valor != '' )
        {
            list($fecha,$hora) = explode(' ', $valor );
            list($anio,$mes,$dia) = explode('-', $fecha );
            $fecha_out = $dia.'/'.$mes.'/'.$anio;
            return $fecha_out.' '.$hora;
        }
    }
    # ------------------------------------------------------------
    public function getUpdatedAtAttribute($valor)
    {
        if( $valor != '' )
        {
            list($fecha,$hora) = explode(' ', $valor );
            list($anio,$mes,$dia) = explode('-', $fecha );
            $fecha_out = $dia.'/'.$mes.'/'.$anio;
            return $fecha_out.' '.$hora;
        }
    }
    # ------------------------------------------------------------
    public function setTipoDocumentoAttribute( $value )
    {
        $extension = strtolower( $value );
        $tipo = '';
        switch ( $extension ) {
            case 'pdf':
                $tipo = 'Archivo PDF';
            break;
            case 'txt':
                $tipo = 'Archivo TXT';
            break;
            case 'doc':
                $tipo = 'Archivo Word';
            break;
            case 'docx':
                $tipo = 'Archivo Word';
            break;
            case 'xls':
                $tipo = 'Archivo Excel';
            break;
            case 'xlsx':
                $tipo = 'Archivo Excel';
            break;
            case 'ppt':
                $tipo = 'Presentación power point';
            break;
            case 'pptx':
                $tipo = 'Presentación power point';
            break;
            case 'png':
                $tipo = 'Imágen';
            break;
            case 'jpg':
                $tipo = 'Imágen';
            break;
            case 'jpeg':
                $tipo = 'Imágen';
            break;
            default:
                $tipo = 'Desconocido - ' . $extension;
            break;
        }
        $this->attributes['tipo_documento'] = $tipo;
    }
    /* ------------------------------------------------------------------- */
    public function getTipoDocumentoAttribute( $value )
    {
        $extension = $value;
        $tipo = '';
        switch ( $extension ) {
            case 'pdf':
                $tipo = 'Archivo PDF';
            break;
            case 'txt':
                $tipo = 'Archivo TXT';
            break;
            case 'doc':
                $tipo = 'Archivo Word';
            break;
            case 'docx':
                $tipo = 'Archivo Word';
            break;
            case 'xls':
                $tipo = 'Archivo Excel';
            break;
            case 'xlsx':
                $tipo = 'Archivo Excel';
            break;
            case 'ppt':
                $tipo = 'Presentación power point';
            break;
            case 'pptx':
                $tipo = 'Presentación power point';
            break;
            case 'png':
                $tipo = 'Imágen';
            break;
            case 'jpg':
                $tipo = 'Imágen';
            break;
            case 'jpeg':
                $tipo = 'Imágen';
            break;
            default:
                $tipo = '' . $extension;
            break;
        }
        return $tipo;
    }
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
}

