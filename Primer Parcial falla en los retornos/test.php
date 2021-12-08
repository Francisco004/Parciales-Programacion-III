<?php
    //include("./clases/Producto.php");
    include("./clases/ProductoEnvasado.php");


    ///GuardarJson
    /*
    $producto = new Producto($_POST["nombre"],$_POST["origen"]);

    var_dump($producto->GuardarJson("./archivos/productos.json"));
    */

    ///TraerJSON
    /*
    var_dump(Producto::TraerJSON());
    */

    ///VerificarProductoJSON
    /*
    $producto = new Producto($_POST["nombre"],$_POST["origen"]);

    var_dump($producto->VerificarProductoJson($producto)); 
    */

    ///AltaProductoJSON.php
    /*
    incluide("./AltaProductoJSON.php");
    */

    ///VerificarProductoJSON.php
    /*
    incluide("./VerificarProductoJSON.php");
    */

    //ListadoProductosJSON.php
    /*
    include("./ListadoProductosJSON.php");
    */

    //MostrarCoockie.php
    /*
    include("./MostrarCoockie.php");
    */

    //ProductoEnvasado.php
    /*
    $extension = $_FILES["foto"]["name"];
    $tmpsNames = $_FILES["foto"]["tmp_name"];
    $tiposArchivo = pathinfo($extension, PATHINFO_EXTENSION);

    $resStr = str_replace('\\', "/", $tiposArchivo);
    $destino = "./fotos/".$_POST["nombre"]."_".$_POST["origen"].".".$tiposArchivo;
    move_uploaded_file($tmpsNames, $destino);

    $producto = new ProductoEnvasado($_POST["nombre"],$_POST["origen"],$_POST["codigo"],$_POST["precio"],$_POST["id"],$destino);

    $producto->Agregar();
*/
    var_dump(ProductoEnvasado::Traer());
    
?>