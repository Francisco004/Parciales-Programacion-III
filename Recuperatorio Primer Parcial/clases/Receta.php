<?php 

    include "IParte1.php";
    include "IParte2.php";
    include "IParte3.php";
    include "Cocinero.php";

    class Receta implements IParte1, IParte2, IParte3
    {
        public $id;
        public $nombre;
        public $ingredientes;
        public $tipo;
        public $pathFoto;

        public function __construct($Nombre = " ", $Ingredientes = " ", $Tipo = " ", $ID = " ", $PathFoto = "vacio")
        {
            $this->id = $ID;
            $this->nombre = $Nombre;
            $this->ingredientes = $Ingredientes;
            $this->tipo = $Tipo;
            $this->pathFoto = $PathFoto;
        }

        public function ToJson()
        {
            return json_encode($this);
        }

        public function Agregar()
        {
            $retorno = false;

            $servername = "localhost";
            $username = "root";
            $dbname = "recetas_bd";
            $sql = "Mensaje de error";       

            try 
            {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $sql = "INSERT INTO recetas (nombre, ingredientes, tipo, path_foto) 
                      VALUES ('".$this->nombre."', '".$this->ingredientes."', '".$this->tipo."', '".$this->pathFoto."')";

              $conn->exec($sql);

              $retorno = true;
            } 
            catch(PDOException $e) 
            {
              echo $sql . "<br>" . $e->getMessage();
            }

            return $retorno;
        }

        public static function Traer()
        {
            $servername = "localhost";
            $username = "root";
            $dbname = "recetas_bd";
            $arrayPersonas = array();

            try 
            {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT id, nombre, ingredientes, tipo, path_foto FROM recetas");

                $stmt->execute();

                $arrayUsuarios = $stmt->fetchAll();

                for($i = 0; $i < count($arrayUsuarios); $i++)
                {
                    $persona = new Receta($arrayUsuarios[$i][1],$arrayUsuarios[$i][2],$arrayUsuarios[$i][3],$arrayUsuarios[$i][0],$arrayUsuarios[$i][4]);
                    
                    array_push($arrayPersonas,$persona);
                }
            } 
            catch(PDOException $e) 
            {
                echo "Error: " . $e->getMessage();
            }

            return $arrayPersonas;
        }

        public function Existe($objetos)
        {
            $retorno = true;

            for($i = 0; $i < count($objetos); $i++)
            {
                if($objetos[$i]->nombre == $this->nombre && $objetos[$i]->tipo == $this->tipo)
                {
                    $retorno = false;
                }
            }

            return $retorno;
        }

        public function Modificar()
        {
            $retorno = false;
            $servername = "localhost";
            $username = "root";
            $dbname = "recetas_bd";
            
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);

              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
              $sql = "UPDATE recetas SET id='".$this->id."', nombre='".$this->nombre."', ingredientes='".$this->ingredientes."' , tipo='".$this->tipo."' , path_foto='".$this->pathFoto."' WHERE id=".$this->id;

              if($conn->exec($sql))
                {
                    $retorno = true;
                }
            } 
            catch(PDOException $e) 
            {
              echo $sql . "<br>" . $e->getMessage();
            }
            
            $conn = null;

            return $retorno;
        }

        public function Eliminar()
        {
            $retorno = false;
            $servername = "localhost";
            $username = "root";
            $dbname = "recetas_bd";

            try 
            {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "DELETE FROM recetas WHERE nombre='".$this->nombre . "'" . " AND tipo='".$this->tipo. "'";

                if($conn->exec($sql))
                {
                    $retorno = true;
                }
            } 
            catch(PDOException $e) 
            {
                echo $sql . "<br>" . $e->getMessage();
            }
            

            return $retorno;
        }

        public function GuardarEnArchivo()
        {
            $path = "./archivos/recetas_borradas.txt";

            $hoy = date("H:i:s"); 
            $hoy = str_replace(":","",$hoy);

            $newPath = "./recetasBorradas/".$this->id.".".$this->nombre."."."borrado".".".$hoy."."."jpg";

            if($this->pathFoto != null)
            {
                if(strpos($this->pathFoto, "modificado") !== false)
                {
                    rename("./recetasModificadas/".$this->pathFoto, $newPath);
                } 
                else
                {
                    rename("./recetas/imagenes/".$this->pathFoto, $newPath);
                }
                
            }

            $this->pathFoto = $newPath;

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

                }

                fclose($archivo);
            }
            else
            {

                $lectura = explode("]", $aux);

                    if(fwrite($archivo, $lectura[0] . "," . $this->ToJSON() . "]") != 0)
                    {

                    }
                    
                    fclose($archivo);
            }
        }

        public static function MostrarBorrados()
        {
            ?>
            <!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Document</title>
                        <style>
                            table, th, td 
                            {
                                padding-inline: 50px;
                                text-align: center;
                                border: 1px solid black;
                                border-collapse: collapse;
                            }
                        </style>
                    </head>
                    <body>
                        <table align="center">
                        <tr>
                        <th>Nombre</th>
                        <th>ingredientes</th>
                        <th>Tipo</th>
                        <th>ID</th>
                        <th>Foto</th>
                    </tr>
                    </body>
                </html>


            <?php
                
                $json_data = file_get_contents("./archivos/recetas_borradas.txt");
                $array = json_decode($json_data, true);

            if($array != null)
            {
                for($i = 0; $i < count($array); $i++)
                {
                    echo "<tr>";
                    echo "<td>".$array[$i]["nombre"]."</td>";
                    echo "<td>".$array[$i]["ingredientes"]."</td>";
                    echo "<td>".$array[$i]["tipo"]."</td>";
                    echo "<td>".$array[$i]["id"]."</td>";
                    if($array[$i]["pathFoto"] != "vacio" && $array[$i]["pathFoto"] != null)
                    {
                        echo "<td>" .'<img src="'.$array[$i]["pathFoto"].'" width="50" height="50"'. "</td>";
                    }
                    else
                    {
                        echo "<td>" .'Sin foto'. "</td>";
                    }
                    echo "</tr>";
                }
            }
        }
    }
?>