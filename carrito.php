<?php
session_start();

$mensaje="";

if(isset($_POST['btnAccion'])){

    switch($_POST['btnAccion']){

        case 'agregar':

            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID=openssl_decrypt($_POST['id'],COD,KEY );
                $mensaje.="OK ID correcto ".$ID."<br/>";

            }else{
                $mensaje.="Ups.. ID incorrecto ".$ID."<br/>";
                break;
            }

            if(is_string(openssl_decrypt($_POST['nombre'],COD,KEY))){
                $nombre=openssl_decrypt($_POST['nombre'],COD,KEY );
                $mensaje.="OK nombre correcto ".$nombre."<br/>";

            }else{
                $mensaje.="Ups.. nombre incorrecto ".$nombre."<br/>";
                break;
            }

            if(is_numeric(openssl_decrypt($_POST['precio'],COD,KEY))){
                $precio=openssl_decrypt($_POST['precio'],COD,KEY );
                $mensaje.="OK precio correcto ".$precio."€<br/>";

            }else{
                $mensaje.="Ups.. precio incorrecto ".$precio."<br/>";
                break;
            }

            if(is_numeric(openssl_decrypt($_POST['cantidad'],COD,KEY))){
                $cantidad=openssl_decrypt($_POST['cantidad'],COD,KEY );
                $mensaje.="OK cantidad correcta ".$cantidad."<br/>";

            }else{
                $mensaje.="Ups.. cantidad incorrecta ".$cantidad."<br/>";
                break;
            }

            if(!isset($_SESSION['CARRITO'])){

                $producto=array(
                    'ID'=>$ID,
                    'nombre'=>$nombre,
                    'precio'=>$precio,
                    'cantidad'=>$cantidad
                );
                $_SESSION['CARRITO'][0]=$producto;
                $mensaje="Producto agregado al carrito";

            }else{

                $idProductos=array_column($_SESSION['CARRITO'],"ID");

                if (in_array($ID,$idProductos)) {
                    echo "<script>alert('El producto ya ha sido agregado')</script>";
                    $mensaje="";
                }else{

                $numeroProductos=count($_SESSION['CARRITO']);
                $producto=array(
                    'ID'=>$ID,
                    'nombre'=>$nombre,
                    'precio'=>$precio,
                    'cantidad'=>$cantidad
                );
                $_SESSION['CARRITO'][$numeroProductos]=$producto;
                $mensaje="Producto agregado al carrito";
                }
            }
            // $mensaje= print_r($_SESSION,true);
            

        break;
        
        case "Eliminar":
            if(is_numeric(openssl_decrypt($_POST['id'],COD,KEY))){
                $ID=openssl_decrypt($_POST['id'],COD,KEY );
                
                foreach($_SESSION['CARRITO'] as $indice=>$producto){
                    if ($producto['ID']==$ID){
                        unset($_SESSION['CARRITO'][$indice]);
                        echo "<script>alert('Elemento borrado');</script>";
                    }
                }

            }else{
                $mensaje.="Ups.. ID incorrecto ".$ID."<br/>";
                break;
            }   

        break;
}

}

?>