<?php
include("conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Clientes</title>

	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">

	<style>
		.content {
			margin-top: 80px;
		}
		* {
			font-family:Tahoma;
			font-size: 11px;
		}
	</style>

</head>
<body>
	<div class="container">
		<h2>Casos Clientes Sefar</h2>
		<hr />
		<div class="content">
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>	
					<th>Sexo</th>
                    <th>ID</th>
					<th>Nombres</th>
					<th>Apellidos</th>
                    <th>País</th>
                    <th>Año de Nac.</th>
					<th>Familiar</th>
					<th>Árbol</th>
					<th>Documentos</th>
                    <th>Editar</th>
				</tr>
				<?php
				$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID = 1 ORDER BY IDCliente DESC");
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td>';
							$Sexo = $row['Sexo'];
							if (!is_null($Sexo)){
								if($Sexo == "Hombre"){
									echo '<img src="imagenes/hombre.png" width="25" height="25">';
								}
								if($Sexo == "Mujer"){
									echo '<img src="imagenes/mujer.png" width="25" height="25">';
								}																
							}
						echo '
							</td>
							<td>'.$row['IDCliente'].'</td>
							<td>'.$row['Nombres'].'</td>
							<td>'.$row['Apellidos'].'</td>
                            <td>'.$row['PaisNac'].'</td>
                            <td>'.$row['AnhoNac'].'</td>
							<td>'.$row['Familiaridad'].'</td>
							<td>';
						echo '<form action="arbol.php" method="post" id="idCliente">';
						echo '<input type="hidden" name="IDCliente" value='.$row['IDCliente'].' />';
						echo '<input type="hidden" name="IDPrincipal" value='.$row['ID'].' />';
						echo '<input type="hidden" name="NombreCliente" value="'.$row['Nombres'].' '.$row['Apellidos'].'" />';
						echo '<input type="hidden" name="EnlaceDoc" value="'.$row['Enlace'].'" />';
						echo '<br><input type="image" name="submit" src="imagenes/arbol.png" width="25" height="25" alt="Enviar formulario">';
						echo '</form>';
						echo '</td>
							<td><a href="'.$row['Enlace'].'" target="_blank"><img src="imagenes/enlace.png" width="25" height="25" alt=Ir a documento/></a></td>
							<td>Editar</td>';
						echo '</tr>';
						$no++;
					}
				}
				?>
			</table>
			</div>
		</div>
	</div>
	<div>
		<p>&copy; Sefar <?php echo date("Y");?></p>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
