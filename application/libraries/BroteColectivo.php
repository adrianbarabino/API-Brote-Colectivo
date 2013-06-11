<?php

class BroteColectivo {
 public static function obtenerBandas($fecha_bandas)
    {
		$fecha_idbanda = $fecha_bandas;
		if(is_null($fecha_idbanda)){
		    return "Ninguna";
		} else {

		
		$cadena = "";
		$array = json_decode(json_encode($fecha_idbanda));

		while (list(, $value) = each($array)) {
		    $banda = DB::table('bandas')
		    	->where('id', '=', $value)
			    ->get();
			$banda= array_shift($banda);


			// print_r($value);
			// print_r($fecha_idbanda);
			// print_r($banda[0]->nombre);

			    if($banda){
		        	$cadena .= $banda->nombre. ", ";
			    }

		}

		return substr($cadena,0,-2);  
		unset ($cadena);
		}
    } 

  public static function obtenerDuracion($archivo)
  {
  	$ruta = "/home/brotecol/www/canciones/".$archivo.".ogg";
  	$getID3 = new getID3;
	$ThisFileInfo = $getID3->analyze($ruta);
	return @$ThisFileInfo['playtime_string'];

  }
  public static function permaLink($info, $tipo)
    {

    	if($tipo == "fechas"){
    		$url = "http://www.brotecolectivo.com/agenda-cultural/".$info."/";

    	}elseif($tipo == "canciones"){
    		$url = "http://www.brotecolectivo.com/canciones/".$info.".ogg";
    	}elseif($tipo == "bandas"){
    		$url = "http://www.brotecolectivo.com/artistas/".$info."/";
    	}
		return $url;

    }
	public static function obtenerCiudad($coordenadas)
	{
	    

	    $coordenadas = explode(',', $coordenadas);
	    if(isset($coordenadas[1])){
	    $fecha_latitud = floatval($coordenadas[0]);
	    $fecha_longitud = floatval($coordenadas[1]);
	    	
	    $url = 'http://nominatim.openstreetmap.org/reverse?format=json&lat='.$fecha_latitud.'&lon='.$fecha_longitud;
	    $archivo_web = file_get_contents($url);
	    $archivo = json_decode(utf8_decode($archivo_web));
	    if(isset($archivo->error)){
	    	return "Río Gallegos";
	    }else{
	    	if(isset($archivo->address->city)){
	   			return $archivo->address->city;	
	    		
	    	}else{

	    		return "Río Gallegos";
	    	}
	    }
	    }else{
	    	return "Error";
	    }
	}
	public static function cortar_contenido($v) {
		if(strpos($v,'[leermas]')){
		BroteColectivo::limpiar_cadena($v);
		$v = substr($v, 0, strpos($v,'[leermas]'));  
		$v = str_ireplace('[leermas]', ' ', $v);
		}
		return $v;
	}
	public static function limpiar_noticia($n) {
		BroteColectivo::limpiar_cadena($n);
		$n = str_ireplace('[leermas]', ' ', $n);
		return $n;

	}
	public static function limpiar_cadena($s) {
	    $s = str_replace('"', "'", $s);    
	    $s = trim(preg_replace('/\s+/', ' ', $s));
	    $result = $s;
	    return $result;
	}
}