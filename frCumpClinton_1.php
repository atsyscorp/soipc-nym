<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	date_default_timezone_set('America/Bogota');
	
	include("General.php");
	//---------------------------------------------------
	$var[1]=$_GET['var1']; //--> Usuario
	//---------------------------------------------------
	$link=Conectarse();
	//----------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	//Captura variables
	
	$sListId = date("YmdHis");
	$sdate = date("Y-m-d");	
	//-------------------------------------------------
	//$file = fopen("https://www.treasury.gov/ofac/downloads/sdn.csv","r");
	$file = fopen("https://soipcnym.com/sFormats/SDNv4.csv","r");

	//------------------------------
	//Elimina registros de lista
	$strSQL = "DELETE From Lista_Clinton_1";	
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Actualizar lista Clinton</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/frCumpClinton.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<script>
	ValWinFrame();
</script>
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:520px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 210)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 516, 2, '', '', 1, '', 1, 'hidden')?>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%">
				<div style="width:90%; margin:auto; padding:20px 0">
					<div>
						<div id="dInfTit" style="font-size:14px; margin-top:10px; float:left" class="fgreen">Importando lista Clinton desde OFAC. Por favor paciencia...</div>
						<img id="imImpProg" src="images/wait.gif" style="width:35px; height:auto; float:right">
						<div style="clear:both"></div>
					</div>
					<div style="margin-top:10px;" class="fcont">
						<div class="bgcol_4 drod_1 dlin_3" style="margin-bottom:10px; padding:12px 7px; box-sizing:border-box">Total Registros: <span id="sRegs">Calculando...</span></div>
					</div>
					<div id="dContinuar" style="display:none; margin-top:10px">
						<input name="btaccept" id="btaccept" type="button" value="Regresar" class="btcont" style="width:90px;" onclick="Back_List('<?=$var[1]?>')" />
					</div>			
					<?php
					//Guardado de registros
						$icont = 0;
						$iregs = 0;
						while(! feof($file)){
							$freg = fgetcsv($file, 0, ',');
							$regval = count($freg);
							if($regval >= 2){	//Valida que no sea un registro raro

								$initialInput = str_replace(".", "", $freg[11]);
								$initialInput = str_replace("-","",$initialInput);
								$initialInput = str_replace("'","",$initialInput);

								$initialName = str_replace("'",'', $freg[1]);

								$strSQ2 = "INSERT INTO Lista_Clinton_1 VALUES ('".$freg[0]."', '".$initialName."', '".$initialInput."')";

								mysqli_query($link, $strSQ2) or die(mysqli_error($link)); 
								$iregs++;
								$icont++;

							}
							//------------------------------------
							//Contador de registros insertados
							if($icont == 100) {
								$icont = 0;
								?>
								<script>
									InnerCtr('sRegs', '<?=$iregs?>')
								</script>
								<?php 
							}
						}

						//Cierra archivo
						fclose($file);
						//----------------------------------------
						//Hace registro en historia
						$strSQ1 = "INSERT INTO Actualizaciones_Lista_Clinton VALUES('".$sListId."','".$var[1]."','".$sdate."','".$iregs."')";
						mysqli_query($link, $strSQ1) or die(mysqli_error($link));
					?>
				</div>
			</div>
			<script>
				InnerCtr('sRegs', '<?=$iregs?>')
				document.getElementById('imImpProg').style.display = 'none';
				document.getElementById('dContinuar').style.display = 'block';
				InnerCtr('dInfTit', 'Excelente! Importación lista Clinton terminada');
			</script>
		</div>
	</div>
</div>
</body>
</html>
