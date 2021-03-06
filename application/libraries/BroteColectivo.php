<?php

class BroteColectivo {

	public static function obtenerMes($mes)
	{
		if ($mes == "1") {
			 return "Enero";
		}elseif ($mes == "2") {
			 return "Febrero";
		}elseif ($mes == "3") {
			 return "Marzo";
		}elseif ($mes == "4") {
			 return "Abril";
		}elseif ($mes == "5") {
			 return "Mayo";
		}elseif ($mes == "6") {
			 return "Junio";
		}elseif ($mes == "7") {
			 return "Julio";
		}elseif ($mes == "8") {
			 return "Agosto";
		}elseif ($mes == "9") {
			 return "Septiembre";
		}elseif ($mes == "10") {
			 return "Octubre";
		}elseif ($mes == "11") {
			 return "Noviembre";
		}elseif ($mes == "12") {
			 return "Diciembre";
		}
	}
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
		        	$cadena .= "<a href='/artistas/".$banda->urltag."' class='link_brote' rel='address:/artistas/".$banda->urltag."'>".$banda->nombre. "</a>, ";
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
	    	
		$archivo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$fecha_latitud.','.$fecha_longitud.'&sensor=true&language=es');
		$archivo = json_decode($archivo);
	    if($archivo->status == "OK"){
			$ubicacion = $archivo->results[0];
			$ciudad = $ubicacion->address_components[2]->long_name;
			if(isset($ciudad)){
				if($ciudad == "Santa Cruz"){
					return "Puerto Deseado";
				}elseif($ciudad == "Lago Argentino"){
					return "El Chaltén";
				}else{

 				return $ciudad;
				}
			}else{
				return "Río Gallegos";
			}
	    }else{
	    	return "Error";
	    }
	    }else{
	    	return "Error";
	    }
	}
    public static function cerrar_tags ( $html )
        {
        #put all opened tags into an array
        preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
        $openedtags = $result[1];
        #put all closed tags into an array
        preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
        $closedtags = $result[1];
        $len_opened = count ( $openedtags );
        # all tags are closed
        if( count ( $closedtags ) == $len_opened )
        {
        return $html;
        }
        $openedtags = array_reverse ( $openedtags );
        # close tags
        for( $i = 0; $i < $len_opened; $i++ )
        {
            if ( !in_array ( $openedtags[$i], $closedtags ) )
            {
            $html .= "</" . $openedtags[$i] . ">";
            }
            else
            {
            unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
            }
        }
        return $html;
    }

	public static function cortar_contenido($v) {
		if(strpos($v,'[leermas]')){
			BroteColectivo::limpiar_cadena($v);
			$v = substr($v, 0, strpos($v,'[leermas]'));  
			$v = str_ireplace('[leermas]', ' ', $v);
		}
		return BroteColectivo::cerrar_tags($v);

	}
	public static function cortar_cadena($cadena, $limite) {
	    if(strlen($cadena) <= $limite)
	        return $cadena;
	    if(false !== ($breakpoint = strpos($cadena, " ", $limite))) {
	        if($breakpoint < strlen($cadena) - 1) {
	            $cadena = substr($cadena, 0, $breakpoint) . "...";
	        }
   	 	}
	    $cadena = BroteColectivo::limpiar_cadena($cadena);
	    $cadena = BroteColectivo::cerrar_tags($cadena);

	    return $cadena;

	}
	public static function limpiar_noticia($n) {
		BroteColectivo::limpiar_cadena($n);
		$n = str_ireplace('[leermas]', ' ', $n);
		$final = BroteColectivo::cerrar_tags($n);
		return $final;

	}
	public static function limpiar_cadena($s) {
	    $s = str_replace('"', "'", $s);    
	    $s = trim(preg_replace('/\s+/', ' ', $s));
	    $result = $s;
	    return $result;
	}
}
