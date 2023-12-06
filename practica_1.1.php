<!DOCTYPE html>
<html>
<body>

<h1>PRACTICA DI 1.1</h1>

<?php
$rows=6;
$cols=4;
?>

<table border="2">
    <thead>
        <tr>
            <?php
                for ($i=1; $i<=$cols;$i++){
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