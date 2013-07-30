<?php
 
class Api_Videos_Controller extends Base_Controller {
 
    public $restful = true;

 


    public function get_index($id = null) 
    {
        if (is_null($id )) 
        {
        	$video = new Video;

            if(Input::get('banda')){
                $id_de_la_banda = Input::get('banda');
                $video = $video->where('videos.idbanda', 'REGEXP', "[[:<:]]".$id_de_la_banda."[[:>:]]" );
            }
            if(Input::get('order')){
                $orden = Input::get('order');

                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $video = $video->order_by('videos.'.$orden, $orden2);
                }else{
                    $video = $video->order_by($orden, 'desc');
                }  
            }else{
                if(Input::get('order2')){
                    $orden2 = Input::get('order2');
                    $video = $video->order_by('videos.id', $orden2);
                }


            }
            if(Input::get('limit')){
                $limite = Input::get('limit');
                $video = $video->take($limite);

            }
            $video = $video->get(array('videos.*'));
            for($i=0;$i<count($video);$i++){
                // $video[$i]->attributes['permalink'] = BroteColectivo::permaLink($video[$i]->attributes['id']."-".$video[$i]->attributes['idbanda'],  "videos");
	        }

            return Response::eloquent($video)->header("Access-Control-Allow-Origin", "*");
        } 
        else
        {
        	$video = Video::where('videos.id', '=', $id)->get(array('videos.*'));
            if(is_null($video)){
                return Response::json('Video no encontrada', 404)->header("Access-Control-Allow-Origin", "*");
            } else {
            			$video_final = new Video();
            			foreach ($video[0]->attributes as $key => $value)
						{
						    $video_final->$key = $value;
						}
						$video_no_object = $video_final->attributes;
						$video_object = new stdClass();
						foreach ($video_no_object as $key => $value)
						{
						    $video_object->$key = $value;
						}

                        // $video_object->permalink = BroteColectivo::permaLink($video_object->id."-".$video_object->idbanda,  "videos");

            			$video_final->attributes = get_object_vars($video_object);


                    return Response::eloquent($video_final)->header("Access-Control-Allow-Origin", "*");
            }

        }
    }
 
 
}
 
?>