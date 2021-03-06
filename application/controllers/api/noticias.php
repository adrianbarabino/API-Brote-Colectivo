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
        if (is_null($id)) 
        {
        	$noticia = Noticia::skip(null);
            if(Input::get('banda')){
                $id_de_la_banda = Input::get('banda');
                $noticia = $noticia->where('noticias.idbanda', 'REGEXP', "[[:<:]]".$id_de_la_banda."[[:>:]]" );
            }
            if(Input::get('order')){
                $orden = Input::get('order');
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $noticia = $noticia->order_by('noticias.'.$orden, $orden2);
                }else{
                    $noticia = $noticia->order_by($orden, 'desc');
                }  
            }else{
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $noticia = $noticia->order_by('noticias.id', $orden2);
                }


            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $noticia = $noticia->take($limite);

            }
            $noticia = $noticia->get(array('noticias.*'));

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
         	$noticia = Noticia::where('noticias.id', '=', $id)->get(array('noticias.*'));
            if(is_null($noticia)){
                return Response::json('Noticia no encontrado', 404);
            } else {

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