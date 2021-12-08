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
            <th>Ingredientes</th>
            <th>Tipo</th>
            <th>Id</th>
            <th>Foto</th>
        </tr>
    </body>
</html>

<?php
    include "./clases/Receta.php";

    $contador = 0;

    $array = Receta::Traer();
    
    if(isset($_POST["tipo"]) && isset($_POST["nombre"]))
    {
        $nombre = $_POST["nombre"];
        $tipo = $_POST["tipo"];

        foreach ( $array as $receta ) 
        {
            if ($nombre == $receta->nombre && $tipo == $receta->tipo) 
            {
                echo "<tr>";
                echo "<td>".$receta->nombre."</td>";
                echo "<td>".$receta->ingredientes."</td>";
                echo "<td>".$receta->tipo."</td>";
                echo "<td>".$receta->id."</td>";
                if($receta->pathFoto != "vacio" && $receta->pathFoto != null)
                {
                    echo "<td>" .'<img src="'.$receta->pathFoto.'" width="50" height="50"'. "</td>";
                }
                else
                {
                    echo "<td>" .'Sin foto'. "</td>";
                }
                echo "</tr>";
                $contador++;
            }
        }
    }
    else if(isset($_POST["nombre"]))
    {
        $nombre = $_POST["nombre"];

        foreach ( $array as $receta ) 
        {
            if ($nombre == $receta->nombre) 
            {
                echo "<tr>";
                echo "<td>".$receta->nombre."</td>";
                echo "<td>".$receta->ingredientes."</td>";
                echo "<td>".$receta->tipo."</td>";
                echo "<td>".$receta->id."</td>";
                if($receta->pathFoto != "vacio" && $receta->pathFoto != null)
                {
                    echo "<td>" .'<img src="'.$receta->pathFoto.'" width="50" height="50"'. "</td>";
                }
                else
                {
                    echo "<td>" .'Sin foto'. "</td>";
                }
                echo "</tr>";
                $contador++;
            }
        }
    }
    else if(isset($_POST["tipo"]))
    {
        $tipo = $_POST["tipo"];

        foreach ( $array as $receta ) 
        {
            if ($tipo == $receta->tipo) 
            {
                echo "<tr>";
                echo "<td>".$receta->nombre."</td>";
                echo "<td>".$receta->ingredientes."</td>";
                echo "<td>".$receta->tipo."</td>";
                echo "<td>".$receta->id."</td>";
                if($receta->pathFoto != "vacio" && $receta->pathFoto != null)
                {
                    echo "<td>" .'<img src="'.$receta->pathFoto.'" width="50" height="50"'. "</td>";
                }
                else
                {
                    echo "<td>" .'Sin foto'. "</td>";
                }
                echo "</tr>";
                $contador++;
            }
        }
    }

    if($contador==0)
    {
        echo ("No existen datos coincidentes...");
    }

    echo '</table>';
?>