<?php
 
class Api_ObtenerId_Controller extends Base_Controller {
 
    public $restful = true;
 
    public function get_index($tag = null) 
    {
        if (is_null($tag )) 
        {
           
            return "Error!!!";
        } 
        else
        {
            $banda = DB::table($tabla)
            			->where('urltag', '=', $tag)
            			->get();

 
            if(is_null($banda)){
                return Response::json('Banda no encontrada', 404);
            } else {
                    return Response::eloquent($banda)->header("Access-Control-Allow-Origin", "*");
            }
        }
    }

 
}
 
?>