<?php
 
class Api_Bandas_Controller extends Base_Controller {
 
    public $restful = true;
 
    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
            $banda = Banda::skip(null);
            if(Input::get('order')){
                $orden = Input::get('order');
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $banda = $banda->order_by('bandas.'.$orden, $orden2);
                }else{
                    $banda = $banda->order_by($orden, 'desc');
                }  
            }else{
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $banda = $banda->order_by('bandas.id', $orden);
                }


            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $banda = $banda->take($limite);

            }
            $banda = $banda->get('*');
            for($i=0;$i<count($banda);$i++){
	            $banda[$i]->attributes['bio'] = BroteColectivo::limpiar_cadena($banda[$i]->attributes['bio']);
	            $banda[$i]->attributes['social'] = json_decode($banda[$i]->attributes['social']);
	        }

            return Response::eloquent($banda)->header("Access-Control-Allow-Origin", "*");
        } 
        else
        {
            $banda = Banda::find($id);
        	$banda->social = json_decode($banda->social);
        	$banda->bio = BroteColectivo::limpiar_cadena($banda->bio);
 
            if(is_null($banda)){
                return Response::json('Banda no encontrada', 404);
            } else {
                    return Response::eloquent($banda)->header("Access-Control-Allow-Origin", "*");
            }
        }
    }

 
}
 
?>