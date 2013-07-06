<?php
 
class Api_Canciones_Controller extends Base_Controller {
 
    public $restful = true;

 


    public function get_index($id = null) 
    {
        require(path('app')."/libraries/getid3/getid3.php");
        if (is_null($id )) 
        {
        	$cancion = Cancion::join('generos', 'canciones.idgenero', '=', 'generos.id')->join('bandas', 'canciones.idbanda', '=', 'bandas.id')->left_join('letras', 'canciones.id', '=', 'letras.idcancion');
            if(Input::get('order')){
                $orden = Input::get('order');
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $cancion = $cancion->order_by('canciones.'.$orden, $orden2);
                }else{
                    $cancion = $cancion->order_by($orden, 'desc');
                }  
            }else{
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $cancion = $cancion->order_by('canciones.id', $orden);
                }


            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $cancion = $cancion->take($limite);

            }
            $cancion = $cancion->get(array('canciones.*', 'generos.nombre as genero', 'bandas.nombre as banda', 'bandas.urltag as banda_urltag', 'letras.id as idletra'));
            for($i=0;$i<count($cancion);$i++){
                $cancion[$i]->attributes['permalink'] = BroteColectivo::permaLink($cancion[$i]->attributes['id']."-".$cancion[$i]->attributes['idbanda'],  "canciones");
	            $cancion[$i]->attributes['duracion'] = BroteColectivo::obtenerDuracion($cancion[$i]->attributes['id']."-".$cancion[$i]->attributes['idbanda']);
	        }

            return Response::eloquent($cancion)->header("Access-Control-Allow-Origin", "*");
        } 
        else
        {
        	$cancion = Cancion::join('generos', 'canciones.idgenero', '=', 'generos.id')->join('bandas', 'canciones.idbanda', '=', 'bandas.id')->left_join('letras', 'canciones.id', '=', 'letras.idcancion')->where('canciones.id', '=', $id)->get(array('canciones.*', 'generos.nombre as genero', 'bandas.nombre as banda', 'bandas.urltag as banda_urltag', 'letras.id as idletra'));
            if(is_null($cancion)){
                return Response::json('Cancion no encontrada', 404)->header("Access-Control-Allow-Origin", "*");
            } else {
            			$cancion_final = new Cancion();
            			foreach ($cancion[0]->attributes as $key => $value)
						{
						    $cancion_final->$key = $value;
						}
						$cancion_no_object = $cancion_final->attributes;
						$cancion_object = new stdClass();
						foreach ($cancion_no_object as $key => $value)
						{
						    $cancion_object->$key = $value;
						}

                        $cancion_object->permalink = BroteColectivo::permaLink($cancion_object->id."-".$cancion_object->idbanda,  "canciones");
            			$cancion_object->duracion = BroteColectivo::obtenerDuracion($cancion_object->id."-".$cancion_object->idbanda);

            			$cancion_final->attributes = get_object_vars($cancion_object);


                    return Response::eloquent($cancion_final)->header("Access-Control-Allow-Origin", "*");
            }

        }
    }
 
 
}
 
?>