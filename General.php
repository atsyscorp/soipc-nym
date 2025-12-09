<?php
//Archivo clases generales
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
require_once 'inc/params.php';
require_once 'siigo/class.siigo.php';
//---------------------------------------------------------------------
//Variables Publicas
$sCaja; //Numero de caja
$sGenSet; //Conf general
$sSucSet; //Configuracion de la sucursal
$sExcSet;	//Conf cambiaria
$sTaxSet;	//Conf impuestos
$sPriCon; //Impresion formato conocimiento de cliente
$sPriCon = 'NO';
$sUserName;	//Nombre de usuario
$sUserCargo;	//Cargo --> Roll
$sUserAcces; //Niveles de acceso
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = (isset($_GET['sFun'])) ? $_GET['sFun'] : '';
switch ($sFun){
	case "RegCount": $calFun = RegCount(); break;
	case "Gen_Accept": $calFun = Gen_Accept(); break;
	case "Gen_Find": $calFun = Gen_Find(); break;
	case "Gen_Find_Field": $calFun = Gen_Find_Field(); break;
	case "Gen_Find_Field_Clinton": $calFun = Gen_Find_Field_Clinton(); break;
	case "Gen_Modif": $calFun = Gen_Modif(); break;
	case "Gen_Delete": $calFun = Gen_Delete(); break;
	case "ActCaja": $calFun = ActCaja(); break;
	case "AddSerie": $calFun = AddSerie(); break;
	case "AddSerieNew": $calFun = AddSerieNew(); break;
	case "Gen_Update_Field": $calFun = Gen_Update_Field(); break;
	case "SiplaOps": $calFun = SiplaOps(); break;
	case "AddCorreo": $calFun = AddCorreo(); break;
	case "SaveInvoiceToSend": $calFun = SaveInvoiceToSend(); break;
	case "SaveInvoiceBuyToSend": $calFun = SaveInvoiceBuyToSend(); break;
	default:
}
//-----------------------------------------------------------------
//Separador decimal y de miles
$GLdecsepa = '.';
$GLmilsepa = ',';
//-----------------------------------------------------------------
//Funcion de conexion a base de datos
function Conectarse()
{
	global $db;
    if (!($link=mysqli_connect($db['host'], $db['user'], $db['pass']))) {
      echo "Error conectando a la base de datos. " . mysqli_error($link);
      exit();
    }
    if (!mysqli_select_db($link, $db['db'])) {
      echo "Error seleccionando la base de datos. " . mysqli_error($link);
      exit();
    }
    return $link;
}
//-----------------------------------------------------------------
//Funcion para crear ids
function DateId()
{
	date_default_timezone_set('America/Bogota');
	$mensaje = date("YmdHis"); 
	return $mensaje;
}
//-----------------------------------------------------------------
//Funcion para capturar IP de usuario que abra pagina
function Regip()
{
	$ipadrs = $_SERVER['REMOTE_ADDR'];
	$fecha = date('Y-m-d'); 
	$hora = date('H:i:s'); 
   	$link=Conectarse();
	//---------------------------------------------------------
	$Sql="INSERT INTO Accesos VALUES ('$ipadrs', '$fecha', '$hora')";
	//Actualiza la base de datos
	mysqli_query($link, $Sql); 
}
//-----------------------------------------------------------------
//Funcion capa bloqueo pantalla
function dBloc()
{
	$msbloc = '<div id="dbloc" style="position:fixed; top:0px; left:0px; width:100%; height:100%; z-index:50; visibility:hidden" class="bgcol_1 dtrans_2 dalert"></div>';
	return $msbloc;
}
//-----------------------------------------------------------------
//Capa de mensajes
function MsSucss($extcls, $intcls, $itop, $iwh, $ilf, $smsj, $funca, $docancel, $funcc, $i, $svis)
{
	//Define boton cancelar
	$btcan = '';
	if($docancel == 1)
	{
		$btcan = '<input name="btcancel'. $i. '" id="btcancel'. $i. '" type="button" value="Cancelar" class="btcont" style="width:88px; margin-left:7px" onclick="'. $funcc. '" />';
	}
	//----------------------------------------------------------
	$mmsj = '<div id="dMsj'. $i. '" class="'. $extcls. '" style="top:'. $itop. 'px; width:'. $iwh. 'px; left:'. $ilf. 'px; overflow:hidden; visibility:'. $svis. '; position:absolute; z-index:55">
		<div style="margin:10px">
			<div id="dMsjm'. $i. '" class="'. $intcls. '" style="overflow:hidden; margin-bottom:7px; text-align:justify;">'. $smsj. '</div> 	
			<div style="text-align:left"><input id="btaccept'.$i. '" type="button" value="Aceptar" class="btcont" style="width:88px" onclick="'. $funca. '" />'. $btcan. '		
			</div>
		</div>
	</div>
	';
	return $mmsj;
}
//-----------------------------------------------------------------
//Capa procesando
function MsWait($itop, $ilf)
{
	$mmsj = '<div id="dWait" class="bgcol_4 dlin_6 drod_3" style="top:'. $itop. 'px; width:100px; left:'. $ilf. 'px; overflow:hidden; visibility:hidden; position:absolute; z-index:55">
		<div style="margin:10px">
			<div style="margin-bottom:7px; text-align:center"><img src="images/wait.gif" style="width:50px; height:auto"/></div> 	
			<div style="text-align:center" class="fcont">Procesando...</div>
		</div>
	</div>
	';
	return $mmsj;
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion general
function GenSet($link)
{
	$p = mysqli_query($link, "SELECT * FROM Configuracion_General") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 4; $i++) {
			global $sGenSet;
			$sGenSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion de usuario
function GetUser($link, $sId)
{
	$p = mysqli_query($link, "SELECT * FROM Usuarios WHERE Identificacion = '$sId'") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		global $sUserName;	
		global $sUserCargo;	
		global $sUserAcces; 
		$sUserName = $n['Nombre'];
		$sUserCargo = $n['Cargo'];
		$sUserAcces = explode("|", $n['NivelAcceso']);
	}
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion sucursal
function SucSet($link, $sId, $opType='venta')
{
	$p=mysqli_query($link, "SELECT 
	Sucursales.*,
	Configuracion_Contable.Copias_Compra,
	Configuracion_Contable.Copias_Venta,
	Configuracion_Contable.Inicio_Consecutivo,
	Configuracion_Contable.Fin_Consecutivo,
	Configuracion_Contable.Resolucion,
	Configuracion_Contable.Fecha_Inicio,
	Configuracion_Contable.Fecha_Final,
	Configuracion_Contable.Prefijo_Fact
	FROM Sucursales 
	LEFT JOIN Configuracion_Contable ON Sucursales.Codigo_Sucursal = Configuracion_Contable.Sucursal
	WHERE Codigo_Sucursal = '$sId' AND Tipo_Resolucion='$opType'") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 17; $i++) {
			global $sSucSet;
			$sSucSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion cambiaria
function ExcSet($link)
{
	$p=mysqli_query($link, "SELECT * FROM Configuracion_Cambiaria") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 6; $i++) {
			global $sExcSet;
			$sExcSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion contable de sucursal
function TaxSet($link, $sSuc)
{
	global $sTaxSet;
	$p=mysqli_query($link, "SELECT * FROM Configuracion_Contable WHERE Sucursal='". $sSuc. "'") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 9; $i++) {
			$sTaxSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion para capturar cantidad de registros en una tabla
function RegCount()
{
	//Captura variables
	$stable =(isset($_GET['stable'])) ? $_GET['stable'] : '';
	$swhere =(isset($_GET['swhere'])) ? $_GET['swhere'] : '';
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "SELECT * FROM ". $stable ." WHERE ". $swhere) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	echo $count;
}
//------------------------------------------------------------
//Funcion para carga de registros en combo
function LoadConfTab($link, $strSQL)
{
	$funval = '';
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$funval = $funval. '<option value="'. $n[0]. '">'. $n[0]. '</option>';
	}
	echo $funval;
}
//------------------------------------------------------------
//Funcion para carga de registros en lista
function LoadTable($link, $strSQL, $bDoCol, $sfun)
{
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	$funval = '';
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	$m = 1;
	$j = 0;
	$k = 0;
	while($n=mysqli_fetch_array($p)){
		if($n[0] != '')
		{
			if($bDoCol === 'true')
			{	
				if($k==0){
					$sclas = "trwhite";
					$k=1;
				} else {
					$sclas = "trgray";
					$k=0;
				}
			} else {
				$sclas = "trwhite";
			}
			$funval.= '<tr valign="middle" style="cursor:pointer" id="row'. $m. '" name="'. ($n[$sfun] ? $n[$sfun] : '') .'" class="fcont '. $sclas. '" onclick="lstfun(this)">';
			for ($j = 0; $j <= $i - 1; $j++) {
				$funval.= '<td class="celrow" id="cel'.$m. '-'. $j .'">';
				$funval.= ($n[$j] && $n[$j] !== '0' ? $n[$j] : '0') .'</td>';
				$funval.= '</td>';
			}
			$m++;
		$funval = $funval. '</tr>';
		}
	}
	echo $funval;
}
//------------------------------------------------------------
//Funcion para carga de registros en lista --> Se hacen dos funciones por si existen listados diferentes en la misma ventana
//y poder llamar diferentes funciones lstfun
function LoadTable_1($link, $strSQL, $bDoCol, $sfun)
{
	$funval = '';
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	$m = 1;
	$j = 0;
	$k = 0;
	while($n=mysqli_fetch_array($p)){
		if($n[0] != '')
		{
			if($bDoCol == 'true')
			{	
				if($k==0){
					$sclas = "trwhite";
					$k=1;
				} else {
					$sclas = "trgray";
					$k=0;
				}
			} else {
				$sclas = "trwhite";
			}
			$funval = $funval. '<tr valign="middle" style="cursor:pointer" id="rowa'. $m. '" name="'. $n[$sfun]. '" class="fcont '. $sclas. '" onclick="lstfun_1(this)">';
			for ($j = 0; $j <= $i - 1; $j++) {
				$funval = $funval. '<td class="celrow" id="cel'.$m. '-'. $j .'">'. $n[$j]. '</td>';
			}
			$m++;
		$funval = $funval. '</tr>';
		}
	}
	echo $funval;
}
//------------------------------------------------------------
//Funcion para carga de registros en lista --> Sin saltos de línea
function LoadTable_3($link, $strSQL, $bDoCol, $sfun)
{
	ini_set('memory_limit', '2048M');
	set_time_limit(0); 
	$funval = '';
	$p=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$i = mysqli_num_fields($p);
	$m = 1;
	$j = 0;
	$k = 0;
	while($n=mysqli_fetch_array($p)){
		if($n[0] != '')
		{
			if($bDoCol == 'true')
			{	
				if($k==0){
					$sclas = "trwhite";
					$k=1;
				} else {
					$sclas = "trgray";
					$k=0;
				}
			} else {
				$sclas = "trwhite";
			}
			$funval = $funval. '<tr valign="middle" style="cursor:pointer" id="row'. $m. '" name="'. $n[$sfun]. '" class="fcont '. $sclas. '" onclick="lstfun(this)">';
			for ($j = 0; $j <= $i - 1; $j++) {
				//$funval.= '<td style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px;" class="celrow" id="cel'.$m. '-'. $j .'">'. filter_var($n[$j], FILTER_SANITIZE_STRING). '</td>';

				$funval.= '<td style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px;" class="celrow" id="cel'.$m. '-'. $j .'">'. htmlspecialchars($n[$j], ENT_QUOTES, 'UTF-8'). '</td>';
			}
			$m++;
		$funval = $funval. '</tr>';
		}
	}
	echo $funval;
}
//------------------------------------------------------------
function Gen_Accept()
{

	$enableSiigoRemote = 0;
	// If is a new client, now register in SIIGO
	$siigo = new Siigo();

	//Captura variables
	$var[0] = (isset($_GET['var0'])) ? $_GET['var0'] : '';
	for($i = 1; $i <= $var[0]; $i++)
	{
		$var[$i] = (isset($_GET['var'.$i])) ? $_GET['var'.$i] : '';
	}
	$stable = (isset($_GET['stable'])) ? $_GET['stable'] : '';
	//--------------------------------------------------
	$link=Conectarse();

	//Construye string de registro
	$Sql = "INSERT INTO ". $stable. " VALUES (";
	$grpValues = []; // Added by ATSYS for allow all fields to be set
	for($i = 1; $i <= $var[0]; $i++)
	{
		$grpValues[] = "'". $var[$i]. "'";
	}

	if($stable === 'Operacion_Ventanilla' && $var[3] === 'VENTA DE DIVISAS') {
		// Actualizar campos Margen_V, Ingreso_V y Valor Descontado_V
		// Obtener Stock Valorizado de la Sucursal y Moneda
		$stockVal = GetPromedioStock($var[4], $var[34]);
		$margenV = $var[35] - $stockVal['Promedio'];
		$ingresoV = $var[37] * $margenV;
		$valorDescontadoV = $var[47] - $ingresoV;

		$grpValues[] = "'". $margenV. "'";
		$grpValues[] = "'". $ingresoV. "'";
		$grpValues[] = "'". $valorDescontadoV. "'";
	}

	if($stable === 'Operacion_Ventanilla' && $var[3] === 'COMPRA DE DIVISAS') {
		$grpValues[] = "'0'"; // Margen_V	
		$grpValues[] = "'0'"; // Ingreso_V
		$grpValues[] = "'0'"; // Valor Descontado_V
	}

	if($stable  === 'Cierres_Ventanilla') {
		// Obtener promedio de Stock_Valorizado
		$Promedio = ObtenerPromedioStock($var[2], $var[7]); // 4015.0015
		$grpValues[21] = "'". $Promedio. "'";
	}

	$Sql.= implode(',', $grpValues);

	$Sql.= ")";

	// Registro en base de datos
	mysqli_query($link, $Sql) or trigger_error(mysqli_error($link), E_USER_ERROR);

	//-------------------------------------------------
	if($stable == 'Clientes') {
		if($enableSiigoRemote == 1) {
			$siigo->setLineInLog('Inicio de creación de cliente en formulario. Buscando si la identificación '.$var[2].' existe en SIIGO', 'info', true);
	
			$docClient = $var[2];
			if($var[4] == 'NIT' && strlen($var[2]) == 10) {
				$docClient = substr($var[2], 0, -1);
			}
	
			$chk = $siigo->apiCheckClient($docClient);
			$codigoValidacion = $siigo->validateAnswer($chk);
			$codigoDocDian = $siigo->getDianCode($var[4]);
			$codigoCiudad = $siigo->getCityCode($var[16], $var[15]);
	
			if($codigoValidacion == 0) {
				// Crear el payload del cliente según el tipo de persona
				$ClientPayload = [];
	
				$person_type = ($var[4] == 'NIT') ? 'Company' : 'Person';
	
				//Consulta codigo de documento del beneficiario -> Codigo DIAN
				$ClientPayload['type'] = 'Customer';
				$ClientPayload['person_type'] = $person_type;
				$ClientPayload['id_type'] = $codigoDocDian['Codigo_Dian'];
				$ClientPayload['identification'] = $var[2];
		
				$customerEmail = 'noemail@newyorkmoney.com.co';
				if($var[18] !== '') {
					$customerEmail = ($var[18] == 'NS') ? 'noemail@newyorkmoney.com.co' : $var[18];
				}
		
				if ($person_type == 'Company') {
					$grpDecl = $siigo->setName($var[9]);
		
					$ClientPayload['name'] = [$var[9]];
					$ClientPayload['check_digit'] = $var[3];
					$ClientPayload['commercial_name'] = $var[9];
					$ClientPayload['contacts'] = [
						[
							"first_name" => $grpDecl['name'],
							"last_name" => $grpDecl['lastname'],
							"phone" => array("number" => $var[13]),
							"email" => ($customerEmail == 'NS') ? '' : $customerEmail
						]
					];
				} else {
					$ClientPayload['name'] = [
						$var[5] . ((!empty($var[6])) ? ' '.$var[6] : ''), 
						$var[7] . ((!empty($var[8])) ? ' '.$var[8] : '')
					];
					$ClientPayload['commercial_name'] = '';
					$ClientPayload['contacts'] = [
						[
							"first_name" => $var[5] . ((!empty($var[6])) ? ' '.$var[6] : ''),
							"last_name" => $var[7] . ((!empty($var[8])) ? ' '.$var[8] : ''),
							"email" => $customerEmail,
							"phone" => ['number' => ($var[12] !== '') ? $var[12] : $var[13]],
						]
					];
				}
		
				$ClientPayload['address']['address'] = $var[14];
				$ClientPayload['address']['city']['country_code'] = 'CO';
				$ClientPayload['address']['city']['state_code'] = substr($codigoCiudad['Codigo'], 0, 2);
				$ClientPayload['address']['city']['city_code'] = $codigoCiudad['Codigo'];
				$ClientPayload['address']['city']['postal_code'] = '';
				$ClientPayload['phones'][0]['indicative'] = '57';
				if($var[12] !== '') {
					$ClientPayload['phones'][0]['number'] = $var[12];
				} else {
					$ClientPayload['phones'][0]['number'] = $var[13];
				}
				$siigo->setLineInLog('$ClientPayload add: '.json_encode($ClientPayload), 'info');
	
				$clientCreation = $siigo->apiCreateClient($ClientPayload);
				$clientValidate = $siigo->validateAnswer($clientCreation);
	
				$siigo->setLineInLog('$clientCreation add: '.json_encode($clientCreation), 'info');
				$siigo->setLineInLog('$clientValidate add: '. $clientValidate, 'info');
	
				$max_retries = 3;
				$retries = 0;
	
				switch ($clientValidate) {
					case 0:
						$siigo->setLineInLog('Cliente ' .$var[9]. ' registrado correctamente en SIIGO desde formulario', 'info');
					break;
					case 1:
						if ($retries < $max_retries) {
	
							$siigo->setLineInLog('Reintento '.$retries.' de crear cliente desde formulario', 'info');
							$message = $chk['Errors'][0]['Message'];
							$start = strpos($message, "Try again in") + 12;
							$end = strpos($message, " seconds.");
							$seconds = substr($message, $start, $end - $start);
							sleep($seconds);
							$retries++;
							break;
	
						} else {
	
							$siigo->setLineInLog('Reintentos agotados para crear cliente desde formulario', 'info');
							$lastUpdate = date("Y-m-d H:i:s");
							$errorMessage = "Error al crear cliente: " . $var[1] ."  -  " . addslashes($clientCreation) . " - Cod: " . $clientValidate;
							$siigo->setLineInLog($errorMessage, 'error');
							break; // Salir del bucle actual y continuar con el proceso
	
						}
					case 2:
	
						$siigo->setLineInLog('Error 4xx al crear cliente desde formulario: '. json_encode($clientCreation) , 'error');
						break; // Salir del bucle actual y continuar con el proceso de creación
	
					case 3:
	
						$siigo->setLineInLog('Error 5xx al crear cliente desde formulario. ' .json_encode($clientCreation), 'error');
	
				}
			} else {
				$siigo->setLineInLog('$chk add: '.json_encode($chk), 'info');
				$siigo->setLineInLog('No se pudo crear '.$var[9].' en SIIGO: ' . json_encode($codigoValidacion), 'info');
			}
		}
	}
	//-------------------------------------------------
	if(
		$stable == 'Operacion_Ventanilla' && $var[3] == 'COMPRA DE DIVISAS' ||
		$stable == 'Operacion_Ventanilla' && $var[3] == 'VENTA DE DIVISAS'
	) {
		// Si el valor obtenido en $var[35] llega como por ejemplo 3980.0000 lo pueda entender sin problema
		// Actualizar promedio en la sucursal
		$var[35] = floatval($var[35]);
		ActualizarPromedioPonderado($var[4], $var[34], $var[37], $var[35], ($stable == 'Operacion_Ventanilla' && $var[3] == 'VENTA DE DIVISAS') ? 'venta' : 'compra');
	}

	//-------------------------------------------------
	if($stable == 'Traslados_Ventanilla') {

		// Actualizar promedio en sucursal saliente
		if($var[3] == 'EGRESO' && $var[16] == 'EFECTIVO' && !preg_match('/^(CAJA|caja|Caja)/', $var[10])) {
			ActualizarPromedioPonderado($var[4], $var[12], $var[14], 0, 'venta');
		}

		// Actualizar promedio en sucursal entrante
		if($var[3] == 'INGRESO' && $var[16] == 'EFECTIVO' && !preg_match('/^(CAJA|caja|Caja)/', $var[10])) {
			$var[13] = floatval($var[13]);
			ActualizarPromedioPonderado($var[4], $var[12], $var[14], $var[13], 'compra');
		}

	}

	//-------------------------------------------------
	$mensaje = 10;	//Mensaje exitoso
	echo $mensaje;
}
//------------------------------------------------------------
//Funcion generica para eliminar registro 
function Gen_Delete()
{
	$stable =(isset($_GET['stable'])) ? $_GET['stable'] : '';
	$swhere =(isset($_GET['swhere'])) ? $_GET['swhere'] : '';
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "select * from ". $stable ." where ". $swhere) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count==0){
		$mensaje = 'El registro para eliminar no existe.';
	} else {
		$Sql = "DELETE FROM ". $stable ." WHERE ". $swhere;
		//Registro en base de datos	
		mysqli_query($link, $Sql);
		//-------------------------------------------------
		$mensaje = 10;	//Mensaje exitoso
	}
	echo $mensaje;
}
//------------------------------------------------------------
//Funcion generica para modificar registro 
function Gen_Modif()
{
	$enableSiigoRemote = 0;
	//Captura variables
	$var[0]=(isset($_GET['var0'])) ? $_GET['var0'] : '';
	for($i = 1; $i <= $var[0]; $i++)
	{
		$var[$i] = (isset($_GET['var'.$i])) ? $_GET['var'.$i] : '';
	}
	$stable =(isset($_GET['stable'])) ? $_GET['stable'] : '';
	$swhere =(isset($_GET['swhere'])) ? $_GET['swhere'] : '';
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link,"select * from ". $stable ." where ". $swhere) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	if($count==0){
		$mensaje = 'El registro para modificar no existe.';
	} else {
		//Construye string de registro
		$Sql = "UPDATE ". $stable ." SET ";
		$grpVar = [];
		for($i = 1; $i <= $var[0]; $i++)
		{
			//Construye substring de campo y valor
			$varset = '';
			$varset = explode('.|.', $var[$i]);
			if($i == $var[0]){
				$Sql = $Sql. $varset[0]. "='". $varset[1]. "'";
				$grpVar[$varset[0]] = $varset[1];
			} else {
				$Sql = $Sql. $varset[0]. "='". $varset[1]. "',";
				$grpVar[$varset[0]] = $varset[1];
			}
		}
		$Sql = $Sql. " WHERE ". $swhere;
		//Registro en base de datos	
		mysqli_query($link, $Sql);
		//-------------------------------------------------
		if($stable == 'Clientes') {
			if($enableSiigoRemote == 1) {

				// Now update in SIIGO
				$siigo = new Siigo();
	
				$siigo->setLineInLog('Actualizando cliente '.$grpVar['Documento'].' en SIIGO', 'info');
	
				$chk = $siigo->apiCheckClient($grpVar['Documento']);
				$siigo->setLineInLog('$chk Gen_Modif(): ' . json_encode($chk));
				if($chk) {
					if(isset($chk['pagination'])) {
						$totalResult = $chk['pagination']['total_results'];
			
						$codigoValidacion = $siigo->validateAnswer($chk);
						$codigoDocDian = $siigo->getDianCode($grpVar['Tipo_Documento']);
						$codigoCiudad = $siigo->getCityCode($grpVar['Ciudad'], $grpVar['Departamento']);
			
						$siigo->setLineInLog('$totalResult: ' . $totalResult, 'info');
	
						if($totalResult == 1) {
							$idSiigoClient = $chk['results'][0]['id'];
							if($codigoValidacion == 0) {
								// Crear el payload del cliente según el tipo de persona
								$ClientPayload = [];
			
								$person_type = 'Person';
						
								//Consulta codigo de documento del beneficiario -> Codigo DIAN
								$ClientPayload['type'] = 'Customer';
								$ClientPayload['person_type'] = $person_type;
								$ClientPayload['id_type'] = $codigoDocDian['Codigo_Dian'];
								$ClientPayload['identification'] = $grpVar['Identificacion'];
						
								$customerEmail = 'noemail@newyorkmoney.com.co';
								if($grpVar['Email'] !== '') {
									$customerEmail = ($grpVar['Email'] == 'NS') ? 'noemail@newyorkmoney.com.co' : $grpVar['Email'];
								}
						
								if ($person_type == 'Company') {
									$grpDecl = $siigo->setName($grpVar['Nombre_Completo']);
						
									$ClientPayload['name'] = [$grpVar['Nombre_Completo']];
									$ClientPayload['check_digit'] = $grpVar['DV'];
									$ClientPayload['commercial_name'] = $grpVar['Nombre_Completo'];
									$ClientPayload['contacts'] = [
										[
											"first_name" => $grpDecl['name'],
											"last_name" => $grpDecl['lastname'],
											"phone" => ["number" => $grpVar['Celular']],
											"email" => $customerEmail
										]
									];
								} else {
									$ClientPayload['name'] = [
										$grpVar['Nombre_1'] . ((!empty($grpVar['Nombre_2'])) ? ' '.$grpVar['Nombre_2'] : ''), 
										$grpVar['Apellido_1'] . ((!empty($grpVar['Apellido_2'])) ? ' '.$grpVar['Apellido_2'] : '')
									];
									$ClientPayload['commercial_name'] = '';
									$ClientPayload['contacts'] = [
										[
											"first_name" => $grpVar['Nombre_1'] . ((!empty($grpVar['Nombre_2'])) ? ' '.$grpVar['Nombre_2'] : ''),
											"last_name" => $grpVar['Apellido_1'] . ((!empty($grpVar['Apellido_2'])) ? ' '.$grpVar['Apellido_2'] : ''),
											"email" => $customerEmail,
											"phone" => ['number' => ($grpVar['Telefono'] !== '') ? $grpVar['Telefono'] : $grpVar['Celular']],
										]
									];
								}
						
								$ClientPayload['address']['address'] = $grpVar['Direccion'];
								$ClientPayload['address']['city']['country_code'] = 'CO';
								$ClientPayload['address']['city']['state_code'] = substr($codigoCiudad['Codigo'], 0, 2);
								$ClientPayload['address']['city']['city_code'] = $codigoCiudad['Codigo'];
								$ClientPayload['phones'][0]['indicative'] = '57';
								if($grpVar['Telefono'] !== '') {
									$ClientPayload['phones'][0]['number'] = $grpVar['Telefono'];
								} else {
									$ClientPayload['phones'][0]['number'] = $grpVar['Celular'];
								}
			
								$siigo->setLineInLog('$ClientPayload: ' . json_encode($ClientPayload), 'info');
								$clientUpdate = $siigo->apiUpdateClient($idSiigoClient, $ClientPayload);
								$clientValidate = $siigo->validateAnswer($clientUpdate);
			
								$max_retries = 3;
								$retries = 0;
			
								$siigo->setLineInLog('$clientUpdate: ' . json_encode($clientUpdate));
								$siigo->setLineInLog('$clientValidate: ' . $clientValidate);
			
								switch ($clientValidate) {
									case 0:
										$siigo->setLineInLog('Cliente ' .$grpVar['Identificacion']. ' actualizado correctamente en SIIGO desde formulario', 'info');
									break;
									case 1:
										if ($retries < $max_retries) {
			
											$siigo->setLineInLog('Reintento '.$retries.' de crear cliente desde formulario', 'info', true);
											$message = $chk['Errors'][0]['Message'];
											$start = strpos($message, "Try again in") + 12;
											$end = strpos($message, " seconds.");
											$seconds = substr($message, $start, $end - $start);
											sleep($seconds);
											$retries++;
											break;
			
										} else {
			
											$siigo->setLineInLog('Reintentos agotados para crear cliente desde formulario', 'info', true);
											$lastUpdate = date("Y-m-d H:i:s");
											$errorMessage = "Error al crear cliente: " . $var[1] ."  -  " . addslashes($clientUpdate) . " - Cod: " . $clientValidate;
											$siigo->setLineInLog($errorMessage, 'error');
											break; // Salir del bucle actual y continuar con el proceso
			
										}
									case 2:
			
										$siigo->setLineInLog('Error 4xx al actualizar cliente desde formulario: '. json_encode($clientUpdate) , 'error');
										break; // Salir del bucle actual y continuar con el proceso de creación
			
									case 3:
			
										$siigo->setLineInLog('Error 5xx al actualizar cliente desde formulario. ' .json_encode($clientUpdate), 'error');
			
								}
							}
						} else {
							$siigo->setLineInLog('$chk edit: '.json_encode($chk), 'info');
							
							// SOLO SI NO EXISTE EL CLIENTE EN SIIGO, SE CREA
							$siigo->setLineInLog('Cliente '.$grpVar['Documento'].' no existe en SIIGO, se procede a crear', 'info');
							$ClientPayload = [];
			
							// Crear el payload del cliente según el tipo de persona
							$person_type = 'Person';
					
							//Consulta codigo de documento del beneficiario -> Codigo DIAN
							$ClientPayload['type'] = 'Customer';
							$ClientPayload['person_type'] = $person_type;
							$ClientPayload['id_type'] = $codigoDocDian['Codigo_Dian'];
							$ClientPayload['identification'] = $grpVar['Identificacion'];
					
							$customerEmail = 'noemail@newyorkmoney.com.co';
							if($grpVar['Email'] !== '') {
								$customerEmail = ($grpVar['Email'] == 'NS') ? 'noemail@newyorkmoney.com.co' : $grpVar['Email'];
							}
					
							if ($person_type == 'Company') {
								$grpDecl = $siigo->setName($grpVar['Nombre_Completo']);
					
								$ClientPayload['name'] = [$grpVar['Nombre_Completo']];
								$ClientPayload['check_digit'] = $grpVar['DV'];
								$ClientPayload['commercial_name'] = $grpVar['Nombre_Completo'];
								$ClientPayload['contacts'] = [
									[
										"first_name" => $grpDecl['name'],
										"last_name" => $grpDecl['lastname'],
										"phone" => ["number" => $grpVar['Celular']],
										"email" => $customerEmail
									]
								];
							} else {
								$ClientPayload['name'] = [
									$grpVar['Nombre_1'] . ((!empty($grpVar['Nombre_2'])) ? ' '.$grpVar['Nombre_2'] : ''), 
									$grpVar['Apellido_1'] . ((!empty($grpVar['Apellido_2'])) ? ' '.$grpVar['Apellido_2'] : '')
								];
								$ClientPayload['commercial_name'] = '';
								$ClientPayload['contacts'] = [
									[
										"first_name" => $grpVar['Nombre_1'] . ((!empty($grpVar['Nombre_2'])) ? ' '.$grpVar['Nombre_2'] : ''),
										"last_name" => $grpVar['Apellido_1'] . ((!empty($grpVar['Apellido_2'])) ? ' '.$grpVar['Apellido_2'] : ''),
										"phone" => ['number' => ($grpVar['Telefono'] !== '') ? $grpVar['Telefono'] : $grpVar['Celular']],
										"email" => $customerEmail
									]
								];
							}
					
							$ClientPayload['address']['address'] = $grpVar['Direccion'];
							$ClientPayload['address']['city']['country_code'] = 'CO';
							$ClientPayload['address']['city']['state_code'] = substr($codigoCiudad['Codigo'], 0, 2);
							$ClientPayload['address']['city']['city_code'] = $codigoCiudad['Codigo'];
							$ClientPayload['phones'][0]['indicative'] = '57';
							if($grpVar['Telefono'] !== '') {
								$ClientPayload['phones'][0]['number'] = $grpVar['Telefono'];
							} else {
								$ClientPayload['phones'][0]['number'] = $grpVar['Celular'];
							}
			
							$siigo->setLineInLog('$ClientPayload in edition: ' . json_encode($ClientPayload), 'info');
			
							$clientCreation = $siigo->apiCreateClient($ClientPayload);
							$clientValidate = $siigo->validateAnswer($clientCreation);
							$siigo->setLineInLog('$clientCreation: ' . json_encode($clientCreation));
			
							$max_retries = 3;
							$retries = 0;
			
							switch ($clientValidate) {
								case 0:
									$siigo->setLineInLog('Cliente ' .$var[9]. ' registrado correctamente en SIIGO desde formulario de edición', 'info');
								break;
								case 1:
									if ($retries < $max_retries) {
			
										$siigo->setLineInLog('Reintento '.$retries.' de crear cliente desde formulario de edición', 'info');
										$message = $chk['Errors'][0]['Message'];
										$start = strpos($message, "Try again in") + 12;
										$end = strpos($message, " seconds.");
										$seconds = substr($message, $start, $end - $start);
										sleep($seconds);
										$retries++;
										break;
			
									} else {
			
										$siigo->setLineInLog('Reintentos agotados para crear cliente desde formulario de edición', 'info');
										$lastUpdate = date("Y-m-d H:i:s");
										$errorMessage = "Error al crear cliente: " . $var[1] ."  -  " . addslashes($clientCreation) . " - Cod: " . $clientValidate;
										$siigo->setLineInLog($errorMessage, 'error');
										break; // Salir del bucle actual y continuar con el proceso
			
									}
								case 2:
			
									$siigo->setLineInLog('Error 4xx al crear cliente desde formulario de edición: '. json_encode($clientCreation) , 'error');
									break; // Salir del bucle actual y continuar con el proceso de creación
			
								case 3:
			
									$siigo->setLineInLog('Error 5xx al crear cliente desde formulario de edción. ' .json_encode($clientCreation), 'error');
			
							}
			
						}
					}
				}

			}
		}
		//-------------------------------------------------
		$mensaje = 10;	//Mensaje exitoso
	}
	echo $mensaje;
}
//------------------------------------------------------------
//Funcion para buscar y poner registros en controles
function Gen_Find()
{
	$var[0] = $_GET['var0'];
	$stable = $_GET['stable'];
	$swhere = $_GET['swhere'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "SELECT * FROM ". $stable ." WHERE ". $swhere) or die(mysqli_error($link));
	$count=mysqli_num_rows($p);
	$mensaje = '';
	$sregm = '';
	if($count == 1) {

		while($n = mysqli_fetch_array($p, MYSQLI_NUM)) {

			$grpLine = [];
			$sregm_fila = '';
			for($i = 1; $i <= $var[0]; $i++)
			{

				$index = $i - 1;
				$valor = isset($n[$index]) ? $n[$index] : '';

				if($stable == 'Clientes') {
					if($index == 12) {
						$valor = substr($valor, 0, -4);
					} else if($index == 13) {
						$valor = substr($valor, 0, -3);
					}
				}

				$grpLine[] = $valor;

			}

			$sregm.= implode('.|.', $grpLine);

		}

		//-------------------------------------------------
	}
	echo $sregm;
}
//-----------------------------------------------------------
//Funcion para buscar un solo registro y campo de base
function Gen_Find_Field()
{
	$strSQL = (isset($_GET['strSQL'])) ? $_GET['strSQL'] : '';

	//--------------------------------------------------
	$link = Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p = mysqli_query($link, $strSQL) or die("[Gen_Find_Field] SQL: " . $strSQL . " - Error: ".mysqli_error($link));
	$count = mysqli_num_rows($p);
	$mensaje = '';
	if($count == 1) {
		while($n = mysqli_fetch_array($p)){
			$mensaje = $n[0];
		}
		//-------------------------------------------------
		echo $mensaje;
	}
}
//-------------------------------------------------
//Funcion para buscar un solo registro de php a php
function Gen_Find_Field_1($strSQL)
{
	//--------------------------------------------------
	$link = Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count = mysqli_num_rows($p);
	$mensaje = '';
	if($count == 1){
		while($n = mysqli_fetch_array($p)){
			$mensaje = $n[0];
		}
		//-------------------------------------------------
		echo $mensaje;
	}
}
//-------------------------------------------------
//Funcion para buscar un solo registro y campo de base
function Gen_Find_Field_Clinton()
{
	$strSQL = "SELECT Informacion FROM Lista_Clinton_1 WHERE Informacion LIKE '%".$_GET['strSQL']."%'";
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$mensaje = '';
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count = mysqli_num_rows($p);
	if($count != 0){
		while($n = mysqli_fetch_array($p)){
			$svalcli = str_replace(",","",str_replace(";","",$n[0]));
			$vvalcli = explode(" ", $svalcli);
			for($i = 0; $i <= count($vvalcli) - 1; $i++){
				if($_GET['strSQL'] == $vvalcli[$i]){
					$mensaje = $count;
					break;			
				}
			}
			if($mensaje != ''){break;}		
		}
	}
	echo $mensaje;
}
//-------------------------------------------------
//Funcion para buscar un solo registro
function ReadSerie($link, $strSQLS)
{
	$mensaje = '';	
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		$mensaje = $n[0];
	}
	//-------------------------------------------------
	echo $mensaje;
}
function ReadSerie_1($link, $strSQLS)
{
	$mensaje = '';	
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n = mysqli_fetch_array($p)){
		$mensaje.= $n[0];
	}
	//-------------------------------------------------
	return $mensaje;
}

//--------------------------------------------------
//Procedimiento para actualizar los saldos de caja
function ActCaja()
{
	$sparvec =(isset($_GET['strSQL'])) ? $_GET['strSQL'] : '';
	$parvec = explode(".|.", $sparvec);
	//Determina si la moneda existe en listado de arqueo
    $strSQLAR = "SELECT * FROM Arqueo_Ventanilla WHERE Sucursal = '". $parvec[0]. "' AND Estacion = '". $parvec[1] ."' AND Moneda = '". $parvec[2] ."'";
	$link=Conectarse();
	$p = mysqli_query($link, $strSQLAR) or die(mysqli_error($link));
    $iAdd = 0;
	$count = mysqli_num_rows($p);
    if($count != 0)
	{	
     	$iAdd = 1;
		//Captura valores
		while($n = mysqli_fetch_array($p)){
			for($i = 4; $i <= 9; $i++){
				$ar[$i] = $n[$i];
			}
		}
	}
	//---------------------------------------------------------------------
	//Nombres campos
	$coln[4] = 'Saldo_Inicial';
	$coln[5] = 'Compras';
	$coln[6] = 'Entradas';
	$coln[7] = 'Ventas';
	$coln[8] = 'Salidas';
	$coln[9] = 'Saldo_Final';
	//---------------------------------------------------------------------
	//Modifica o agrega la información de arqueo
	if($iAdd == 0)	// Agrega
	{
		//inicia valores
		for($i = 4; $i <= 9; $i++){
			if($coln[$i] == $parvec[3])
			{
				$arn[$i] = $parvec[4];
			} else {
				$arn[$i] = 0;
			}
		}
		$arn[9] = $arn[4] + $arn[5] + $arn[6] - $arn[7] - $arn[8];
		$idArqueo = $parvec[0]. $parvec[2]. $parvec[1];
		$strSQLAA =  "INSERT INTO Arqueo_Ventanilla VALUES ('". $idArqueo. "', '". $parvec[0]. "', '". $parvec[1]. "', '". $parvec[2]. "', '". $arn[4]. "', '". $arn[5]. "', '". $arn[6]. "', '". $arn[7]. "', '". $arn[8]. "', '". $arn[9]. "')";
		//Registro en base de datos	
		mysqli_query($link, $strSQLAA);
	} else {	//Actualiza
		$j = 0;
		for($i = 4; $i <= 9; $i++){
			if($coln[$i] == $parvec[3])
			{
				$arn[$i] = $ar[$i] + $parvec[4];
				$j = $i;
			} else {
				$arn[$i] = $ar[$i];
			}
		}
		$arn[9] = $arn[4] + $arn[5] + $arn[6] - $arn[7] - $arn[8];
		$strSQLAA = "UPDATE Arqueo_Ventanilla SET ". $parvec[3]. "='". $arn[$j]. "', Saldo_Final='". $arn[9]. "' WHERE Sucursal = '". $parvec[0]. "' AND Estacion = '". $parvec[1] ."' AND Moneda = '". $parvec[2] ."'";
		//Registro en base de datos	
		mysqli_query($link, $strSQLAA);
	}
	//--------------------------------------
	echo $iAdd;
}
//-----------------------------------------------------
//Funcion para agregar unidad a consecutivo
function AddSerie()
{
	$sCode = $_GET['strSQL'];
	//--------------------------------------------------------
    $strSQLS = "SELECT Consecutivo FROM XConf_Consecutivos WHERE Identificacion = '".  $sCode. "'";
	$link=Conectarse();
	//Consulta consecutivo actual
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	while($n = mysqli_fetch_array($p)){
		$dSerie = $n['Consecutivo'];
	}
	//-----------------------------------------------------
	//Suma unidad y actualiza consecutivo
	$dSerie = $dSerie + 1;
	$strUpdate = "UPDATE XConf_Consecutivos SET Consecutivo='". $dSerie. "' WHERE Identificacion = '". $sCode. "'";
	mysqli_query($link, $strUpdate) or die(mysqli_error($link));
	//------------------------------------------------
	echo $dSerie;
}
//-----------------------------------------------------
//Funcion para agregar unidad a consecutivo
function AddSerieNew()
{
	$sCode =(isset($_GET['strSQL'])) ? $_GET['strSQL'] : '';
	//--------------------------------------------------------
    $strSQLS = "SELECT Consecutivo FROM XConf_Consecutivos WHERE Identificacion = '".  $sCode. "'";
	$link = Conectarse();
	//Consulta consecutivo actual
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error());
	while($n = mysqli_fetch_array($p)) {
		$dSerie = $n['Consecutivo']; 
		$dSerieO = $n['Consecutivo']; 
	}	
	//-----------------------------------------------------
	//Suma unidad y actualiza consecutivo
	$dSerie = $dSerie + 1;
	$strUpdate = "UPDATE XConf_Consecutivos SET Consecutivo='". $dSerie. "' WHERE Identificacion = '". $sCode. "'";
	mysqli_query($link, $strUpdate) or die(mysqli_error($link));
	//------------------------------------------------
	echo $dSerieO;
}
//----------------------------------------------------
//Funcion paar actualizar un solo registro en base de datos
function Gen_Update_Field()
{
	$stable = (isset($_GET['stable'])) ? $_GET['stable'] : '';
	$swhere = (isset($_GET['swhere'])) ? $_GET['swhere'] : '';
	$sfield = (isset($_GET['sfield'])) ? $_GET['sfield'] : '';
	$mensaje = '';
	//--------------------------------------------------------
    $strSQL = "SELECT * FROM ". $stable. " WHERE ". $swhere;
	$link = Conectarse();
	//--------------------------------------------------------
	$p = mysqli_query($link, $strSQL) or die(mysqli_error($link));
	$count = mysqli_num_rows($p);
	$mensaje = '';
	if($count == 1){
		$mensaje = 1;
		$strUpdate = "UPDATE ".  $stable. " SET ". $sfield. " WHERE ". $swhere;
		mysqli_query($link, $strUpdate) or die(mysqli_error($link));
	}
	echo $mensaje;
}
//--------------------------------------------------------
// Función de calificación alerta eperaciones
function SiplaOps()
{
	//---------------------------------------------------
	//Captura variables
	$sops = (isset($_GET['sops'])) ? $_GET['sops'] : '';	//Tipo operacion
	$sseg = (isset($_GET['sseg'])) ? $_GET['sseg'] : '';	//Segmento mercado
	$sidc = (isset($_GET['sidc'])) ? $_GET['sidc'] : '';	//Identificación del cliente
	$sval = (isset($_GET['sval'])) ? $_GET['sval'] : '';	//Valor en usd de la operacion
	$sfec = (isset($_GET['sfec'])) ? $_GET['sfec'] : '';	//fecha actual
	$mensaje = '';
	//---------------------------------------------------
	$strSQLC = "SELECT * FROM Calificacion_Alerta WHERE Operacion = '". $sops. "'";
	$link = Conectarse();
	$p=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
	while($n = mysqli_fetch_array($p)){
		for($i = 0; $i <= 6; $i++)
		{
			$dCal[$i] = $n[$i + 1]; 
		}
	}
	//-------------------------------------------------		
	//Captura matriz de parametros segmentos
	$strSQLP = "SELECT * FROM Parametros_Segmentacion WHERE Operacion = '". $sops. "' AND Segmento = '". $sseg. "'";
	$a = mysqli_query($link, $strSQLP) or die(mysqli_error($link));
	$count = mysqli_num_rows($a);
	if($count == 0)
	{
		return $mensaje;
	} else {
		while($ap=mysqli_fetch_array($a)){
			for($i = 0; $i <= 4; $i++)
			{
				$dPar[$i] = $ap[$i + 3]; 
			}
		}
	}
	//----------------------------------------------------------------------
	//Calcula acumulados y valores del cliente
	//Fecha
	$varfec = explode('-', $sfec);
	//Frecuencia mensual
	$strSQLOC = "SELECT COUNT(Identificacion) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Tipo_Operacion = '". $sops. "' AND Ano_Mes = '". $varfec[0]. "_". $varfec[1] . "' AND Estado_Operacion = 'ACTIVO'";
	$dOpsCont = 0;
	$tempv = '';
	$tempv = ReadSerie_1($link, $strSQLOC);
	if($tempv != ''){
		$dResult[0] = $tempv + 1;
	} else {
		$dResult[0] = 0;
	}
	//Acumulado diario
	$strSQLD = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Fecha = '". $sfec. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
	$tempv = '';
	$tempv = ReadSerie_1($link, $strSQLD);
	if($tempv != ''){
		$dResult[1] = $tempv + $sval;
	} else {
		$dResult[1] = $sval;
	}
	//Acumulado Mismo mes cliente
	$strSQLM = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Fecha >= '". $varfec[0]."-".$varfec[1]."-01".  "' AND Fecha <= '". $sfec. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
	$tempv = '';
	$tempv = ReadSerie_1($link, $strSQLM);
	if($tempv != ''){
		$dResult[2] = $tempv + $sval;
	} else {
		$dResult[2] = $sval;
	}
	//Acumulado Total del cliente sin la operacion actual --> todas las operaciones
	$strSQLT = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
	$tempv = '';
	$tempv = ReadSerie_1($link, $strSQLT);
	if($tempv != ''){
		$dResult[3] = $tempv;
	} else {
		$dResult[3] = 0;
	}
	// Total de días en que hay hecho operaciones para calcular el promedio diario
	$iTotDia = 0;
	$strSQLI = "SELECT DISTINCT Fecha FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
	$tot = mysqli_query($link, $strSQLI) or die(mysqli_error($link));
	$iTotDia = mysqli_num_rows($tot);
	//Promedio acumulado diario + 50%
	if($iTotDia == 0) {
		$dResult[4] = $dResult[1];
	} else {
		$dResult[4] = ($dResult[3] / $iTotDia) * 1.5;
	}
	//Total meses en que ha hecho operaciones para calcular promedio mensual
	$iTotMes = 0;
	$strSQLS = "SELECT DISTINCT Ano_Mes FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". $sidc. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
	$totm = mysqli_query($link, $strSQLS) or die(mysqli_error($link));
	$iTotMes = mysqli_num_rows($totm);
	//Promedio acumulado mensual + 50% 
	if($iTotMes == 0) {
		$dResult[5] = $dResult[2];
	} else {
		$dResult[5] = ($dResult[3] / $iTotMes) * 1.5;
	}
	//-------------------------------------------------------------------------------
	//Calcula las diferencias para luego obtener las calificaciones, si las diferencias
	//son mayores que cero
	//Frecuencia
	$dDif[0] = $dResult[0] - $dPar[0];
	//Acumulado diario cliente - acumulado diario segmento
	$dDif[1] = $dResult[1] - $dPar[1];
	//Acumulado mensual cliente - acumulado mensual segmento
	$dDif[2] = $dResult[2] - $dPar[2];
	//Acumulado del dia del cliente - Promedio acumulado diario mismo cliente
	$dDif[3] = $dResult[1] - $dResult[4];
	//Acumulado mismo mes cliente - Promedio acumulado mensual del cliente
	$dDif[4] = $dResult[2] - $dResult[5];
	//Acumulado diario cliente - tope maximo acumulado diario segmento
	$dDif[5] = $dResult[1] - $dPar[3];
	//Acumulado mismo mes cliente - tope maximo acumulado mensual segmento
	$dDif[6] = $dResult[2] - $dPar[4];
	//-----------------------------------------------------------------------------------
	//Hace calculo de calificacion total por sistema
	$dCFinal = 0;
	for($i = 0; $i <= 6; $i++) {
		if($dDif[$i] > 0){$dCFinal = $dCFinal + $dCal[$i];}
	}
	//------------------------------------------------------------------------------------
	//regresa valor calificacion
	$mensaje = $dCFinal;
    echo $mensaje;
}
//------------------------------------------------------------------------------
// Funcion para agregar registro correo para encuesta
function AddCorreo(){
	// Conectar
	$link=Conectarse();
	// Recuperar variable
	$swhere =(isset($_GET['swhere'])) ? $_GET['swhere'] : '';
	$ids = explode(',',$swhere);
	// Identificador
	$regid = DateId().rand(0, 9999);
	// Inserta información a tabla correos
	$Sqladdcorreo="INSERT INTO Correos VALUES ('". $regid ."', '". $ids[0] ."', '". $ids[1] ."', 'Por Enviar')";
	//-------------------------------
	mysqli_query($link, $Sqladdcorreo);
}
//------------------------------------------------------------------------------
// Función para insertar factura para integración SIIGO
function SaveInvoiceToSend(){
	// Conectar
	$link = Conectarse();
	// Recuperar variable
	$swhere = $_GET['swhere'];
	date_default_timezone_set('America/Bogota');
	$lastUpdate = date("Y-m-d H:i:s");
	//------------------------------------------------------------
	$strSQ0 = "INSERT INTO Factura_Electronica (InvoiceId,SendProcess,SendStatus,LastUpdate,ApiResponse) VALUES('".$swhere."','Crear','Por Enviar','".$lastUpdate."','')";
	mysqli_query($link, $strSQ0);
}

// Función para insertar factura para integración SIIGO
function SaveInvoiceBuyToSend(){
	// Conectar
	$link = Conectarse();
	// Recuperar variable
	$swhere = $_GET['swhere'];
	date_default_timezone_set('America/Bogota');
	$lastUpdate = date("Y-m-d H:i:s");
	//------------------------------------------------------------
	$strSQ0 = "INSERT INTO Factura_Electronica_Compra (InvoiceId,SendProcess,SendStatus,LastUpdate,ApiResponse) VALUES('".$swhere."','Crear','Por Enviar','".$lastUpdate."','')";
	mysqli_query($link, $strSQ0) or die("[SaveInvoiceBuyToSend] SQL: $strSQ0 - Error: ".mysqli_error($link));
}

// TODO: Confirmar con el cliente si el precio usado para promedio es con o sin IVA.
// Se está usando sin IVA por consistencia con la valorización de inventario.
// Base SIN IVA
function ActualizarPromedioPonderado($sucursal, $moneda, $cantidadNueva, $precioUnitarioNuevo, $tipo='compra') {

    $link = Conectarse();
    $siigo = new Siigo();
    $sucursal = mysqli_real_escape_string($link, $sucursal);
    $moneda = mysqli_real_escape_string($link, $moneda);

	try {

		if ($tipo == 'compra') {
			$valorCompraNueva = $cantidadNueva * $precioUnitarioNuevo;
			
			$sql = "INSERT INTO Stock_Valorizado 
                    (Sucursal, Moneda, Cantidad, Valor_Total, Promedio, Fecha_Actualizacion) 
                    VALUES ('$sucursal', '$moneda', '$cantidadNueva', '$valorCompraNueva', '$precioUnitarioNuevo', NOW())
                    ON DUPLICATE KEY UPDATE 
                        Cantidad = Cantidad + VALUES(Cantidad),
                        Valor_Total = Valor_Total + VALUES(Valor_Total),
                        Promedio = IF((Cantidad + VALUES(Cantidad)) > 0, 
                                    (Valor_Total + VALUES(Valor_Total)) / (Cantidad + VALUES(Cantidad)), 
                                    0),
                        Fecha_Actualizacion = NOW()";
			$result = mysqli_query($link, $sql);
            if (!$result) throw new Exception(mysqli_error($link));
            
            $siigo->setLineInLog("[ActualizarPromedioPonderado] Compra - Suc.: $sucursal - Mon.: $moneda - Cant.: $cantidadNueva - Precio: $precioUnitarioNuevo");	

		} else {

			// Igual: bloquear el registro que vamos a actualizar
			$sql = "SELECT Cantidad, Valor_Total, Promedio 
                    FROM Stock_Valorizado 
                    WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
			$query = mysqli_query($link, $sql);
            if (!$query) throw new Exception(mysqli_error($link));
            $stock = mysqli_fetch_assoc($query);

			if ($stock && $stock['Cantidad'] >= $cantidadNueva) {
                $nuevaCantidad = $stock['Cantidad'] - $cantidadNueva;
                $nuevoValorTotal = $nuevaCantidad * $stock['Promedio'];

                $updateSql = "UPDATE Stock_Valorizado 
                            SET Cantidad='$nuevaCantidad', 
                                Valor_Total='$nuevoValorTotal', 
                                Fecha_Actualizacion=NOW() 
                            WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
                
                $result = mysqli_query($link, $updateSql);
                if (!$result) throw new Exception(mysqli_error($link));
                
                $siigo->setLineInLog("[ActualizarPromedioPonderado] Venta - Suc.: $sucursal - Mon.: $moneda - Cant.: $cantidadNueva - Stock final: $nuevaCantidad");
            } else {
                $siigo->setLineInLog("[ActualizarPromedioPonderado] Venta - Suc.: $sucursal - Mon.: $moneda - Cant.: $cantidadNueva - Stock insuficiente");
				//throw new Exception("Stock insuficiente para la venta");
            }
		}

	} catch (Exception $e) {
        $siigo->setLineInLog('Error en ActualizarPromedioPonderado: '.$e->getMessage().' en '.__FILE__.' línea '.__LINE__);
        //throw $e; // Re-lanzar la excepción para que la función padre la maneje
	}

}

function RecalcularPromedioStock($sucursal, $moneda, $fecha, $ultCierre) {
	$siigo = new Siigo();
    $link = Conectarse();

    try {
        $PrevCierre = ObtenerPromedioV_CantSaldoCierre($sucursal, $moneda, $ultCierre);
        $siigo->setLineInLog('[RecalcularPromedioStock] $PrevCierre: ' . json_encode($PrevCierre));

        $cantidadPrev = ($PrevCierre) ? $PrevCierre['Cantidad_Saldo_Cierre'] : 0;
        $totalPrev    = ($PrevCierre) ? $PrevCierre['Valor_Total'] : 0;
        $promedioPrev = ($PrevCierre) ? $PrevCierre['Precio_Promedio_Entrada_V'] : 0;

        $sqlInsertStock = "REPLACE INTO Stock_Valorizado 
            (Sucursal, Moneda, Cantidad, Valor_Total, Promedio, Fecha_Actualizacion) VALUES
            ('$sucursal', '$moneda', '$cantidadPrev', '$totalPrev', '$promedioPrev', NOW())";
        $result = mysqli_query($link, $sqlInsertStock);
        if (!$result) throw new Exception(mysqli_error($link));

        $siigo->setLineInLog("[RecalcularPromedioStock] Reiniciado stock para Sucursal $sucursal y Moneda $moneda");

        $grpDate = explode('-', $fecha); 

		$operaciones = [];

		// Operaciones de ventanilla
		$sqlOp = "SELECT 
            `Identificacion`,`Codigo_Operacion`,`Tipo_Operacion`,`Sucursal`, 
            CONCAT(`Fecha`,' ',`Hora`) AS Fecha,
            `Estado_Operacion`, `Moneda`,`Precio_Sin_Iva`,`Cantidad`,`Valor`,
            `Medio_Pago`,`Margen_V`,`Ingreso_V`,`Valor_Descontado_V`,`Margen`,`Caja_Nacional`
            FROM `Operacion_Ventanilla` 
            WHERE Sucursal='$sucursal' AND Moneda='$moneda' 
            AND YEAR(`Fecha`)='".$grpDate[0]."' AND MONTH(`Fecha`)='".$grpDate[1]."' AND DAY(`Fecha`)='".$grpDate[2]."'
            AND Estado_Operacion='ACTIVO'
            ORDER BY `Fecha`, `Hora` ASC";
		$resultOp = mysqli_query($link, $sqlOp);
		if (!$resultOp) throw new Exception(mysqli_error($link));

        while($row = mysqli_fetch_assoc($resultOp)) {
            $operaciones[] = $row;
        }

		// Traslados de ventanilla (excluyendo los entre cajas)
		$sqlTrasl = "SELECT 
            `Identificacion`,`Codigo_Movimiento` as Codigo_Operacion, `Tipo_Movimiento` as Tipo_Operacion, 
            Sucursal, Fecha, Estado as Estado_Operacion, Moneda, Precio_Promedio as Precio_Sin_Iva, 
            Cantidad, Valor, Medio_Pago, 0 as Margen_V, 0 as Ingreso_V, 0 as Valor_Descontado_V, 
            0 as Margen, 0 as Caja_Nacional
            FROM `Traslados_Ventanilla` 
            WHERE Sucursal='$sucursal' AND Moneda='$moneda'
            AND YEAR(`Fecha`)='".$grpDate[0]."' AND MONTH(`Fecha`)='".$grpDate[1]."' AND DAY(`Fecha`)='".$grpDate[2]."'
            AND Estado='ACTIVO' AND Origen_Destino NOT IN('CAJA 01','CAJA 02','CAJA 03')  
            ORDER BY `Fecha` ASC";
		$resultTrasl = mysqli_query($link, $sqlTrasl);
		if (!$resultTrasl) throw new Exception(mysqli_error($link));

        while($row = mysqli_fetch_assoc($resultTrasl)) {
            $operaciones[] = $row;
        }

		// Ordenar todas las operaciones por fecha y hora
		usort($operaciones, function($a, $b) {
            return strtotime($a['Fecha']) - strtotime($b['Fecha']);
        });

		foreach($operaciones as $row) {

            $tipoOperacion = $row['Tipo_Operacion'];

            switch($tipoOperacion) {
                case 'COMPRA DE DIVISAS':

                    ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'compra');

                break;
                case 'VENTA DE DIVISAS':

                    $sqlStock = "SELECT Cantidad, Valor_Total, Promedio FROM Stock_Valorizado WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
                    $queryStock = mysqli_query($link, $sqlStock);
                    if (!$queryStock) throw new Exception(mysqli_error($link));
                    $stock = mysqli_fetch_assoc($queryStock);

                    if ($stock) {
                        $promedio = floatval($stock['Promedio']);
                        $cantidadActual = floatval($stock['Cantidad']);
                        $margenV = $row['Precio_Sin_Iva'] - $promedio;
                        $ingresoV = $row['Cantidad'] * $margenV;
                        $valorDescontadoV = $row['Caja_Nacional'] - $ingresoV;

                        // Actualizar operacion
                        $updateOp = "UPDATE Operacion_Ventanilla 
                                SET Margen_V='$margenV',
                                    Ingreso_V='$ingresoV',
                                    Valor_Descontado_V='$valorDescontadoV'
                                WHERE Identificacion='".$row['Identificacion']."'";
						$resultOpUp = mysqli_query($link, $updateOp);
                        if (!$resultOpUp) throw new Exception(mysqli_error($link));

                        if ($cantidadActual >= $row['Cantidad']) {
                            ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'venta');
                        } else {
							$siigo->setLineInLog("[RecalcularPromedioStock] Venta - Suc.: $sucursal - Mon.: $moneda - Cant.: ".$row['Cantidad']." - Stock insuficiente");
                            //throw new Exception("Stock insuficiente para venta de ".$row['Cantidad']);
                        }
                    }
				
                break;
                case 'INGRESO':

                    ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'compra');
					
                break;
                case 'EGRESO':

                    ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'venta');
	
                break;
            }

        }

        $siigo->setLineInLog("[RecalcularPromedioStock] Recalculo de stock para $moneda en sucursal $sucursal con fecha $fecha completado.");

    } catch (Exception $e) {
        $siigo->setLineInLog('[RecalcularPromedioStock] Error transaccional: '.$e->getMessage());
        throw $e;
    }
}

function RecuentoPostAnulado($sucursal, $moneda, $fecha) {
	$siigo = new Siigo();
	date_default_timezone_set('America/Bogota');
    $link = Conectarse();

	try {

		$queryDate = "SELECT Fecha FROM Cierres_Ventanilla 
                      WHERE Sucursal='$sucursal' AND Moneda='$moneda' AND Fecha < '$fecha' 
                      ORDER BY Fecha DESC LIMIT 1";
        $dataDate_ = mysqli_query($link, $queryDate);
        if (!$dataDate_) throw new Exception(mysqli_error($link));

		$dateData = mysqli_fetch_assoc($dataDate_);
        $siigo->setLineInLog('[RecuentoPostAnulado] Fecha cierre anterior: ' . json_encode($dateData));

		// Obtener último cierre
        $PrevCierre = ObtenerPromedioV_CantSaldoCierre($sucursal, $moneda, $dateData['Fecha']);
        $siigo->setLineInLog('[RecuentoPostAnulado] $PrevCierre: ' . json_encode($PrevCierre));

        $cantidadPrev = ($PrevCierre) ? $PrevCierre['Cantidad_Saldo_Cierre'] : 0;
        $totalPrev = ($PrevCierre) ? $PrevCierre['Valor_Total'] : 0;
        $promedioPrev = ($PrevCierre) ? $PrevCierre['Precio_Promedio_Entrada_V'] : 0;

		$sqlReplace = "REPLACE INTO Stock_Valorizado 
                       (Sucursal, Moneda, Cantidad, Valor_Total, Promedio, Fecha_Actualizacion) 
                       VALUES ('$sucursal', '$moneda', '$cantidadPrev', '$totalPrev', '$promedioPrev', NOW())";
        
        $result = mysqli_query($link, $sqlReplace);
        if (!$result) throw new Exception(mysqli_error($link));

        $siigo->setLineInLog('[RecuentoPostAnulado] Stock reiniciado con REPLACE');

		$grpDate = explode('-', $fecha);
        $operaciones = [];

		// Obtener operaciones de ventanilla
		$sqlOp = "SELECT 
            `Identificacion`,`Codigo_Operacion`,`Tipo_Operacion`,`Sucursal`, 
            CONCAT(`Fecha`,' ',`Hora`) AS Fecha,
            `Estado_Operacion`, `Moneda`,`Precio_Sin_Iva`,`Cantidad`,`Valor`,
            `Medio_Pago`,`Margen_V`,`Ingreso_V`,`Valor_Descontado_V`,`Margen`,`Caja_Nacional`
            FROM `Operacion_Ventanilla` 
            WHERE Sucursal='$sucursal' AND Moneda='$moneda' 
            AND YEAR(`Fecha`)='".$grpDate[0]."' AND MONTH(`Fecha`)='".$grpDate[1]."' AND DAY(`Fecha`)='".$grpDate[2]."'
            AND Estado_Operacion='ACTIVO'
            ORDER BY `Fecha`, `Hora` ASC";
        
        $result1 = mysqli_query($link, $sqlOp);
        if (!$result1) throw new Exception(mysqli_error($link));
        
        while($row = mysqli_fetch_assoc($result1)) {
            $operaciones[] = $row;
        }

		// Obtener traslados de ventanilla (excluyendo los entre cajas)
		$sqlTrasl = "SELECT 
            `Identificacion`,`Codigo_Movimiento` as Codigo_Operacion, `Tipo_Movimiento` as Tipo_Operacion, 
            Sucursal, Fecha, Estado as Estado_Operacion, Moneda, Precio_Promedio as Precio_Sin_Iva, 
            Cantidad, Valor, Medio_Pago, 0 as Margen_V, 0 as Ingreso_V, 0 as Valor_Descontado_V, 
            0 as Margen, 0 as Caja_Nacional
            FROM `Traslados_Ventanilla` 
            WHERE Sucursal='$sucursal' AND Moneda='$moneda'
            AND YEAR(`Fecha`)='".$grpDate[0]."' AND MONTH(`Fecha`)='".$grpDate[1]."' AND DAY(`Fecha`)='".$grpDate[2]."'
            AND Estado='ACTIVO' AND Origen_Destino NOT IN('CAJA 01','CAJA 02','CAJA 03')  
            ORDER BY `Fecha` ASC";
        
        $result2 = mysqli_query($link, $sqlTrasl);
        if (!$result2) throw new Exception(mysqli_error($link));
        
        while($row = mysqli_fetch_assoc($result2)) {
            $operaciones[] = $row;
        }

		// Ordenar todas las operaciones por fecha y hora
		usort($operaciones, function($a, $b) {
            return strtotime($a['Fecha']) - strtotime($b['Fecha']);
        });
	
		$siigo->setLineInLog('[RecuentoPostAnulado] Total operaciones a procesar: ' . count($operaciones));

		foreach($operaciones as $row) {
			$tipoOperacion = $row['Tipo_Operacion'];

			switch($tipoOperacion) {
				case 'COMPRA DE DIVISAS':

					$siigo->setLineInLog('[RecuentoPostAnulado] Cantidad en compra de divisas: '.$row['Cantidad']);
					$row['Precio_Sin_Iva'] = floatval($row['Precio_Sin_Iva']);
					ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'compra');
					$siigo->setLineInLog('[RecuentoPostAnulado] Compra de divisas en bucle: Sucursal '.$sucursal.', Moneda '.$moneda.', Cantidad '.$row['Cantidad'] . ' Precio ' . $row['Precio_Sin_Iva']);

				break;
				case 'VENTA DE DIVISAS':

					$siigo->setLineInLog('[RecuentoPostAnulado] Cantidad en venta de divisas: '.$row['Cantidad']);

					// Obtener stock actual
					$sqlStock = "SELECT Cantidad, Valor_Total, Promedio FROM Stock_Valorizado WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
					$queryStock = mysqli_query($link, $sqlStock);
					$stock = mysqli_fetch_assoc($queryStock);

					if ($stock) {
						$promedio = floatval($stock['Promedio']);
						$cantidadActual = floatval($stock['Cantidad']);
						$utilidad = ($row['Precio_Sin_Iva'] - $promedio) * $row['Cantidad'];
						$margenV = $row['Precio_Sin_Iva'] - $stock['Promedio'];
						$ingresoV = $row['Cantidad'] * $margenV;
						$valorDescontadoV = $row['Caja_Nacional'] - $ingresoV;

						// Actualizar utilidad en operación
						$updateOp = "UPDATE Operacion_Ventanilla 
                                    SET Margen_V='$margenV', Ingreso_V='$ingresoV', Valor_Descontado_V='$valorDescontadoV'
                                    WHERE Identificacion='".$row['Identificacion']."'";
                        $resultUpdate = mysqli_query($link, $updateOp);
                        if (!$resultUpdate) throw new Exception(mysqli_error($link));

						// Validar si hay suficiente cantidad
						if ($cantidadActual >= $row['Cantidad']) {
							$row['Precio_Sin_Iva'] = floatval($row['Precio_Sin_Iva']);
                            ActualizarPromedioPonderado($sucursal, $moneda, $row['Cantidad'], $row['Precio_Sin_Iva'], 'venta');
                            $siigo->setLineInLog('[RecuentoPostAnulado] Venta procesada: '.$row['Cantidad'].' de stock '.$cantidadActual);
                        } else {
                            $siigo->setLineInLog("[RecuentoPostAnulado] ADVERTENCIA: Stock insuficiente para $moneda en sucursal $sucursal. Venta de ".$row['Cantidad']." no aplicada. Stock actual: $cantidadActual");
                            // No lanzar excepción, solo registrar el problema
                        }
					} else {
						$siigo->setLineInLog("[RecuentoPostAnulado] ERROR: No existe stock registrado para $moneda en sucursal $sucursal");
					}

				break;
				case 'INGRESO':

					$cantidad = floatval($row['Cantidad']);
					$siigo->setLineInLog('[RecuentoPostAnulado] Cantidad en ingreso: '.$cantidad);
                    $precio = floatval($row['Precio_Sin_Iva']);
                    ActualizarPromedioPonderado($sucursal, $moneda, $cantidad, $precio, 'compra');
                    $siigo->setLineInLog('[RecuentoPostAnulado] Ingreso procesado: '.$cantidad.' a '.$precio);

				break;
				case 'EGRESO':
					
					$cantidad = floatval($row['Cantidad']);
					$siigo->setLineInLog('[RecuentoPostAnulado] Cantidad en egreso: '.$cantidad);
                    $precio = floatval($row['Precio_Sin_Iva']);
                    ActualizarPromedioPonderado($sucursal, $moneda, $cantidad, $precio, 'venta');
                    $siigo->setLineInLog('[RecuentoPostAnulado] Egreso procesado: '.$cantidad.' a '.$precio);

				break;
			}

		}

        $siigo->setLineInLog("[RecuentoPostAnulado] Recalculo completado por anulación.");

	} catch (Exception $e) {

        $siigo->setLineInLog('[RecuentoPostAnulado] Error transaccional: '.$e->getMessage());
        throw $e;

	}

}

function ObtenerPrecioValorizado($sucursal, $moneda) {

	$siigo = new Siigo();
	$link = Conectarse();
	$sucursal = mysqli_real_escape_string($link, $sucursal);
	$moneda = mysqli_real_escape_string($link, $moneda);

	$query = "SELECT Promedio FROM Stock_Valorizado WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
	$result = mysqli_query($link, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		return $row['Promedio'];
	} else {
		$siigo->setLineInLog('[RecuentoPostAnulado] No se encontró precio valorizado para $moneda en sucursal $sucursal.');
		return 0.0;
	}

}

function ObtenerPromedioStock($sucursal, $moneda) {

	$siigo = new Siigo();
	$link = Conectarse();
	$sucursal = mysqli_real_escape_string($link, $sucursal);
	$moneda = mysqli_real_escape_string($link, $moneda);

	$query = "SELECT Promedio FROM Stock_Valorizado WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
	$result = mysqli_query($link, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$siigo->setLineInLog('[ObtenerPromedioStock] Promedio obtenido de Stock para sucursal '.$sucursal.' y moneda '.$moneda.': '. json_encode($row));
		return floatval($row['Promedio']);
	} else {
		$siigo->setLineInLog('[ObtenerPromedioStock] No se encontró promedio para $moneda en sucursal $sucursal en Stock_Valorizado.');
		return 0.0;
	}

}

function ObtenerPromedioV_CantSaldoCierre($sucursal, $moneda, $fecha) {

	$siigo = new Siigo();
	$link = Conectarse();

	$Sql = "SELECT Precio_Promedio_Entrada_V, Cantidad_Saldo_Cierre FROM `Cierres_Ventanilla` 
	WHERE `Sucursal`='$sucursal' AND Moneda='$moneda' AND Estacion='01' AND Fecha='$fecha'";
	$siigo->setLineInLog('[ObtenerPromedioV_CantSaldoCierre] $sucursal: ' . $sucursal);
	$siigo->setLineInLog('[ObtenerPromedioV_CantSaldoCierre] $moneda: ' . $moneda);
	$siigo->setLineInLog('[ObtenerPromedioV_CantSaldoCierre] $fecha: ' . $fecha);

	$p = mysqli_query($link, $Sql) or die(mysqli_error($link));
	$res = mysqli_fetch_assoc($p);

	$siigo->setLineInLog('[ObtenerPromedioV_CantSaldoCierre] $res: ' . json_encode($res));
	$calcValorizado = $res['Precio_Promedio_Entrada_V'] * $res['Cantidad_Saldo_Cierre'];
	
	$opt = [
		'Precio_Promedio_Entrada_V' => ($res) ? $res['Precio_Promedio_Entrada_V'] : 0,
		'Cantidad_Saldo_Cierre' => ($res) ? $res['Cantidad_Saldo_Cierre'] : 0,
		'Valor_Total' => $calcValorizado
	];

	return $opt;
}

function GetPromedioStock($sucursal, $moneda) {
	$link = Conectarse();
	$sucursal = mysqli_real_escape_string($link, $sucursal);
	$moneda = mysqli_real_escape_string($link, $moneda);

	$query = "SELECT Promedio FROM Stock_Valorizado WHERE Sucursal='$sucursal' AND Moneda='$moneda'";
	$result = mysqli_query($link, $query);
	if ($result && mysqli_num_rows($result) > 0) {
		return mysqli_fetch_assoc($result);
	} else {
		$siigo->setLineInLog("[GetPromedioStock] No se encontró stock valorizado para $moneda en sucursal $sucursal.");
		return null;
	}
}

?>