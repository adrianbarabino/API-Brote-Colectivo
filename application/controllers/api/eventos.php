<?php
 
class Api_Fechas_Controller extends Base_Controller {
 
    public $restful = true;
 
    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
            return Response::eloquent(Fecha::all());
        } 
        else
        {
            $fecha = Fecha::find($id);
 
            if(is_null($fecha)){
                return Response::json('Fecha no encontrado', 404);
            } else {
                    return Response::eloquent($fecha);
            }
        }
    }
 
    public function post_index() 
    {
 
        $nuevofecha = Input::json();
 
        $fecha = new Fecha();
        $fecha->titulo = $nuevofecha->titulo;
        $fecha->idbanda = $nuevofecha->idbanda;
        $fecha->tags = $nuevofecha->tags;
        $fecha->contenido = $nuevofecha->contenido;
        $fecha->fecha_inicio = $nuevofecha->fecha_inicio;
        $fecha->fecha_fin = $nuevofecha->fecha_fin;
        $fecha->save();
 
        return Response::eloquent($fecha);
    }
 
    public function put_index() 
    {
        $actualizarfecha = Input::json();
 
        $fecha = Fecha::find($actualizarfecha->id);
        if(is_null($fecha)){
            return Response::json('Fecha no encontrada', 404);
        }
        $fecha->titulo = $actualizarfecha->titulo;
        $fecha->idbanda = $actualizarfecha->idbanda;
        $fecha->tags = $actualizarfecha->tags;
        $fecha->contenido = $actualizarfecha->contenido;
        $fecha->fecha_inicio = $actualizarfecha->fecha_inicio;
        $fecha->fecha_fin = $actualizarfecha->fecha_fin;
        $fecha->save();
        return Response::eloquent($fecha);
    }
 
    public function delete_index($id = null) 
    {
        $fecha = Fecha::find($id);
 
        if(is_null($fecha))
        {
             return Response::json('Fecha no encontrada', 404);
        }
        $fechaeliminada = $fecha;
        $fecha->delete();     
        return Response::eloquent($fechaeliminada);   
    } 
 
}
 
?>