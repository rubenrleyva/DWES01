<?php

/**
 * Función que dibujará el formulario para introducir datos, el mismo dispone 
 * de dos argumentos.
 * 
 * @param type $tipo nos sirve para saber que tipo de formulario es, Insertar o Pagar.
 * @param type $errores nos sirve para saber en caso de que falte algún campo por completar, cuál sería.
 */
function formulario($tipo, $errores, $fecha, $concepto, $cantidad) {
    ?>
    <div>
        <table style="margin: 10px auto 15px auto">
        <tr><td width='30%'></td><td width='70%'></td></tr>

        <form method=post action=$_SERVER[PHP_SELF]>
            <tr><td colspan=2><b><?php echo $errores; ?></b></td></tr>
            <tr><td colspan=2><b><?php echo $tipo; ?></b></td></tr>
            <tr><td>Fecha:</td><td><input type=text name=fecha value='<?php echo $fecha ?>' size=50 maxlength=50></td></tr>
            <tr><td>Concepto:</td><td><input type=text name=concepto value='<?php  echo $concepto ?>' size=50 maxlength=50></td></tr>
            <tr><td>Cantidad:</td><td><input type=text name=cantidad value='<?php echo $cantidad ?>'size=50 maxlength=50></td></tr>
            <tr><td colspan=2><i>Por favor, ingrese los datos solicitados y haga clic en '<?php echo $tipo; ?>'. Todos los campos son obligatorios.</i></td></tr>
            <tr><td><input type=submit name=eleccion value='<?php echo $tipo; ?>' /><input type=submit name=eleccion value='Cancelar' /></td><td></td>
        </form>
        </table> 
    </div>
    <?php
}

/**
 * Función que muestra el listado de los movimientos hasta el momento.
 * 
 * @param type $guardaMovimientos nos sirve para pasarle el Array con los diferentes movimientos.
 * @param type $correcto nos sirve para controlar y pasar un mensaje en caso de que sea correcto.
 * @param type $devolucion nos sirve para indicar si es una devolución.
 */
function mostrarUltimos($tipoUltimos, $guardaMovimientos, $correcto, $devolucion) { //Se le pasa como parametro el array con los datos que tengamos almacenados  
    
    
    $saldoTotal = 0; // La variable es utilizada para que me sume todo el saldo que hay aunque solo aparecezcan los últimos cuatro movimientos.
    $contadorRecibos = 0; // La variable es utilizada para contar los recibos que hay, si no existe ninguno pasamos un mensaje.
    
    
    // Comprobamos que el array no se encuentra vacio.
    if (!empty($guardaMovimientos)) {
        
        // Utilizamos la variable para indicar el número de movimientos que hay dentro del array, así podemos después colorear la última cifra de saldo contable.
        $totalElementos = count($guardaMovimientos) - 1;
        
        // El resto del código sirve para crear una tabla con los diferentes resultados.
        if($correcto){
            echo "<div>";
            echo "<table  border='2px' style='margin: 1% auto 1% '>";
            echo "<tr><td><span>".$correcto."</span></td></tr>";
            echo "</table>";
            echo "</div>";
        }
        ?>
        <div>
            <table  border="2px" style="margin: 1% auto 1% ">
            <form method=post action=$_SERVER[PHP_SELF]>
                <tr><td colspan=4 align=center><b><?php echo $tipoUltimos ?></b></td></tr>           
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Cantidad</th>
                    <?php
                    
                    // Comprobamos si es una devolución para mostrar en el último espacio un texto u otro.
                    if(!$devolucion){
                        
                        // En caso de que no sea una devolución.
                        echo "<th>Saldo Contable</th>";
                        
                        // Contamos el número de movimientos que a hay en el array y le restamos cuatro, así aparecerán solo los cuatro últimos.
                        $numeroElementos = count($guardaMovimientos) - 4;
                        
                    } else {
                        
                        // En caso de que si sea una devolución.
                        echo "<th>DEVOLVER</th>";
                        
                        // Contamos el número de movimientos que a hay en el array, ahora no le restamos para que aparezcan todos.
                        $numeroElementos = 0;
                    }  
                echo "</tr>";

                // Utilizamos un bucle foreach para recorrer el array, ha $indice le pasamos los
                // valores de los diferentes elementos que se encuentran en $guardaMovimientos.
                foreach($guardaMovimientos as $indice => $valor){
                    
                    // Calculamos el saldo total aunque solo se muestren los cuatro últimos movimientos.
                    $saldoTotal = calcular_Saldo_Contable($saldoTotal, $valor);
                    
                    // Mostramos todos los movimientos o solo los recibos según ambas opciones mostradas en el if.
                    if(!$devolucion || ($valor['cantidad'] < 0)){
                        
                        // Para mostrar solo los cuatro úlltimos movimientos de la lista o la lista completa.
                        if(($indice >= $numeroElementos) || ($numeroElementos == 0)){                           
                            
                            // Mostramos los diferentes datos en la tabla.
                            echo " <tr>";
                            echo "  <td align=center><small>".$valor['fecha']."</td>";
                            echo "  <td align=center><small>".$valor['concepto']."</td>";
                            
                            // Si el valor de la cantidad es menor de cero lo ponemos en color rojo.
                            if($valor['cantidad'] < 0){
                                echo "<td align=center><small><font color=red>".round($valor['cantidad'], 2)." €</font></td>";
                            
                            // en caso contrario lo dejamos normal.    
                            } else {
                                echo "<td align=center><small>".round($valor['cantidad'], 2)." €</td>";
                            }
      
                            // Si no es una devolución se muestra el saldo.
                            if(!$devolucion){
                            
                                // si se trata del último movimiento...
                                if($indice == $totalElementos){
                                    
                                    // y además es menor de cero, lo ponemos en rojo y con un tamaño mayor.
                                    if($saldoTotal < 0){
                                        echo "<td align=center><small><font color=red size=3><strong>".round($saldoTotal, 2)." €</strong></font></small></td>";
                                    
                                    // en caso contrario se deja por defecto.    
                                    } else {
                                        echo "<td align=center><small><font size=3><strong>".round($saldoTotal, 2)." €</strong></small></td>";
                                    }
                                
                                // si no se trara del último movimiento..
                                } else {
                                    
                                    // y es menor de cero, se pone de color rojo.
                                    if($saldoTotal < 0){
                                        echo "<td align=center><small><font color=red>".round($saldoTotal, 2)."€ </font></small></td>";
                                    
                                    //en caso contrario se deja por defecto.    
                                    } else {
                                        echo "<td align=center><small>".round($saldoTotal, 2)." €</small></td>";
                                    }
                                }
                            
                            // si es una devolución se muestra un botón con el número de movimiento.
                            } else {
                            
                                // Si pulsamos sobre le número que aparece junto a los datos se le pasará el indice a borrar.
                                echo "  <td align=center><small><input type=submit name='indice' value='$indice' /></td>";
                                echo "  <input type='hidden' name='eleccion' value='Devolver'/>";
                                $contadorRecibos++;
                            
                            }
                        }
                    }
                }
                 
                // Si pulsamos sobre devolución nos aparecerá un botón con cancelar o con no existen recibos según existan o no.
                if($devolucion){
                    if($contadorRecibos < 1){
                        echo "<tr><td colspan=4 align=center>NO HAY RECIBOS PARA DEVOLVER</td></tr>";
                    }
                    echo "<tr><td colspan=4 align=center><input type='submit' name='eleccion' value='Cancelar'/></td></tr>";
                echo " </tr>";
                }  
            ?>
        </form>
        </table> 
    </div>
    <?php
    // en caso de que lo este, mostramos un mensaje en pantalla.
    } else {
        echo "<div>";
        echo "<table  border='2px' style='margin: 1% auto 1% '>";
        echo "<tr><td><span>NO EXISTEN MOVIMIENTOS, INTRODUZCA UNO PRIMERO.</span></td></tr>";
        echo "</table>";
        echo "</div>";
    }
}


/**
 * Función usada para borrar el movimiento deseado.
 * 
 * @param type $guardarMovimientos le pasamos el array de movimientos.
 * @param type $numeroIndice le pasamos el número de índice que deseamos quitar.
 * 
 * @return type nos devuelve el el array de movimientos modificado.
 */
function devolverMovimiento(&$guardarMovimientos, $numeroIndice){
    
    // Se utiliza para que automaticamente se ordene el array,
    // Se ha extraido de http://php.net/manual/es/function.array-splice.php
    array_splice($guardarMovimientos, $numeroIndice, 1);
    //unset($guardarMovimientos[$numeroIndice]);
    return $guardarMovimientos;
}


/**
 * Función usada para calcular el campo saldo contable.
 * 
 * @param type $saldo le pasamos el saldo que hay en ese momento.
 * @param type $valor le pasamos el índice que debemos sumar.
 * 
 * @return type nos devuelve la suma del saldo.
 */
function calcular_Saldo_Contable(&$saldo, $valor){
    $saldo += $valor['cantidad'];
    return $saldo;
}


/**
 * Función usada para validar los datos de los diferentes campos a rellenar, en principio pensé
 * en hacerlos por separado, pero finalmente para mantener el código lo más "recogido" posible
 * he optado por meterlo todo en la mnisma función.
 * 
 * @param type $fecha le pasamos la fecha para saber si se encuentra bien introducida.
 * @param type $concepto le pasamos el concepto para saber si se encuentra bien introducido.
 * @param type $cantidad le pasamos la cantidad para saber si se encuentra bien introducida.
 * 
 * @return string nos devuelve el mensaje de error si existe con un mensaje que detalla el tipo del mismo.
 */
function validar_Datos($fecha, $concepto, $cantidad){
    
    // Iniciamos el array al que le introduciremos los mensajes de error, en caso de que existan.
    $faltan = "";
    
    // Comprobamos si el campo fecha se encuentra vacío.
    if(empty($fecha)){
        $faltan = $faltan."<span>ERROR: Revise la información a introducir, parece que el campo fecha se encuentran vacía.</span><br/>";
    }
    
    // Comprobamos si el campo fecha dispone de un formato válido.
    if(!validacionFecha($fecha)) {
        $faltan = $faltan."<span>ERROR: Revise la información a introducir, la fecha se encuentra en un formato no válido(dd/mm/yyyy).</span><br/>";
    }
    
    // Comprobamos que el campo cantidad no se encuentra vacio y que es una cantidad.
    if(($cantidad < 0) || !is_numeric($cantidad) || empty($cantidad)) {
        $faltan = $faltan."<span>ERROR: Revise la información a introducir, la cantidad debe ser un número mayor de cero.</span><br/>";
    }
    
    // Comprobamos que el campo concepto no se encuentra vacio.
    if (empty($concepto)){
        $faltan = $faltan."<span>ERROR: Revise la información a introducir, parece que el campo concepto se encuentran vacío.</span><br/>";
    }
    return $faltan;
}


/**
 * Función usada para compobar si se encuentra la fecha en el formato correspondiente
 * ha sido extraido de http://php.net/manual/es/function.checkdate.php

 * 
 * @param type $fecha le pasamos la fecha para saber si su formato es el correcto.
 * @param type $format le pasamos el tipo de formato que deseamos.
 * 
 * @return type nos devuelve si es correcto o no.
 */
function validacionFecha($fecha, $format = 'd/m/Y')
{  
    date_default_timezone_set('Europe/Madrid');
    $d = DateTime::createFromFormat($format, $fecha);
    return $d && $d->format($format) == $fecha;
}
 

function fecha($format = 'd/m/Y'){
    
    date_default_timezone_set('Europe/Madrid'); // Establecemos la zona horaria en España/Madrid
    return date($format);
}

/**
 * Función creada para la cabecera de la web.
 * 
 * @param type $title le pasamos el texto que deseamos que aparezca.
 */
function cabecera($title, $title2)
{
    echo "<center><b><h2>$title</h2></b></center>"; // Muestra el mensaje de cabecera.
    echo "<center><b><h3>$title2</h3></b></center>";
}


/**
 * Función creada para el pie de la página web.
 * 
 * @param type $nombreTarea le pasamos el nombre de la tarea.
 */
function pie($nombreTarea)
{
    //Muestra el mensaje del pie
    echo "<center><small>Rubén Ángel Rodriguez Leyva</br> 
          <center><small>$nombreTarea</br>
          </body>
          </html>";
    exit; 
}

