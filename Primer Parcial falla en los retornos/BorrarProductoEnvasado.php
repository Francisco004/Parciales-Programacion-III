<?php
    include "./clases/ProductoEnvasado.php";

    $retorno = new stdClass();
    $retorno->exito = false;
    $retorno->mensaje = "Error al borrar el producto";

    if(isset($_POST["producto_json"]))
    {
        $json = json_decode($_POST["producto_json"],true);
    
        if(ProductoEnvasado::Eliminar($json["id"]))
        {
            $ProductoEnvasado = new ProductoEnvasado($json["nombre"], $json["apellido"], $json["codigoBarra"], $json["precio"], $json["pathFoto"], $json["id"]);
    
            $ProductoEnvasado->GuardarEnArchivo();
            $retorno->exito = true;
            $retorno->mensaje = "Exito al borrar el producto";
        }
    }
    else
    {
        $json_data = file_get_contents('./archivos/productos_envasados_borrados.txt');
        $listado = json_decode($json_data, true);
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
            <th>Origen</th>
            <th>CodigoDeBarras</th>
            <th>ID</th>
            <th>Precio</th>
            <th>Foto</th>
        </tr>
            <?php
                for($i = 0; $i < count($listado); $i++)
                {
                    echo "<tr>";
                    echo "<td>".$listado[$i]["nombre"]."</td>";
                    echo "<td>".$listado[$i]["origen"]."</td>";
                    echo "<td>".$listado[$i]["codigoBarra"]."</td>";
                    echo "<td>".$listado[$i]["id"]."</td>";
                    echo "<td>".$listado[$i]["precio"]."</td>";
                    echo "<td>" . "<img src=".$listado[$i]["pathFoto"]." width='50' height='50'>" . "</td>";
                    echo "</tr>";
                }
            ?>
        </body>
        </html>
<?php
    }
        
    return json_encode($retorno);
?> 
