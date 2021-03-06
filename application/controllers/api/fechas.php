<?php
 
class Api_Fechas_Controller extends Base_Controller {
    
    public $restful = true;
   
	public function get_index_bandas($id)
	{
			$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id')->where('fechas.idbanda', 'REGEXP', "[[:<:]]".$id."[[:>:]]" )->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
        	for($i=0;$i<count($fecha);$i++){
        		$fecha[$i]->attributes['lugar'] = utf8_decode($fecha[$i]->attributes['nombre']);
        		$fecha[$i]->attributes['titulo'] = utf8_decode($fecha[$i]->attributes['titulo']);
        		$fecha[$i]->attributes['idbanda'] = json_decode($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['contenido'] = BroteColectivo::limpiar_cadena($fecha[$i]->attributes['contenido']);
				$fecha[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['ciudad'] = BroteColectivo::obtenerCiudad($fecha[$i]->attributes['coordenadas']);
				$fecha[$i]->attributes['fecha_corta'] =  $fecha_inicio_corta = date("d/m",strtotime($fecha[$i]->attributes['fecha_inicio']));
        	}

        	$fecha_final = $fecha;
            return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");

	}
 	public function get_index_nuevas() 
 	{
			$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id')->where('fechas.fecha_fin', '>', date("Y-m-d H:i:s") )->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
        	for($i=0;$i<count($fecha);$i++){
        		
        		$fecha[$i]->attributes['lugar'] = utf8_decode($fecha[$i]->attributes['nombre']);
        		$fecha[$i]->attributes['idbanda'] = json_decode($fecha[$i]->attributes['idbanda']);
        		$fecha[$i]->attributes['titulo'] = utf8_decode($fecha[$i]->attributes['titulo']);
				$fecha[$i]->attributes['contenido'] = BroteColectivo::limpiar_cadena($fecha[$i]->attributes['contenido']);
				$fecha[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['ciudad'] = BroteColectivo::obtenerCiudad($fecha[$i]->attributes['coordenadas']);
				$fecha[$i]->attributes['fecha_corta'] =  $fecha_inicio_corta = date("d/m",strtotime($fecha[$i]->attributes['fecha_inicio']));
        	}

        	$fecha_final = $fecha;
            return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");

 	}
 	public function get_index_interior() 
 	{
			$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id')->where('lugares.interior', '=', '1' )->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
        	for($i=0;$i<count($fecha);$i++){
        		$fecha[$i]->attributes['lugar'] = utf8_decode($fecha[$i]->attributes['nombre']);
        		$fecha[$i]->attributes['titulo'] = utf8_decode($fecha[$i]->attributes['titulo']);
        		$fecha[$i]->attributes['idbanda'] = json_decode($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['contenido'] = BroteColectivo::limpiar_cadena($fecha[$i]->attributes['contenido']);
				$fecha[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['ciudad'] = BroteColectivo::obtenerCiudad($fecha[$i]->attributes['coordenadas']);
				$fecha[$i]->attributes['fecha_corta'] =  $fecha_inicio_corta = date("d/m",strtotime($fecha[$i]->attributes['fecha_inicio']));
        	}

        	$fecha_final = $fecha;
            return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");
 	}
 	public function get_index_gallegos() 
 	{
			$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id')->where('lugares.interior', '=', '0' )->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
        	for($i=0;$i<count($fecha);$i++){
        		$fecha[$i]->attributes['lugar'] = utf8_decode($fecha[$i]->attributes['nombre']);
        		$fecha[$i]->attributes['titulo'] = utf8_decode($fecha[$i]->attributes['titulo']);
        		$fecha[$i]->attributes['idbanda'] = json_decode($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['contenido'] = BroteColectivo::limpiar_cadena($fecha[$i]->attributes['contenido']);
				$fecha[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['ciudad'] = BroteColectivo::obtenerCiudad($fecha[$i]->attributes['coordenadas']);
				$fecha[$i]->attributes['fecha_corta'] =  $fecha_inicio_corta = date("d/m",strtotime($fecha[$i]->attributes['fecha_inicio']));
        	}

        	$fecha_final = $fecha;
            return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");

 	}
    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
        	$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id');
            if(Input::get('banda')){
                $id_de_la_banda = Input::get('banda');
                $fecha = $fecha->where('fechas.idbanda', 'REGEXP', "[[:<:]]".$id_de_la_banda."[[:>:]]" );
            }
            if(Input::get('order')){
                $orden = Input::get('order');
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $fecha = $fecha->order_by($orden, $orden2);
                }else{
                    $fecha = $fecha->order_by($orden, 'desc');
                }  
            }
            if(Input::get('lugar')){
                $id_del_lugar = Input::get('lugar');
                $fecha = $fecha->where('fechas.idlugar', '=', $id_del_lugar );
           
            }
            if(Input::get('nuevas')){
                $fecha = $fecha->where('fechas.fecha_fin', '>', date("Y-m-d H:i:s") );
            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $fecha = $fecha->take($limite);

            }
            $fecha = $fecha->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
        	for($i=0;$i<count($fecha);$i++){
                $fecha[$i]->attributes['titulo'] = utf8_decode($fecha[$i]->attributes['titulo']);
        		$fecha[$i]->attributes['lugar'] = utf8_decode($fecha[$i]->attributes['nombre']);
                $fecha[$i]->attributes['contenido_corto'] = BroteColectivo::cortar_contenido($fecha[$i]->attributes['contenido'], 60);
                $fecha[$i]->attributes['idbanda'] = json_decode($fecha[$i]->attributes['idbanda']);
                $fecha[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($fecha[$i]->attributes['idbanda']);
				$fecha[$i]->attributes['fecha_corta'] =  $fecha_inicio_corta = date("d/m",strtotime($fecha[$i]->attributes['fecha_inicio']));
                            if(!Input::get('formato_corto')){
                $fecha[$i]->attributes['contenido'] = BroteColectivo::limpiar_cadena($fecha[$i]->attributes['contenido']);
                $fecha[$i]->attributes['ciudad'] = BroteColectivo::obtenerCiudad($fecha[$i]->attributes['coordenadas']);

                }else{
                    unset($fecha[$i]->attributes['idbanda']);
                    unset($fecha[$i]->attributes['coordenadas']);
                    unset($fecha[$i]->attributes['direccion']);
                    unset($fecha[$i]->attributes['fecha_fin']);
                    unset($fecha[$i]->attributes['interior']);
                    unset($fecha[$i]->attributes['tags']);
                    unset($fecha[$i]->attributes['idlugar']);
                    unset($fecha[$i]->attributes['url_tag']);
                    unset($fecha[$i]->attributes['fecha_corta']);
                    unset($fecha[$i]->attributes['contenido_corto']);
                    unset($fecha[$i]->attributes['contenido']);
                    $fecha[$i]->attributes['dia'] = date("d",strtotime($fecha[$i]->attributes['fecha_inicio']));
                    $fecha[$i]->attributes['mes'] = BroteColectivo::obtenerMes(date("n",strtotime($fecha[$i]->attributes['fecha_inicio'])));
                    $fecha[$i]->attributes['anio'] = date("Y",strtotime($fecha[$i]->attributes['fecha_inicio']));
                    $fecha[$i]->attributes['hora'] = date("H:i",strtotime($fecha[$i]->attributes['fecha_inicio']));
                    unset($fecha[$i]->attributes['fecha_inicio']);
                }

        	}

        	$fecha_final = $fecha;
            return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");
        } 
        else
        {
         	$fecha = Fecha::join('lugares', 'fechas.idlugar', '=', 'lugares.id')->where('fechas.id', '=', $id)->get(array('fechas.*', 'lugares.coordenadas', 'lugares.url_tag', 'lugares.direccion', 'lugares.nombre', 'lugares.interior'));
            if(is_null($fecha)){
                return Response::json('Fecha no encontrado', 404);
            } else {
            			$fecha_final = new Fecha();
            			foreach ($fecha[0]->attributes as $key => $value)
						{
						    $fecha_final->$key = $value;
						}
						$fecha_no_object = $fecha_final->attributes;
						$fecha_object = new stdClass();
						foreach ($fecha_no_object as $key => $value)
						{
						    $fecha_object->$key = $value;
						}

        				$fecha_object->titulo = utf8_decode($fecha_object->titulo);
        				$fecha_object->lugar = utf8_decode($fecha_object->nombre);
            			$fecha_object->id = $fecha_object->id;
            			$fecha_object->idbanda = json_decode($fecha_object->idbanda);
            			$fecha_object->ciudad = BroteColectivo::obtenerCiudad($fecha_object->coordenadas);
        				$fecha_object->bandas = BroteColectivo::obtenerBandas($fecha_object->idbanda);
            			$fecha_object->titulo = $fecha_object->titulo;
            			$fecha_object->urltag = $fecha_object->urltag;
            			$fecha_object->tags = $fecha_object->tags;
                        $fecha_object->contenido = BroteColectivo::limpiar_cadena($fecha_object->contenido);
            			$fecha_object->contenido_corto = BroteColectivo::cortar_contenido($fecha_object->contenido,60);
            			$fecha_object->fecha_inicio = $fecha_object->fecha_inicio;
            			$fecha_inicio_corta = date("d/m",strtotime($fecha_object->fecha_inicio));
            			$fecha_object->fecha_corta = $fecha_inicio_corta;
            			$fecha_object->fecha_fin = $fecha_object->fecha_fin;
            			$fecha_final->attributes = get_object_vars($fecha_object);


                    return Response::eloquent($fecha_final)->header("Access-Control-Allow-Origin", "*");
            }
        }
    }
 
    // public function post_index() 
    // {
 
    //     $nuevofecha = Input::json();
 
    //     $fecha = new Fecha();
    //     $fecha->titulo = $nuevofecha->titulo;
    //     $fecha->idbanda = $nuevofecha->idbanda;
    //     $fecha->idlugar = $nuevofecha->idlugar;
    //     $fecha->tags = $nuevofecha->tags;
    //     $fecha->contenido = $nuevofecha->contenido;
    //     $fecha->fecha_inicio = $nuevofecha->fecha_inicio;
    //     $fecha->fecha_fin = $nuevofecha->fecha_fin;
    //     $fecha->save();
 
    //     return Response::eloquent($fecha);
    // }
 
    // public function put_index() 
    // {
    //     $actualizarfecha = Input::json();
 
    //     $fecha = Fecha::find($actualizarfecha->id);
    //     if(is_null($fecha)){
    //         return Response::json('Fecha no encontrada', 404);
    //     }
    //     $fecha->titulo = $actualizarfecha->titulo;
    //     $fecha->idbanda = $actualizarfecha->idbanda;
    //     $fecha->idlugar = $actualizarfecha->idlugar;
    //     $fecha->tags = $actualizarfecha->tags;
    //     $fecha->contenido = $actualizarfecha->contenido;
    //     $fecha->fecha_inicio = $actualizarfecha->fecha_inicio;
    //     $fecha->fecha_fin = $actualizarfecha->fecha_fin;
    //     $fecha->save();
    //     return Response::eloquent($fecha);
    // }
 
    // public function delete_index($id = null) 
    // {
    //     $fecha = Fecha::find($id);
 
    //     if(is_null($fecha))
    //     {
    //          return Response::json('Fecha no encontrada', 404);
    //     }
    //     $fechaeliminada = $fecha;
    //     $fecha->delete();     
    //     return Response::eloquent($fechaeliminada);   
    // } 
 
}
 
?>