<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

 


class auditoriaModel extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_auditoria';
    protected $primaryKey = 'id';
  
    protected $fillable = [ 'uu_id','id_usuario','usuario','modulo','documento','id_documento','glosa','json' ];
    
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

