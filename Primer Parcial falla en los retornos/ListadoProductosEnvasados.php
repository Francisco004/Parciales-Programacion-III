<?php
    include "./clases/ProductoEnvasado.php";

    $listado = json_encode(ProductoEnvasado::Traer());
    $listado = json_decode($listado);

    if(isset($_GET["tabla"]) && $_GET["tabla"] == "mostrar")
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
            echo "<td>".$listado[$i]->nombre."</td>";
            echo "<td>".$listado[$i]->origen."</td>";
            echo "<td>".$listado[$i]->codigoBarra."</td>";
            echo "<td>".$listado[$i]->id."</td>";
            echo "<td>".$listado[$i]->precio."</td>";
            echo "<td>" .'<img src="'.$listado[$i]->pathFoto.'" width="50" height="50"'. "</td>";
            echo "</tr>";
        }
      ?>
</body>
</html>

<?php

    }
    else
    {
        return json_encode($listado);
    }
?>
