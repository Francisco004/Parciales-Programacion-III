<?php
    include "./clases/Receta.php";

    $listado = json_encode(Receta::Traer());
    $listado = json_decode($listado);

    
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
      <?php
        for($i = 0; $i < count($listado); $i++)
        {
            echo "<tr>";
            echo "<td>".$listado[$i]->nombre."</td>";
            echo "<td>".$listado[$i]->ingredientes."</td>";
            echo "<td>".$listado[$i]->tipo."</td>";
            echo "<td>".$listado[$i]->id."</td>";
            if($listado[$i]->pathFoto != "vacio" && $listado[$i]->pathFoto != null)
            {
                if(strpos($listado[$i]->pathFoto, "modificado") !== false)
                {
                    echo "<td>" .'<img src="'."./recetasModificadas/".$listado[$i]->pathFoto.'" width="50" height="50"'. "</td>";
                } 
                else
                {
                    echo "<td>" .'<img src="'."./recetas/imagenes/".$listado[$i]->pathFoto.'" width="50" height="50"'. "</td>";
                }
            }
            else
            {
                echo "<td>" .'Sin foto'. "</td>";
            }
            echo "</tr>";
        }
      ?>
</body>
</html>

<?php

?>
