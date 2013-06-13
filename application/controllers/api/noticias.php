<?php
 
class Api_Noticias_Controller extends Base_Controller {
    
    public $restful = true;
   
	public function get_index_bandas($id)
	{
			$noticia = Noticia::where('noticias.idbanda', 'REGEXP', "[[:<:]]".$id."[[:>:]]" )->get(array('noticias.*'));
        	for($i=0;$i<count($noticia);$i++){
        		$noticia[$i]->attributes['titulo'] = utf8_decode($noticia[$i]->attributes['titulo']);
        		$noticia[$i]->attributes['idbanda'] = json_decode($noticia[$i]->attributes['idbanda']);
				$noticia[$i]->attributes['contenido_corto'] = BroteColectivo::cortar_contenido($noticia[$i]->attributes['contenido']);
				$noticia[$i]->attributes['contenido'] = BroteColectivo::limpiar_noticia($noticia[$i]->attributes['contenido']);
        		if(Input::get('corto')){
					$noticia[$i]->attributes['contenido'] = "";
        		}
				$noticia[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($noticia[$i]->attributes['idbanda']);
				$noticia[$i]->attributes['fecha_corta'] = date("d/m/Y",$noticia[$i]->attributes['fecha']);
  				$noticia[$i]->attributes['fecha_js'] =  $noticia_inicio_corta = date('D, d M y H:i:s',$noticia[$i]->attributes['fecha'])." -3000";
        	}

        	$noticia_final = $noticia;
            return Response::eloquent($noticia_final)->header("Access-Control-Allow-Origin", "*");

	}

    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
        	$noticia = Noticia::get(array('noticias.*'));
            if(Input::get('banda')){
                $id_de_la_banda = Input::get('banda');
                $noticia = $noticia->where('noticias.idbanda', 'REGEXP', "[[:<:]]".$id_de_la_banda."[[:>:]]" );
            }
            if(Input::get('order')){
                $orden = Input::get('order');
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $noticia = $noticia->order_by($orden, $orden2);
                }else{
                    $noticia = $noticia->order_by($orden, 'desc');
                }  
            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $noticia = $noticia->take($limite);

            }
        	for($i=0;$i<count($noticia);$i++){
                $noticia[$i]->attributes['titulo'] = utf8_decode($noticia[$i]->attributes['titulo']);
        		$noticia[$i]->attributes['tags'] = utf8_decode($noticia[$i]->attributes['tags']);
        		$noticia[$i]->attributes['idbanda'] = json_decode($noticia[$i]->attributes['idbanda']);
				$noticia[$i]->attributes['contenido_corto'] = BroteColectivo::cortar_contenido($noticia[$i]->attributes['contenido']);
				$noticia[$i]->attributes['contenido'] = BroteColectivo::limpiar_noticia($noticia[$i]->attributes['contenido']);
        		if(Input::get('corto')){
					$noticia[$i]->attributes['contenido'] = "";
        		}
				$noticia[$i]->attributes['bandas'] = BroteColectivo::obtenerBandas($noticia[$i]->attributes['idbanda']);
				$noticia[$i]->attributes['fecha_corta'] =  $noticia_inicio_corta = date("d/m/Y",$noticia[$i]->attributes['fecha']);
				$noticia[$i]->attributes['fecha_js'] =  $noticia_inicio_corta = date('D, d M y H:i:s',$noticia[$i]->attributes['fecha'])." -3000";
        	}

        	$noticia_final = $noticia;
            return Response::eloquent($noticia_final)->header("Access-Control-Allow-Origin", "*");
        } 
        else
        {
         	$noticia = Noticia::get(array('noticias.*'));
            if(is_null($noticia)){
                return Response::json('Noticia no encontrado', 404);
            } else {
            			$noticia_final = new Noticia();
            			foreach ($noticia[0]->attributes as $key => $value)
						{
						    $noticia_final->$key = $value;
						}
						$noticia_no_object = $noticia_final->attributes;
						$noticia_object = new stdClass();
						foreach ($noticia_no_object as $key => $value)
						{
						    $noticia_object->$key = $value;
						}

        				$noticia_object->titulo = utf8_decode($noticia_object->titulo);
            			$noticia_object->id = $noticia_object->id;
            			$noticia_object->idbanda = json_decode($noticia_object->idbanda);
        				$noticia_object->bandas = BroteColectivo::obtenerBandas($noticia_object->idbanda);
            			$noticia_object->urltag = $noticia_object->urltag;
            			$noticia_object->tags = $noticia_object->tags;
            			$noticia_object->contenido_corto = BroteColectivo::cortar_contenido($noticia_object->contenido);
            			$noticia_object->contenido = BroteColectivo::limpiar_noticia($noticia_object->contenido);
            			$noticia_object->fecha = $noticia_object->fecha;
            			$noticia_object->fecha_corta = date("d/m/Y",$noticia_object->fecha);
            			$noticia_object->fecha_js = date('D, d M y H:i:s',$noticia_object->fecha)." -3000";
            			$noticia_final->attributes = get_object_vars($noticia_object);


                    return Response::eloquent($noticia_final)->header("Access-Control-Allow-Origin", "*");
            }
        }
    }
 
    // public function post_index() 
    // {
 
    //     $nuevonoticia = Input::json();
 
    //     $noticia = new Noticia();
    //     $noticia->titulo = $nuevonoticia->titulo;
    //     $noticia->idbanda = $nuevonoticia->idbanda;
    //     $noticia->idlugar = $nuevonoticia->idlugar;
    //     $noticia->tags = $nuevonoticia->tags;
    //     $noticia->contenido = $nuevonoticia->contenido;
    //     $noticia->noticia_inicio = $nuevonoticia->noticia_inicio;
    //     $noticia->noticia_fin = $nuevonoticia->noticia_fin;
    //     $noticia->save();
 
    //     return Response::eloquent($noticia);
    // }
 
    // public function put_index() 
    // {
    //     $actualizarnoticia = Input::json();
 
    //     $noticia = Noticia::find($actualizarnoticia->id);
    //     if(is_null($noticia)){
    //         return Response::json('Noticia no encontrada', 404);
    //     }
    //     $noticia->titulo = $actualizarnoticia->titulo;
    //     $noticia->idbanda = $actualizarnoticia->idbanda;
    //     $noticia->idlugar = $actualizarnoticia->idlugar;
    //     $noticia->tags = $actualizarnoticia->tags;
    //     $noticia->contenido = $actualizarnoticia->contenido;
    //     $noticia->noticia_inicio = $actualizarnoticia->noticia_inicio;
    //     $noticia->noticia_fin = $actualizarnoticia->noticia_fin;
    //     $noticia->save();
    //     return Response::eloquent($noticia);
    // }
 
    // public function delete_index($id = null) 
    // {
    //     $noticia = Noticia::find($id);
 
    //     if(is_null($noticia))
    //     {
    //          return Response::json('Noticia no encontrada', 404);
    //     }
    //     $noticiaeliminada = $noticia;
    //     $noticia->delete();     
    //     return Response::eloquent($noticiaeliminada);   
    // } 
 
}
 
?>