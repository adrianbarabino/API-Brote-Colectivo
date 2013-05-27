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
 
    public function post_index() 
    {
 
        $nuevabanda = Input::json();
 
        $banda = new Banda();
        $banda->nombre = $nuevabanda->nombre;
        $banda->bio = BroteColectivo::limpiar_cadena($nuevabanda->bio);
        $banda->urltag = $nuevabanda->urltag;
        $banda->social = json_decode($nuevabanda->social);
        $banda->save();
 
        return Response::eloquent($banda);
    }
 
    public function put_index() 
    {
        $actualizarbanda = Input::json();
 
        $banda = Banda::find($actualizarbanda->id);
        if(is_null($banda)){
            return Response::json('Banda no encontrada', 404);
        }
        $banda->nombre = $actualizarbanda->nombre;
        $banda->bio = $actualizarbanda->bio;
        $banda->urltag = $actualizarbanda->urltag;
        $banda->social = $actualizarbanda->social;
        $banda->save();
        return Response::eloquent($banda);
    }
 
    public function delete_index($id = null) 
    {
        $banda = Banda::find($id);
 
        if(is_null($banda))
        {
             return Response::json('Banda no encontrada', 404);
        }
        $bandaeliminada = $banda;
        $banda->delete();     
        return Response::eloquent($bandaeliminada);   
    } 
 
}
 
?>