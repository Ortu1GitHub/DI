
<!DOCTYPE html>
<html>
<body>

<h2>PRACTICA DI 1.3</h2>

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
            <?php 
            try
            {
            $con=new PDO('mysql:host:localhost;dbname=universidad','root','Ortuno1!*');
            $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            //echo 'Conexión OK con BBDD';
            $stmt=$con->prepare('SELECT * FROM universidad.alumno');
            $stmt->execute();
            while($datos=$stmt->fetch(PDO::FETCH_ASSOC)){
                //echo $datos[0].' '.$datos[1].' '.$datos[2].' '.$datos[3].' '.$datos[4].' '.$datos[5].' '.$datos[6].' '.$datos[7].'</br>';
                echo "<tr>";
                        // Se añade una celda por cada campo de cada registro de $datos , que son 8 en total
                        foreach($datos as $key =>$value){
                            echo "<td>".$value."</td>";
                        }
                    echo "</tr>";
            }
            
            }catch(PDOException $e) {
            echo 'Error de conexión con BBDD'.$e->getMessage();
            }
            ?>
    </tbody>
</table>
</body>
</html>