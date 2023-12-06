<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
$rows=2;
$cols=2;

if (isset($_POST['rows'])) {
    $rows = $_POST['rows'];
}

if (isset($_POST['cols'])) {
    $cols = $_POST['cols'];
}
?>
<!DOCTYPE html>
<html>
<body>

<h2>PRACTICA DI 1.2</h2>

<form method="post" action="practica_1.2.php">
    Filas: <input type="text" name="rows" value="<?php echo $rows ?>" />
    Columnas: <input type="text" name="cols" value="<?php echo $cols ?>" />
    <input type="submit"  value="Enviar" />
</form>
</br>
</br>

<?php

?>

<table border="2">
    <thead>
        <tr>
            <?php
                for ($i=1; $i<=$cols;$i++){
                    //Se elimina el borde de las celdas de la cabecera de la tabla
                    echo "<th style='border: none;'><strong> Campo ".($i)."</strong> </th>";
                }
            ?>
        </tr>
    </thead>
    <tbody >
            <?php 
              for ($i=1; $i<=$rows;$i++){
                echo "<tr>";
                for ($j=1; $j<=$cols;$j++){
                    echo "<td>fila".($i)." columna".($j)."</td>";
                 }
                 echo "</tr>";
            }
            
            ?>
    </tbody>
</table>



</body>
</html>