<?php 

    include "IParte1.php";
    include "IParte2.php";
    include "IParte3.php";
    include "Producto.php";

    class ProductoEnvasado extends Producto implements IParte1, IParte2, IParte3
    {
        public $id;
        public $precio;
        public $pathFoto;
        public $codigoBarra;

        public function __construct($Nombre, $Origen, $CodigoDeBarras = " ", $Precio = " ", $ID = " ", $Foto = "vacio")
        {
            parent::__construct($Nombre, $Origen);
            $this->id = $ID;
            $this->precio = $Precio;
            $this->pathFoto = $Foto;
            $this->codigoBarra = $CodigoDeBarras;
        }

        public function ToJson()
        {
            $objJson = json_encode($this);
            return $objJson;
        }

        public function Agregar()
        {
            $retorno = false;

            $servername = "localhost";
            $username = "root";
            $dbname = "productos_bd";
            $sql = "Mensaje de error";       

            try 
            {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              $sql = "INSERT INTO productos (codigo_barra, nombre, origen, precio, foto) 
                      VALUES ('".$this->codigoBarra."', '".$this->nombre."', '".$this->origen."', '".$this->precio."', '".$this->pathFoto."')";

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
            $dbname = "productos_bd";
            $arrayPersonas = array();

            try 
            {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT id, codigo_barra, nombre, origen, precio, foto FROM productos");

                $stmt->execute();

                $arrayUsuarios = $stmt->fetchAll();

                for($i = 0; $i < count($arrayUsuarios); $i++)
                {
                    $persona = new ProductoEnvasado($arrayUsuarios[$i][2],$arrayUsuarios[$i][3],$arrayUsuarios[$i][1],$arrayUsuarios[$i][4],$arrayUsuarios[$i][0],$arrayUsuarios[$i][5]);
                    array_push($arrayPersonas,$persona);
                }
            } 
            catch(PDOException $e) 
            {
                echo "Error: " . $e->getMessage();
            }

            return $arrayPersonas;
        }

        public static function Eliminar($id)
        {
            $retorno = false;
            $servername = "localhost";
            $username = "root";
            $dbname = "productos_bd";

            try 
            {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);

                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "DELETE FROM productos WHERE id=".$id;

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

        public function Modificar()
        {
            $retorno = false;
            $servername = "localhost";
            $username = "root";
            $dbname = "productos_bd";
            
            try {
              $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username);

              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
              echo($this->id);
              $sql = "UPDATE productos SET id='".$this->id."', codigo_barra='".$this->codigoBarra."', nombre='".$this->nombre."' , origen='".$this->origen."' , precio='".$this->precio."' , foto='".$this->pathFoto."' WHERE id=".$this->id;

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

        public function Existe($objetos)
        {
            $retorno = false;

            for($i = 0; $i < count($objetos); $i++)
            {
                if($objetos[$i]->nombre == $this->nombre && $objetos[$i]->origen == $this->origen)
                {
                    $retorno = true;
                }
            }

            return $retorno;
        }
        
        public function GuardarEnArchivo()
        {
            $path = "../archivos/productos_envasados_borrados.txt";

            $hoy = date("H:i:s"); 
            $hoy = str_replace(":","",$hoy);

            $newPath = "../ProductosBorrados/".$this->id.".".$this->nombre."."."borrado".".".$hoy."."."jpg";

            $data = file_get_contents($path); 
            $obj = json_decode($data);
            rename($this->pathFoto, $newPath);
            array_push($obj, json_encode($this));
            file_put_contents($path, json_encode($obj));
        }

    }
?>