<?php

    include "./clases/Producto.php";

    echo json_encode(Producto::TraerJSON("./archivos/productos.json"));
    
?>