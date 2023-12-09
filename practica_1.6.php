<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
$DNI="";
$NOMBRE="";
$LOCALIDAD="";
$FECHA_NACIMIENTO="";
$lineas_pagina="10";
$primera="";
$anterior="";
$num_paginas = 1;
$valor_inicial_limit = 0;
$id_eliminar = 0;

if (isset($_GET['id_modificar'])) {
    echo "id_modificar: " . $_GET['id_modificar'];
}

if (isset($_GET['id_eliminar'])) {
    echo "id_eliminar: " . $_GET['id_eliminar'];
}

if (isset($_POST['DNI'])) {
    $DNI = $_POST['DNI'];
}
if (isset($_POST['NOMBRE'])) {
    $NOMBRE = $_POST['NOMBRE'];
}
if (isset($_POST['LOCALIDAD'])) {
    $LOCALIDAD = $_POST['LOCALIDAD'];
}
if (isset($_POST['FECHA_NACIMIENTO'])) {
    $FECHA_NACIMIENTO = $_POST['FECHA_NACIMIENTO'];
}
if (isset($_POST['lineas_pagina'])) {
    $lineas_pagina= $_POST['lineas_pagina'];
}
//Si viene en el post un valor de valor_inicial_limit se considera el mismo , de lo contrario seria 0
if (isset($_POST['valor_inicial_limit'])) {
    $valor_inicial_limit = $_POST['valor_inicial_limit'];
} else {
    $valor_inicial_limit = 0;
}
?>

<!DOCTYPE html>
<html>
<body>

<h2>PRACTICA DI 1.6</h2>

<form method="post" action="practica_1.6.php">
    DNI: <input type="text" name="DNI" value="<?php echo $DNI ?>" />
    Nombre: <input type="text" name="NOMBRE" value="<?php echo $NOMBRE ?>" />
    Localidad: <input type="text" name="LOCALIDAD" value="<?php echo $LOCALIDAD ?>" />
    F.Nacimiento: <input type="text" name="FECHA_NACIMIENTO" value="<?php echo $FECHA_NACIMIENTO  ?>" />
    <input type="submit"  value="Enviar" />
</form>
</br>

<table border="2">
<thead>
        <tr>
            <?php
                    //Se elimina el borde de las celdas de la cabecera de la tabla y se añade la cabecera de cada campo de BBDD
                    echo "<th style='border: none;'><strong>DNI</strong></th>";
                    echo "<th style='border: none;'><strong>APELLIDO 1</strong></th>";
                    echo "<th style='border: none;'><strong>APELLIDO 2</strong></th>";
                    echo "<th style='border: none;'><strong>NOMBRE</strong></th>";
                    echo "<th style='border: none;'><strong>DIRECCION</strong></th>";
                    echo "<th style='border: none;'><strong>LOCALIDAD</strong></th>";
                    echo "<th style='border: none;'><strong>PROVINCIA</strong></th>";
                    echo "<th style='border: none;'><strong>FECHA NAC.</strong></th>";
                    echo "<th style='border: none;'><strong>ACCION</strong></th>";
            ?>
        </tr>
    </thead>
    <tbody >
    <thead>
        <tr>
        <?php 
try {
    $con = new PDO('mysql:host=localhost;dbname=universidad','root','Ortuno1!*');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Controlamos el borrado en caso de que se haya clicado en el link asociado...
    //Se usa el metodo GET porque estamos pidiendo datos al servidor , no midificando datos en el mismo (para lo ual habría que usar POST)
    if (isset($_GET['id_eliminar'])) {
        $id_eliminar = $_GET['id_eliminar'];
    
        $sql_eliminar = "DELETE FROM universidad.alumno WHERE dni = :id_eliminar";
        $params_eliminar = array(":id_eliminar" => $id_eliminar);
    
        if ($con->prepare($sql_eliminar)->execute($params_eliminar)) {
            echo "Registro eliminado correctamente";
        } else {
            echo "Error al eliminar el registro: " . $con->errorInfo();
        }
    }

       //Controlamos la modificación en caso de que se haya clicado en el link asociado...
       //Se concatena al nombre la cadena "_MODIF"
       if (isset($_GET['id_modificar'])) {
        $id_modificar = $_GET['id_modificar'];
    
        $sql_modificar = "UPDATE universidad.alumno set nombre=concat(nombre,'_MODIF') WHERE dni = :id_modificar";
        $params_modificar = array(":id_modificar" => $id_modificar);
    
        if ($con->prepare($sql_modificar)->execute($params_modificar)) {
            echo "Registro modificado correctamente";
        } else {
            echo "Error al modificar el registro: " . $con->errorInfo();
        }
    }

    $params = array();
    $sql = 'SELECT * FROM universidad.alumno WHERE true';
    $sql_total = 'SELECT count(*) FROM universidad.alumno';

    if (isset($DNI) && !empty($DNI)) {
        $sql .= " AND DNI = :DNI";
        $params[":DNI"] = $DNI;
    }
    if (isset($NOMBRE) && !empty($NOMBRE)) {
        $sql .= " AND NOMBRE LIKE :NOMBRE";
        $params[":NOMBRE"] = "%".$NOMBRE."%";
    }
    if (isset($LOCALIDAD) && !empty($LOCALIDAD)) {
        $sql .= " AND LOCALIDAD LIKE :LOCALIDAD";
        $params[":LOCALIDAD"] = "%".$LOCALIDAD."%";
    }
    if (isset($FECHA_NACIMIENTO) && !empty($FECHA_NACIMIENTO)) {
        $sql .= " AND FECHA_NACIMIENTO = :FECHA_NACIMIENTO";
        $params[":FECHA_NACIMIENTO"] = $FECHA_NACIMIENTO;
    }
        
    //Se añade el valor inicial de limit y el número de paginas antes de hacer la consulta
    $sql .= " LIMIT $valor_inicial_limit , $lineas_pagina";
    
    $stmt = $con->prepare($sql);
    $stmt->execute($params);
   
    $total_stmt = $con->prepare($sql_total);
    $total_stmt->execute();
    $total = $total_stmt->fetchColumn();

    // Calcular el número de páginas después de obtener los resultados paginados
    $num_paginas = ceil($total / $lineas_pagina);
    if (isset($_POST['primera'])) {
        $valor_inicial_limit = 0;
    } elseif (isset($_POST['anterior'])) {
        $valor_inicial_limit = max(0, $valor_inicial_limit - $lineas_pagina);
    } elseif (isset($_POST['siguiente'])) {
        $valor_inicial_limit = min($total - $lineas_pagina, $valor_inicial_limit + $lineas_pagina);
    } elseif (isset($_POST['ultima'])) {
        $valor_inicial_limit = max(0, $total - $lineas_pagina);
    }

    while ($datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($datos as $key => $value) {
            echo "<td>".$value."</td>";
        }
      // Agrega botones de "Eliminar" y "Modificar" en la última columna que recogan el id a eliminar/modificar
        echo "<td><a href='practica_1.6.php?id_eliminar=".$datos['DNI']."'>Eliminar</a> <a href='practica_1.6.php?id_modificar=".$datos['DNI']."'>Modificar</a></td>";
        echo "</tr>";
    }
    
} catch (PDOException $e) {
    echo 'Error de conexión con BBDD'.$e->getMessage();
}
?>
    </tbody>
</table>
</br>
<form method="post" action="practica_1.6.php">
   <input type="submit" name="primera" value="<<" />
   <input type="submit" name="anterior" value="<" />
   <input type="text"   name="lineas_pagina"  value="<?php echo $lineas_pagina ?>"/>
   <input type="submit" name="siguiente" value=">" />
   <input type="submit" name="ultima" value=">>" />
   <!-- Se añade el elemento hidden valor _inicial_limit que se envia al propio formulario -->
   <input type="hidden" name="valor_inicial_limit" value="<?php echo $valor_inicial_limit; ?>" />
</form>
</br>
<!-- Se muestra la página actual y el total de páginas -->
<span><strong>Total de registros: <?php echo $total; ?> Página: <?php echo ceil($valor_inicial_limit / $lineas_pagina) + 1; ?>/<?php echo $num_paginas; ?></strong></span>
</body>
</html>