<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "
http://www.w3.org/TR/html4/loose.dtd">
<!-- Desarrollo Web en Entorno Servidor -->
<!-- Unidad 1 : Plataformas de programación web en entorno servidor. Aplicaciones LAMP -->
<!-- Autor: Rubén Ángel Rodriguez Leyva -->
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <title>DWES - Tarea 1 - Forrare Bank</title>
    </head>
    <body>
        
        <?php

        // Se añade el archivo donde se encuentran todas las funciones.
        include 'funciones.php';
        
        
        // Función que contiene la cabecera web (espero mejorarla con el tiempo).
        cabecera("Unidad 1 - Plataformas de programación web en entorno servidor Aplicaciones LAMP", "FORRARE BANK");
        
	// Iniciamos algunas variables que se utilizarán en algunas partes del código.
        $fecha = ""; // Para la fecha
        $concepto =""; // Para el concepto.
        $cantidad = ""; // Para la cantidad. 
        $errores = ""; // Para los errores.
        
        ?>
        <!-- Menú con los diferentes botones necesarios para la tarea -->
        <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <div>
            <table style="margin: 10px auto 15px auto">
                <tr>
                    <td><input type="submit" name="eleccion" value="Ultimos movimientos"/></td>
                    <td><input type="submit" name="eleccion" value="Ingresar dinero"/></td>
                    <td><input type="submit" name="eleccion" value="Pagar recibos"/></td>
                    <td><input type="submit" name="eleccion" value="Devolver recibos"/></td>
                </tr>
            </table>
        </div>
        <?php
        
        // Se crea el array que contendrá los movimientos con sus datos.
        $guardarMovimientos = array();
            
        /* Si mediante los campos hidden (están al final del archivo) nos automandamos el array hidden, obtenemos los datos
           y lo volcamos directamente al array para rellenarlo. */
        if (isset($_REQUEST['guardarMovimientos'])) {
            $guardarMovimientos = $_REQUEST['guardarMovimientos'];
        }
        
        // En caso de pulsar un botón
        if (isset($_REQUEST['eleccion'])) {
            
            // le pasamos a la variable elección el nombre del botón pulsado.
            $eleccion = $_REQUEST['eleccion'];
            
            // A través de un switch al que le pasamos la elección escogemos la opción que corresponda.
            switch ($eleccion) {
                
                // Con esta opción vemos los útilmos movimientos que tenemos guardados en el array.
                case "Ultimos movimientos":
                    
                    $tipoUltimos = "Últimos movimientos(4 últimos movimientos)"; //Indicamos que se van a mostrar los 4 últimos movimientos
                    $correcto = ""; // Variable utilizada para guardar el texto que se mostrará en caso de que el pago o ingreso sean correctos.
                    $devolucion = false;  // Variable encargada de indicar si es una devolución para cambiar la forma de mostrar los últimos movimientos.
                    
                    // Llamamos a la función para mostrar los últimos movimientos.
                    mostrarUltimos($tipoUltimos, $guardarMovimientos, $correcto, $devolucion);
                    break;
                
                // Con esta opción entramos en la opción de ingresar dinero en el banco.
                case "Ingresar dinero":
                    
                    $tipo = "Ingresar"; // Esta variable la utilizamos para darle el nombre de Pagar tanto al formulario como al botón al formulario.
                    
                    $fecha = fecha(); // Colocamos la fecha actual en el formato correcto, para referencia de la persona que introduce los datos.
                    
                    // Lamamos a la función que dibuja el formulario.
                    formulario($tipo, $errores, $fecha, $concepto, $cantidad);
                    break;
                
                // Con esta opción al pulsar en el botón ingresar comporbamos los datos e introducimos los datos en el array.
                case "Ingresar":
                    
                    $tipoUltimos = "Últimos movimientos(4 últimos movimientos)"; //Indicamos que se van a mostrar los 4 últimos movimientos
                    
                    // Variable utilizada para compobar si existen errores a través de validaciones.
                    $errores = validar_Datos($_REQUEST['fecha'], $_REQUEST['concepto'], $_REQUEST['cantidad']);
                    
                    // Variable encargada de indicar si es una devolución para cambiar la forma de mostrar los últimos movimientos.
                    $devolucion = false;
                    
                    // En caso de que existan errores.
                    if($errores){
                        
                        $tipo = "Ingresar"; // Esta variable la utilizamos para darle el nombre de Ingresar tanto al formulario como al botón al formulario.
                        
			// Le pasamos a las diferentes variables los datos que tenían para que los coloque de nuevo en caso de error.
                        $fecha = $_REQUEST['fecha'];
                        $concepto = $_REQUEST['concepto'];
                        $cantidad = $_REQUEST['cantidad'];
                        
                        // llamamos de nuevo al formulario, pasándole los diferentes errores que hay.
                        formulario($tipo, $errores, $fecha, $concepto, $cantidad);
                    
                    // en caso de que no existan errores
                    }else{
                        
                        //Creamos un array donde guardamos los datos del movimiento que hemos introducido
                        $miMovimiento = array(
                            'fecha' => $_REQUEST['fecha'], // Para la fecha
                            'concepto' => $_REQUEST['concepto'], // Para el concepro
                            'cantidad' => $_REQUEST['cantidad'] // Para la cantidad
                        );

                        //Ahora añadimos el movieminto insertado en el array de los movimientos
                        $guardarMovimientos[] = $miMovimiento;
                        
                        // Como ha sido corecto mostramos la leyenda de éxito por pantalla.
                        $correcto = "INGRESO REALIZADO CON ÉXITO";
                        
                        // Mostramos los cuatro últimos movimientos.
                        mostrarUltimos($tipoUltimos, $guardarMovimientos, $correcto, $devolucion);
                    }
                    break;
                    
                // Con esta opción al pulsar en el botón pagar recibos generamos un nuevo formulario para introducir los datos necesatrios.
                case "Pagar recibos":
                    
                    $tipo = "Pagar"; // Esta variable la utilizamos para darle el nombre de Pagar tanto al formulario como al botón al formulario.
                              
                    $fecha = fecha(); // Colocamos la fecha actual en el formato correcto, para referencia de la persona que introduce los datos.
                    
                    // Lamamos a la función que dibuja el formulario.
                    formulario($tipo, $errores, $fecha, $concepto, $cantidad);
                    break;
                
                // Con esta opción al pulsar en el botón pagar comprobamos los datos e introducimos los datos en el array.
                case "Pagar":
                    
                    $tipoUltimos = "Últimos movimientos(4 últimos movimientos)"; //Indicamos que se van a mostrar los 4 últimos movimientos
                    
                    // Variable utilizada para compobar si existen errores a través de validaciones.
                    $errores = validar_Datos($_REQUEST['fecha'], $_REQUEST['concepto'], $_REQUEST['cantidad']);
                    
                    // Variable encargada de indicar si es una devolución para cambiar la forma de mostrar los últimos movimientos.
                    $devolucion = false;
                    
                    // En caso de que existan errores.
                    if($errores){
                        
                        $tipo = "Pagar"; // Esta variable la utilizamos para darle el nombre de Pagar tanto al formulario como al botón al formulario.
                        
                        // Le pasamos a las diferentes variables los datos que tenían para que los coloque de nuevo en caso de error.
                        $fecha = $_REQUEST['fecha'];
                        $concepto = $_REQUEST['concepto'];
                        $cantidad = $_REQUEST['cantidad'];
                           
                        // llamamos de nuevo al formulario, pasándole los diferentes errores que hay.
                        formulario($tipo, $errores, $fecha, $concepto, $cantidad);
                        
                    // en caso de que no existan errores    
                    } else {
                        
                        //Creamos un array donde guardamos los datos del movimiento que hemos introducido.
                        $miMovimiento = array(
                            'fecha' => $_REQUEST['fecha'], // Para la fecha
                            'concepto' => $_REQUEST['concepto'], // Para el concepro
                            'cantidad' => -$_REQUEST['cantidad'] // Para la cantidad, en este caso NEGATIVO
                        );

                        //Ahora añadimos el movieminto insertado en el array de los movimientos
                        $guardarMovimientos[] = $miMovimiento;
                        
                        // Como ha sido corecto mostramos la leyenda de éxito por pantalla.
                        $correcto = "PAGO REALIZADO CON ÉXITO";
                        
                        // Mostramos los cuatro últimos movimientos.
                        mostrarUltimos($tipoUltimos, $guardarMovimientos, $correcto, $devolucion);
                    }
                    break;
                
                // Con esta opción al pulsar en el botón devolver recibos mostramos los últimos movimientos para eliminar el que deseemos.
                case "Devolver recibos":
                    
                    $tipoUltimos = "Devolver recibo"; //Indicamos que se van a mostrar los 4 últimos movimientos
                    
                    $correcto = ""; // Variable utilizada para guardar el texto que se mostrará en caso de que el pago o ingreso sean correctos.
                    
                    // Variable encargada de indicar si es una devolución para cambiar la forma de mostrar los últimos movimientos.
                    $devolucion = true;
                    
                    // Mostramos los cuatro últimos movimientos.
                    mostrarUltimos($tipoUltimos, $guardarMovimientos, $correcto, $devolucion);
                    break;
                
                // Con esta opción al pulsar en el botón devolver recibos mostramos los últimos movimientos para eliminar el que deseemos.
                case "Devolver":
                    
                    $correcto = ""; // Variable utilizada para guardar el texto que se mostrará en caso de que el pago o ingreso sean correctos.
                    
                    // Variable encargada de indicar si es una devolución para cambiar la forma de mostrar los últimos movimientos.
                    $devolucion = false;
                    
                    
                    
                    // En caso de que tanto el array como el índice no se encuentren vacíos o nulos continuamos.
                    if(isset($_REQUEST['indice'])){
                        
                        $tipoUltimos = "Últimos movimientos(4 últimos movimientos)"; //Indicamos que se van a mostrar los 4 últimos movimientos
                        
                        $numeroIndice = (int) $_REQUEST['indice'];
                        
                        // Llamamos a la función encargada de borrar el array deseado.
                        devolverMovimiento($guardarMovimientos, $numeroIndice);
                        
                        // Mostramos el mensaje de operación correcta.
                        echo "<div>";
                        echo "<table  border='2px' style='margin: 1% auto 1% '>";
                        echo "<tr><td><span>DEVOLUCIÓN CORRECTA</span></td></tr>";
                        echo "</table>";
                        echo "</div>";
                        
                        // Llamamos a la función encargada de mostrarnos los últimos movimientos.
                        mostrarUltimos($tipoUltimos, $guardarMovimientos, $correcto, $devolucion); 
                    }                        
                    break;
                
                // Con esta opción al pulsar en el botón cancelar mostramos un mensaje de cancelación.
                case "Cancelar":
                    
                    // Mostramos el mensaje operación cancelada
                    echo "<div>";
                    echo "<table  border='2px' style='margin: 1% auto 1% '>";
                    echo "<tr><td><span>SE HA CANCELADO</span></td></tr>";
                    echo "</table>";
                    echo "</div>";
                    
                    
                    // Mostramos el mensaje para que elija otra opción
                    echo "<div>";
                    echo "<table  border='2px' style='margin: 1% auto 1% '>";
                    echo "<tr><td><span>ELIJA UNA OPCIÓN DE LAS MOSTRADAS EN EL MENÚ PRINCIPAL</span></td></tr>";
                    echo "</table>";
                    echo "</div>";                     
                    break;
                
                    default:
                        break;
                }  
            }

            //Cada vez que enviemos algo por el formulario pasamos en el formulario todos los elementos del array $guardarMovimientos como hidden para automandarnos un array $guardarMovimientos
            foreach ($guardarMovimientos as $clave => $valor) {
                echo '<input type="hidden" name="guardarMovimientos[' . $clave . '][fecha]" value="' . $valor['fecha'] . '">'; // Para la fecha
                echo '<input type="hidden" name="guardarMovimientos[' . $clave . '][concepto]"  value="' . $valor['concepto'] . '">'; // Para el concepto
                echo '<input type="hidden" name="guardarMovimientos[' . $clave . '][cantidad]"  value="' . $valor['cantidad'] . '">'; // Para la cantidad
            }
            
            // Pie de la página Web
            pie("Desarrollo web en entorno servidor - Tarea 1");  
            ?>
        </form>
    </body>
</html>
