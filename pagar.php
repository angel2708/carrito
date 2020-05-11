<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php
if($_POST){

    $total=0;
    $SID=session_id();
    $correo=$_POST['email'];

    foreach($_SESSION['CARRITO'] as $indice=>$producto){

        $total=$total+($producto['precio']*$producto['cantidad']);

    }
        $sentencia=$pdo->prepare("INSERT INTO `ventas` 
                            (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) 
        VALUES (NULL, :ClaveTransaccion, '', NOW(), :Correo, :Total, 'pendiente');");

        $sentencia->bindParam(":ClaveTransaccion",$SID);
        $sentencia->bindParam(":Correo",$correo);
        $sentencia->bindParam(":Total",$total);
        $sentencia->execute();
        $idVenta=$pdo->lastInsertId();

        foreach($_SESSION['CARRITO'] as $indice=>$producto){

            $sentencia=$pdo->prepare("INSERT INTO 
                                `detalleventa` (`ID`, `IDventa`, `IDproducto`, `precioUnitario`, `cantidad`, `descargado`) 
            VALUES (NULL, :IDventa, :IDproducto, :precioUnitario, :cantidad, '0');");

            $sentencia->bindParam(":IDventa",$idVenta);
            $sentencia->bindParam(":IDproducto",$producto['ID']);
            $sentencia->bindParam(":precioUnitario",$producto['precio']);
            $sentencia->bindParam(":cantidad",$producto['cantidad']);
            $sentencia->execute();

        }

    // echo "<h3>".$total."</h3>";
}
?>

<div class="jumbotron text-center">
    <h1 class="display-4">¡PASO FINAL!</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de:
        <h4><?php echo number_format($total,2);?>€</h4>
    </p>
    <p>Al procesarse el pago usted podrá descargar los productos seleccionados.<br/>
        <strong>Si tiene alguna duda contacte con nosotros</strong>
    </p>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>

    <!-- Include the PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>

    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({
            style: {
                layout: 'horizontal'
            }
        }).render('#paypal-button-container');
    </script>
</div>

<?php
include 'templates/pie.php';
?>