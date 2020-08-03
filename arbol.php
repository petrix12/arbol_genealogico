<?php
include("conexion.php");
header("Content-Type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>�rbol</title>
	<style>
		* {
			margin: 0;
			padding: 0;
		}

		.tree ul {
			padding-top: 20px;
			position: relative;

			transition: all 0.5s;
			-webkit-transition: all 0.5s;
			-moz-transition: all 0.5s;
		}

		.tree li {
			float: left;
			text-align: center;
			list-style-type: none;
			position: relative;
			padding: 20px 5px 0 5px;

			transition: all 0.5s;
			-webkit-transition: all 0.5s;
			-moz-transition: all 0.5s;
		}

		/*We will use ::before and ::after to draw the connectors*/

		.tree li::before,
		.tree li::after {
			content: '';
			position: absolute;
			top: 0;
			right: 50%;
			border-top: 1px solid #ccc;
			width: 50%;
			height: 20px;
		}

		.tree li::after {
			right: auto;
			left: 50%;
			border-left: 1px solid #ccc;
		}

		/*We need to remove left-right connectors from elements without 
any siblings*/
		.tree li:only-child::after,
		.tree li:only-child::before {
			display: none;
		}

		/*Remove space from the top of single children*/
		.tree li:only-child {
			padding-top: 0;
		}

		/*Remove left connector from first child and 
right connector from last child*/
		.tree li:first-child::before,
		.tree li:last-child::after {
			border: 0 none;
		}

		/*Adding back the vertical connector to the last nodes*/
		.tree li:last-child::before {
			border-right: 1px solid #ccc;
			border-radius: 0 5px 0 0;
			-webkit-border-radius: 0 5px 0 0;
			-moz-border-radius: 0 5px 0 0;
		}

		.tree li:first-child::after {
			border-radius: 5px 0 0 0;
			-webkit-border-radius: 5px 0 0 0;
			-moz-border-radius: 5px 0 0 0;
		}

		/*Time to add downward connectors from parents*/
		.tree ul ul::before {
			content: '';
			position: absolute;
			top: 0;
			left: 50%;
			border-left: 1px solid #ccc;
			width: 0;
			height: 20px;
		}

		.tree li a {
			border: 1px solid #ccc;
			padding: 5px 10px;
			text-decoration: none;
			color: #666;
			font-family: arial, verdana, tahoma;
			font-size: 11px;
			display: inline-block;

			border-radius: 5px;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;

			transition: all 0.5s;
			-webkit-transition: all 0.5s;
			-moz-transition: all 0.5s;
		}

		/*Time for some hover effects*/
		/*We will apply the hover effect the the lineage of the element also*/
		.tree li a:hover,
		.tree li a:hover+ul li a {
			background: #c8e4f8;
			color: #000;
			border: 1px solid #94a0b4;
		}

		/*Connector styles on hover*/
		.tree li a:hover+ul li::after,
		.tree li a:hover+ul li::before,
		.tree li a:hover+ul::before,
		.tree li a:hover+ul ul::before {
			border-color: #94a0b4;
		}

		.floater {
			float: right;
		}

		.tbl-container {
			overflow: hidden;
		}

		.tbl-container>table {
			width: 100%;
		}
	</style>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body bgcolor="white">
	<?php
	if (!empty($_POST['NombreCliente'])) $NombreCliente = $_POST['NombreCliente'];
	else $NombreCliente = '';
	if (!empty($_POST['EnlaceDoc'])) $EnlaceDoc = $_POST['EnlaceDoc'];
	else $EnlaceDoc = '';
	if (!empty($_POST['IDPrincipal'])) $IDPrincipal = $_POST['IDPrincipal'];
	else $IDPrincipal = 0;
	if (!empty($_POST['IDCliente'])) $IDCliente = $_POST['IDCliente'];
	else $IDCliente = 0;
	$NombrePrincipal = '';
	$Sexo = '';
	$IDPadre = 0;
	$IDMadre = 0;
	$IDAbueloP = 0;
	$IDAbuelaP = 0;
	$IDAbueloM = 0;
	$IDAbuelaM = 0;
	?>
	<hr />
	<h2 align="center">&Aacuterbol Geneal&oacutegico</h2>
	<?php
	echo '<h3 align="center"><a href="' . $EnlaceDoc . '" target="_blank">';
	echo '<img src="imagenes/documentos.png" width="25" height="25"/></a>';
	echo ' ' . $NombreCliente . '</h3>';
	?>
	<hr />
	<div class="container">
		<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="center">
			<div class="modal-body" width="100%">
				<table bgcolor="white">
					<tbody>
						<tr>
							<td>
								<div class="span2 fileupload fileupload-new pull-left" data-provides="fileupload">
									<div class="fileupload-preview thumbnail" style="width: 100%; height: 100%;"></div>
									<div>
										<div class="tree">
											<ul>
												<li>
													<a>
														<!-- <b>Principal</b> -->
														<br>
														<?php
														$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDPrincipal . " AND IDCliente =" . $IDCliente);
														$row = mysqli_fetch_assoc($sql);
														if (mysqli_num_rows($sql) > 0) {
															$Sexo = $row['Sexo'];
															if (!is_null($Sexo)) {
																if ($Sexo == "Hombre") {
																	echo '<img src="imagenes/hombre.png" width="25" height="25">';
																}
																if ($Sexo == "Mujer") {
																	echo '<img src="imagenes/mujer.png" width="25" height="25">';
																}
															}
															$NombrePrincipal = $row['Nombres'] . ' ' . $row['Apellidos'];
															$IDPadre = $row['IDPadre'];
															if (is_null($IDPadre)) {
																$IDPadre = 0;
															}
															$IDMadre = $row['IDMadre'];
															if (is_null($IDMadre)) {
																$IDMadre = 0;
															}
															echo '<p style="color:blue";><b>' . $row['Nombres'] . ', ' . $row['Apellidos'] . '</b></p><hr>';
															// Generaci�n
															if (!is_null($row['Generacion'])) {
																echo '<p style="color:gray";><b>Generaci&oacuten: ' . $row['Generacion'] . '.</b></p>';
															}
															// INFORMACI�N DE NACIMIENTO
															// Fecha de nacimiento
															if (!is_null($row['AnhoNac']) or !is_null($row['MesNac']) or !is_null($row['DiaNac'])) {
																echo '<b><br>Fecha de Nacimiento: </b><br>';
																if (!is_null($row['AnhoNac'])) {
																	echo 'A&ntildeo: ' . $row['AnhoNac'] . '. ';
																}
																if (!is_null($row['MesNac'])) {
																	echo 'Mes: ' . $row['MesNac'] . '. ';
																}
																if (!is_null($row['DiaNac'])) {
																	echo 'Mes: ' . $row['DiaNac'] . '. ';
																}
															}
															// Lugar de nacimiento
															if (!is_null($row['LugarNac']) or !is_null($row['PaisNac'])) {
																echo '<br><b>Lugar de Nacimiento: </b><br>';
																if (!is_null($row['LugarNac'])) {
																	echo 'Ciudad: ' . $row['LugarNac'] . '.<br>';
																}
																if (!is_null($row['PaisNac'])) {
																	echo 'Pa&iacutes: ' . $row['PaisNac'] . '.';
																}
															}

															// INFORMACI�N DE BAUTIZO
															// Fecha de bautizo
															if (!is_null($row['AnhoBtzo']) or !is_null($row['MesBtzo']) or !is_null($row['DiaBtzo'])) {
																echo '<b><br>Fecha de Bautizo: </b><br>';
																if (!is_null($row['AnhoBtzo'])) {
																	echo 'A&ntildeo: ' . $row['AnhoBtzo'] . '. ';
																}
																if (!is_null($row['MesBtzo'])) {
																	echo 'Mes: ' . $row['MesBtzo'] . '. ';
																}
																if (!is_null($row['DiaBtzo'])) {
																	echo 'Mes: ' . $row['DiaBtzo'] . '. ';
																}
															}
															// Lugar de bautizo
															if (!is_null($row['LugarBtzo']) or !is_null($row['PaisBtzo'])) {
																echo '<br><b>Lugar de Bautizo: </b><br>';
																if (!is_null($row['LugarBtzo'])) {
																	echo 'Ciudad: ' . $row['LugarBtzo'] . '.<br>';
																}
																if (!is_null($row['PaisBtzo'])) {
																	echo 'Pa&iacutes: ' . $row['PaisBtzo'] . '.';
																}
															}

															// INFORMACI�N DE MATRIMONIO
															// Fecha de matrimonio
															if (!is_null($row['AnhoMatr']) or !is_null($row['MesMatr']) or !is_null($row['DiaMatr'])) {
																echo '<b><br>Fecha de Matrimonio: </b><br>';
																if (!is_null($row['AnhoMatr'])) {
																	echo 'A&ntildeo: ' . $row['AnhoMatr'] . '. ';
																}
																if (!is_null($row['MesMatr'])) {
																	echo 'Mes: ' . $row['MesMatr'] . '. ';
																}
																if (!is_null($row['DiaMatr'])) {
																	echo 'Mes: ' . $row['DiaMatr'] . '. ';
																}
															}
															// Lugar de matrimonio
															if (!is_null($row['LugarMatr']) or !is_null($row['PaisMatr'])) {
																echo '<br><b>Lugar de Matrimonio: </b><br>';
																if (!is_null($row['LugarMatr'])) {
																	echo 'Ciudad: ' . $row['LugarMatr'] . '.<br>';
																}
																if (!is_null($row['PaisMatr'])) {
																	echo 'Pa&iacutes: ' . $row['PaisMatr'] . '.';
																}
															}

															// INFORMACI�N DE DEFUNCI�N
															// Fecha de defunci�n
															if (!is_null($row['AnhoDef']) or !is_null($row['MesDef']) or !is_null($row['DiaDef'])) {
																echo '<b><br>Fecha de Defunci&oacuten: </b><br>';
																if (!is_null($row['AnhoDef'])) {
																	echo 'A&ntildeo: ' . $row['AnhoDef'] . '. ';
																}
																if (!is_null($row['MesDef'])) {
																	echo 'Mes: ' . $row['MesDef'] . '. ';
																}
																if (!is_null($row['DiaDef'])) {
																	echo 'Mes: ' . $row['DiaDef'] . '. ';
																}
															}
															// Lugar de defunci�n
															if (!is_null($row['LugarDef']) or !is_null($row['PaisDef'])) {
																echo '<br><b>Lugar de Defunci&oacuten: </b><br>';
																if (!is_null($row['LugarDef'])) {
																	echo 'Ciudad: ' . $row['LugarDef'] . '.<br>';
																}
																if (!is_null($row['PaisDef'])) {
																	echo 'Pa&iacutes: ' . $row['PaisDef'] . '.';
																}
															}

															// Observaciones
															$Observaciones = NULL;
															if (!is_null($row['Observaciones'])) {
																$Observaciones = $row['Observaciones'];
															}
															// Exactitud de los datos de nacimiento
															if (!is_null($row['DatosExactosNac'])) {
																if (!is_null($Observaciones)) {
																	$Observaciones = $Observaciones . '<br>';
																}
																if ($row['DatosExactosNac'] == "Si") {
																	$Observaciones = $Observaciones . 'Los datos de nacimiento son exactos.';
																}
																if ($row['DatosExactosNac'] == "No") {
																	$Observaciones = $Observaciones . 'Los datos de nacimiento no son exactos.';
																}
															}
															// Exactitud de los datos de bautizo
															if (!is_null($row['DatosExactosBtzo'])) {
																if (!is_null($Observaciones)) {
																	$Observaciones = $Observaciones . '<br>';
																}
																if ($row['DatosExactosBtzo'] == "Si") {
																	$Observaciones = $Observaciones . 'Los datos de bautizo son exactos.';
																}
																if ($row['DatosExactosBtzo'] == "No") {
																	$Observaciones = $Observaciones . 'Los datos de bautizo no son exactos.';
																}
															}
															// Exactitud de los datos de matrimonio
															if (!is_null($row['DatosExactosMatr'])) {
																if (!is_null($Observaciones)) {
																	$Observaciones = $Observaciones . '<br>';
																}
																if ($row['DatosExactosMatr'] == "Si") {
																	$Observaciones = $Observaciones . 'Los datos de matrimonio son exactos.';
																}
																if ($row['DatosExactosMatr'] == "No") {
																	$Observaciones = $Observaciones . 'Los datos de matrimonio no son exactos.';
																}
															}
															// Exactitud de los datos de defunci�n
															if (!is_null($row['DatosExactosDef'])) {
																if (!is_null($Observaciones)) {
																	$Observaciones = $Observaciones . '<br>';
																}
																if ($row['DatosExactosDef'] == "Si") {
																	$Observaciones = $Observaciones . 'Los datos de defunci&oacuten son exactos.';
																}
																if ($row['DatosExactosDef'] == "No") {
																	$Observaciones = $Observaciones . 'Los datos de defunci&oacuten no son exactos.';
																}
															}
															// Exactitud de los datos de defunci�n
															if (!is_null($row['Vive'])) {
																if (!is_null($Observaciones)) {
																	$Observaciones = $Observaciones . '<br>';
																}
																if ($row['Vive'] == "Si") {
																	$Observaciones = $Observaciones . 'Vive.';
																}
																if ($row['Vive'] == "No") {
																	$Observaciones = $Observaciones . 'No vive.';
																}
															}
														} else {
															echo 'Desconocido';
														}
														?>
													</a>
													<ul>
														<li>
															<a>
																<b>Padre</b>
																<br>
																<?php
																$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDPadre . " AND IDCliente =" . $IDCliente);
																$row = mysqli_fetch_assoc($sql);
																if (mysqli_num_rows($sql) > 0) {
																	$IDAbueloP = $row['IDPadre'];
																	if (is_null($IDAbueloP)) {
																		$IDAbueloP = 0;
																	}
																	$IDAbuelaP = $row['IDMadre'];
																	if (is_null($IDAbuelaP)) {
																		$IDAbuelaP = 0;
																	}
																	echo $row['Nombres'] . ', ' . $row['Apellidos'];
																	if (!is_null($row['AnhoNac'])) {
																		echo '<br>Nacimiento: ' . $row['AnhoNac'];
																	}
																	if (!is_null($row['AnhoDef'])) {
																		echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																	}

																	echo '<form action="arbol.php" method="post" id="idPadre">';
																	echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																	echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																	echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																	echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																	echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																	echo '</form>';
																} else {
																	echo 'Desconocido';
																}
																?>
															</a>
															<ul>
																<li>
																	<a>
																		<b>Abuelo Paterno</b>
																		<br>
																		<?php
																		$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDAbueloP . " AND IDCliente =" . $IDCliente);
																		$row = mysqli_fetch_assoc($sql);
																		if (mysqli_num_rows($sql) > 0) {
																			echo $row['Nombres'] . ', ' . $row['Apellidos'];
																			if (!is_null($row['AnhoNac'])) {
																				echo '<br>Nacimiento: ' . $row['AnhoNac'];
																			}
																			if (!is_null($row['AnhoDef'])) {
																				echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																			}

																			echo '<form action="arbol.php" method="post" id="idAbueloP">';
																			echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																			echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																			echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																			echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																			echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																			echo '</form>';
																		} else {
																			echo 'Desconocido';
																		}
																		?>
																	</a>
																</li>
																<li>
																	<a>
																		<b>Abuela Paterna</b>
																		<br>
																		<?php
																		$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDAbuelaP . " AND IDCliente =" . $IDCliente);
																		$row = mysqli_fetch_assoc($sql);
																		if (mysqli_num_rows($sql) > 0) {
																			echo $row['Nombres'] . ', ' . $row['Apellidos'];
																			if (!is_null($row['AnhoNac'])) {
																				echo '<br>Nacimiento: ' . $row['AnhoNac'];
																			}
																			if (!is_null($row['AnhoDef'])) {
																				echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																			}


																			echo '<form action="arbol.php" method="post" id="idAbuelaP">';
																			echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																			echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																			echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																			echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																			echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																			echo '</form>';
																		} else {
																			echo 'Desconocida';
																		}
																		?>
																	</a>
																</li>
															</ul>
														</li>
														<li>
															<a>
																<b>Madre</b>
																<br>
																<?php
																$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDMadre . " AND IDCliente =" . $IDCliente);
																$row = mysqli_fetch_assoc($sql);
																if (mysqli_num_rows($sql) > 0) {
																	$IDAbueloM = $row['IDPadre'];
																	if (is_null($IDAbueloM)) {
																		$IDAbueloM = 0;
																	}
																	$IDAbuelaM = $row['IDMadre'];
																	if (is_null($IDAbuelaM)) {
																		$IDAbuelaM = 0;
																	}
																	echo $row['Nombres'] . ', ' . $row['Apellidos'];
																	if (!is_null($row['AnhoNac'])) {
																		echo '<br>Nacimiento: ' . $row['AnhoNac'];
																	}
																	if (!is_null($row['AnhoDef'])) {
																		echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																	}

																	echo '<form action="arbol.php" method="post" id="idMadre">';
																	echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																	echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																	echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																	echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																	echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																	echo '</form>';
																} else {
																	echo 'Desconocida';
																}
																?>
															</a>
															<ul>
																<li>
																	<a>
																		<b>Abuelo Materno</b>
																		<br>
																		<?php
																		$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDAbueloM . " AND IDCliente =" . $IDCliente);
																		$row = mysqli_fetch_assoc($sql);
																		if (mysqli_num_rows($sql) > 0) {
																			echo $row['Nombres'] . ', ' . $row['Apellidos'];
																			if (!is_null($row['AnhoNac'])) {
																				echo '<br>Nacimiento: ' . $row['AnhoNac'];
																			}
																			if (!is_null($row['AnhoDef'])) {
																				echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																			}

																			echo '<form action="arbol.php" method="post" id="idAbueloM">';
																			echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																			echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																			echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																			echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																			echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																			echo '</form>';
																		} else {
																			echo 'Desconocido';
																		}
																		?>
																	</a>
																</li>
																<li>
																	<a>
																		<b>Abuela Materna</b>
																		<br>
																		<?php
																		$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE ID =" . $IDAbuelaM . " AND IDCliente =" . $IDCliente);
																		$row = mysqli_fetch_assoc($sql);
																		if (mysqli_num_rows($sql) > 0) {
																			echo $row['Nombres'] . ', ' . $row['Apellidos'];
																			if (!is_null($row['AnhoNac'])) {
																				echo '<br>Nacimiento: ' . $row['AnhoNac'];
																			}
																			if (!is_null($row['AnhoDef'])) {
																				echo '<br>Defunci&oacuten: ' . $row['AnhoDef'];
																			}

																			echo '<form action="arbol.php" method="post" id="idAbuelaM">';
																			echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
																			echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
																			echo '<input type="hidden" name="IDCliente" value=' . $row['IDCliente'] . ' />';
																			echo '<input type="hidden" name="IDPrincipal" value=' . $row['ID'] . ' />';
																			echo '<input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/>';
																			echo '</form>';
																		} else {
																			echo 'Desconocida';
																		}
																		?>
																		</select>
																	</a>
																</li>
															</ul>
														</li>
													</ul>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<table bgcolor="white">
					<tbody>
						<tr>
							<td>
								<div class="span2 fileupload fileupload-new pull-left" data-provides="fileupload">
									<div class="fileupload-preview thumbnail" style="width: 100%; height: 100%;"></div>
									<div>
										<?php
										echo '<br><b style="font-size:13px">Hijos de ' . $NombrePrincipal . '</b><br>';
										if ($Sexo == 'Hombre') {
											$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE IDPadre=" . $IDPrincipal . " AND IDCliente =" . $IDCliente);
										} else {
											$sql = mysqli_query($con, "SELECT * FROM genealogias WHERE IDMadre=" . $IDPrincipal . " AND IDCliente =" . $IDCliente);
										}
										if (mysqli_num_rows($sql) > 0) {
											echo '<form action="arbol.php" method="post" id="idHijos">';
											echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
											echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
											$i = 0;
											while ($row = mysqli_fetch_assoc($sql)) {
												if ($i == 0) {
													echo '<input type="radio" name="IDPrincipal" value="' . $row['ID'] . '" checked><a style="font-size:13px">  ' . $row['Nombres'] . ', ' . $row['Apellidos'] . '</a><br>';
												} else {
													echo '<input type="radio" name="IDPrincipal" value="' . $row['ID'] . '"><a style="font-size:13px">  ' . $row['Nombres'] . ', ' . $row['Apellidos'] . '</a><br>';
												}
												$i++;
											}
											echo '<input type="hidden" name="IDCliente" value=' . $IDCliente . ' />';
											echo '<h3 align="center"><input type=image src="imagenes/subir.png" width="25" height="25" name="submit" value="Subir" align="center"/></h3>';
											echo '</form>';
										} else {
											echo '<a style="font-size:13px">Sin hijos</a>';
										}
										?>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="modal-footer">
				<?php
				// Observaciones
				if (!is_null($Observaciones)) {
					echo '<p style="font-size:13px">';
					echo '<br><b>Observaciones de ' . $NombrePrincipal . ': </b><br>' . $Observaciones;
					echo '</p>';
				}

				// Formulario Restaurar			
				echo '<form action="arbol.php" method="post" id="idRestaurar">';
				echo '<input type="hidden" name="NombreCliente" value="' . $NombreCliente . '" />';
				echo '<input type="hidden" name="EnlaceDoc" value="' . $EnlaceDoc . '" />';
				echo '<input type="hidden" name="IDCliente" value=' . $IDCliente . ' />';
				echo '<input type="hidden" name="IDPrincipal" value=1 />';
				echo '<br><input type="submit" name="submit" value=" Restaurar " />';
				echo '</form>';
				?>
			</div>
		</div>
	</div>
</body>

</html>