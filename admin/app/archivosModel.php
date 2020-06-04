<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 


class archivosModel extends Model
{
    use SoftDeletes;
    protected $table = 'orq_archivo_adjunto';
    protected $primaryKey = 'id';
  
    protected $fillable = [ 'uu_id','id_documento','url','ruta_fisica','tipo','peso','token','extension','tipo_documento','nombre_archivo','nombre_fisico','id_usuario','usuario' ];
    
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
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
    # ------------------------------------------------------------
}

