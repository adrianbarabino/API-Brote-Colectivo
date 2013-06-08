<?php
 
class Api_Bandas_Controller extends Base_Controller {
 
    public $restful = true;
 
    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
            $banda = Banda::get('*');
            for($i=0;$i<count($banda);$i++){
	            $banda[$i]->attributes['bio'] = BroteColectivo::limpiar_cadena($banda[$i]->attributes['bio']);
	            $banda[$i]->attributes['social'] = json_decode($banda[$i]->attributes['social']);
	        }

            return Response::eloquent($banda);
        } 
        else
        {
            $banda = Banda::find($id);
        	$banda->social = json_decode($banda->social);
        	$banda->bio = BroteColectivo::limpiar_cadena($banda->bio);
 
            if(is_null($banda)){
                return Response::json('Banda no encontrada', 404);
            } else {
                    return Response::eloquent($banda);
            }
        }
    }

 
}
 
?>