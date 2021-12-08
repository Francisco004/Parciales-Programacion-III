<?php

    class Producto
    {
        public $nombre;
        Public $origen;

        public function __construct($Nombre, $Origen)
        {
            $this->nombre = $Nombre;
            $this->origen = $Origen;
        }

        public function ToJson()
        {
            $objJson = json_encode($this);
            return $objJson;
        }

        public function GuardarJson($path)
        {
            $rtaJSON = new stdClass();
            $rtaJSON->exito = false;
            $rtaJSON->mensaje = "No se pudo agregar el producto!!!";

            if(file_exists($path))
            {
                $archivo = fopen($path,"r");

                if(filesize($path) > 0)
                {
                    $aux = fread($archivo, filesize($path));
                }

                fclose($archivo);

                $archivo = fopen($path,"w");
            }
            else
            {
                $archivo = fopen($path,"a");
            }

            if(filesize($path) == 0)
            {
                if(fwrite($archivo, "[". $this->ToJSON() . "]") != 0)
                {
                    $rtaJSON->exito = true;
                    $rtaJSON->mensaje = "Se pudo guardar en el archivo.";

                }

                fclose($archivo);
            }
            else
            {

                $lectura = explode("]", $aux);

                    if(fwrite($archivo, $lectura[0] . "," . $this->ToJSON() . "]") != 0)
                    {
                        $rtaJSON->exito = true;
                        $rtaJSON->mensaje = "Se pudo guardar en el archivo.";
                    }
                    fclose($archivo);
            }

            return json_encode($rtaJSON);
        }

        public static function TraerJSON($path)
        {
            $productos = array();

            $string = file_get_contents($path);

            $decodeo = json_decode($string, true);

            for($i = 0; $i < count($decodeo); $i++)
            {
                $producto = new Producto($decodeo[$i]["nombre"],$decodeo[$i]["origen"]);
                array_push($productos, $producto);
            }

            return $productos;
        }

        public static function VerificarProductoJson($producto)
        {
            $cantidad = 0;
            $retorno = new stdClass();
            $retorno->exito = false;

            $array = Producto::TraerJSON("./archivos/productos.json");

            foreach ( $array as $element ) 
            {
                if ( $producto->nombre == $element->nombre && $producto->origen == $element->origen) 
                {
                    $cantidad++;

                    $retorno->exito = true;

                    $retorno->mensaje = "La cantidad de productos registrados con el mismo origen es de: " . $cantidad;
                }
            }

            if($retorno->exito == false)
            {
                $out = array();

                foreach($array as $el)
                {
                    $key = serialize($el);

                    if (!isset($out[$key]))
                    {
                        $out[$key]=1;
                    }
                    else
                    {
                        $out[$key]++;
                    }
                }

                arsort($out);
                
                $retorno->mensaje = "El producto mas popular es: ";

                foreach($out as $el=>$count)
                {
                    $item = unserialize($el);
                    $retorno->mensaje .= "<br>Nombre: " . $item->nombre . "<br>Origen: " . $item->origen;
                    break;
                }
            }
                        
            return Json_encode($retorno);
        }
    }

?>