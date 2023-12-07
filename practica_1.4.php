<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
$DNI="";
$NOMBRE="";
$LOCALIDAD="";
$FECHA_NACIMIENTO="";

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
?>

<!DOCTYPE html>
<html>
<body>

<h2>PRACTICA DI 1.4</h2>

<form method="post" action="practica_1.4.php">
    DNI: <input type="text" name="DNI" value="<?php echo $DNI ?>" />
    Nombre: <input type="text" name="NOMBRE" value="<?php echo $NOMBRE ?>" />
    Localidad: <input type="text" name="LOCALIDAD" value="<?php echo $LOCALIDAD ?>" />
    F.Nacimiento: <input type="text" name="FECHA_NACIMIENTO" value="<?php echo $FECHA_NACIMIENTO  ?>" />
    <input type="submit"  value="Enviar" />
</form>
</br>
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

    $params = array();
    $sql = 'SELECT * FROM universidad.alumno WHERE true';

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
    echo $sql;
    $stmt = $con->prepare($sql);
    $stmt->execute($params);

    while ($datos = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($datos as $key => $value) {
            echo "<td>".$value."</td>";
        }
        echo "</tr>";
    }
} catch (PDOException $e) {
    echo 'Error de conexión con BBDD'.$e->getMessage();
}
?>
    </tbody>
</table>
</body>
</html>