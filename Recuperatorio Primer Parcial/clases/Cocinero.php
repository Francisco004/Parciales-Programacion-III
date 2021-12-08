<?php

    class Cocinero
    {
        private $email;
        private $clave;
        private $especialidad;

        public function __construct($Especialidad, $Email, $Clave)
        {
            $this->email = $Email;
            $this->clave = $Clave;
            $this->especialidad = $Especialidad;
        }

        public function ToJson()
        {
            return '{"especialidad":'.'"'.$this->especialidad.'"'.',"email":'.'"'.$this->email.'"'.',"clave":'.'"'.$this->clave.'"'.'}';
        }

        public function GuardarEnArchivo()
        {
            $path = "./archivos/cocinero.json";
            $rtaJSON = new stdClass();
            $rtaJSON->exito = false;
            $rtaJSON->mensaje = "No se pudo agregar el cocinero!!!";

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
                    $rtaJSON->mensaje = "Se pudo guardar en el cocinero.";

                }

                fclose($archivo);
            }
            else
            {
                $lectura = explode("]", $aux);

                    if(fwrite($archivo, $lectura[0] . "," . $this->ToJSON() . "]") != 0)
                    {
                        $rtaJSON->exito = true;
                        $rtaJSON->mensaje = "Se pudo guardar en el cocinero.";
                    }
                    fclose($archivo);
            }

            return json_encode($rtaJSON);
        }

        public static function TraerTodos()
        {
            $path = "./archivos/cocinero.json";

            if(file_exists($path))
            {
                $archivo = fopen($path,"r");

                $cadena = '';

                while(!feof($archivo))
                {
                    $cadena .= fgets($archivo);
                }

                fclose($archivo);

                $aux = json_decode($cadena);

                $auxArray = array();

                foreach($aux as $cocineros)
                {
                    $lista = new stdclass();
                    $lista->especialidad = $cocineros->especialidad; 
                    $lista->email = $cocineros->email;
                    $lista->clave = $cocineros->clave;

                    array_push($auxArray, $lista);
                }
            }

            return $auxArray;
        }

        public static function VerificarExistencia($cocinero)
        {
            $cantidad = 0;
            $retorno = new stdClass();
            $retorno->exito = false;

            $array = Cocinero::TraerTodos();
            
            foreach ( $array as $element ) 
            {
                if($cocinero->email == $element->email && $cocinero->clave == $element->clave) 
                {
                    $retorno->exito = true;

                    foreach ( $array as $element2 ) 
                    {
                        if($element->especialidad == $element2->especialidad) 
                        {
                            $cantidad++;

                            $retorno->mensaje = "La cantidad de cocineros registrados con la misma especialidad es de: " . $cantidad;
                        }
                    }
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
                
                $retorno->mensaje = ", la especialidad mas popular es: ";

                foreach($out as $el=>$count)
                {
                    $item = unserialize($el);
                    $retorno->mensaje .= $item->especialidad;
                    break;
                }
            }
                        
            return Json_encode($retorno);
        }
    }

?>