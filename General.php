<?php
//Archivo clases generales
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
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
	case "RegCount":
		$calFun = RegCount();
		break;
	case "Gen_Accept":
		$calFun = Gen_Accept();
		break;
	case "Gen_Find":
		$calFun = Gen_Find();
		break;
	case "Gen_Find_Field":
		$calFun = Gen_Find_Field();
		break;
	case "Gen_Find_Field_Clinton":
		$calFun = Gen_Find_Field_Clinton();
		break;
	case "Gen_Modif":
		$calFun = Gen_Modif();
		break;
	case "Gen_Delete":
		$calFun = Gen_Delete();
		break;
	case "ActCaja":
		$calFun = ActCaja();
		break;
	case "AddSerie":
		$calFun = AddSerie();
		break;
	case "AddSerieNew":
		$calFun = AddSerieNew();
		break;
	case "Gen_Update_Field":
		$calFun = Gen_Update_Field();
		break;
	case "SiplaOps":
		$calFun = SiplaOps();
		break;
	case "AddCorreo":
		$calFun = AddCorreo();
		break;
	case "SaveInvoiceToSend":
		$calFun = SaveInvoiceToSend();
		break;


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
    if (!($link=mysqli_connect("localhost","nym_soipc","Qot^V4h]sfI9"))) {
      echo "Error conectando a la base de datos.";
      exit();
    }
    if (!mysqli_select_db($link, "nym_soipc")) {
      echo "Error seleccionando la base de datos.";
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
	$p=mysqli_query($link, "SELECT * FROM Usuarios WHERE Identificacion = '$sId'") or die(mysqli_error($link));
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
function SucSet($link, $sId)
{
	$p=mysqli_query($link, "SELECT * FROM Sucursales WHERE Codigo_Sucursal = '$sId'") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 14; $i++) {
			global $sSucSet;
			$sSucSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion de carga de configuracion cambiaria
function ExcSet($link)
{
	$p=mysqli_query($link, "select * from Configuracion_Cambiaria") or die(mysqli_error($link));
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
	$p=mysqli_query($link, "select * from Configuracion_Contable WHERE Sucursal='". $sSuc. "'") or die(mysqli_error($link));
	while($n=mysqli_fetch_array($p)){
		for ($i = 0; $i <= 5; $i++) {
			global $sTaxSet;
			$sTaxSet[$i] = $n[$i];
		}
	}
}
//-----------------------------------------------------------------
//Funcion para capturar cantidad de registros en una tabla
function RegCount()
{
	//Captura variables
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
	//--------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
	//Consulta si el registro existe en la base de datos
	$p=mysqli_query($link, "SELECT * FROM ". $stable ." where ". $swhere) or die(mysqli_error($link));
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
				$funval = $funval. '<td class="celrow" id="cel'.$m. '-'. $j .'">'. $n[$j]. '</td>';
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
				$funval = $funval. '<td style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px;" class="celrow" id="cel'.$m. '-'. $j .'">'. filter_var($n[$j], FILTER_SANITIZE_STRING). '</td>';
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
	//Captura variables
	$var[0]= (isset($_GET['var0'])) ? $_GET['var0'] : '';
	for($i = 1; $i <= $var[0]; $i++)
	{
		$var[$i] = (isset($_GET['var'.$i])) ? $_GET['var'.$i] : '';
	}
	$stable = (isset($_GET['stable'])) ? $_GET['stable'] : '';
	//--------------------------------------------------
	$link=Conectarse();
	//Construye string de registro
	$Sql = "INSERT INTO ". $stable. " VALUES (";
	for($i = 1; $i <= $var[0]; $i++)
	{
		if($i == $var[0]){
			$Sql = $Sql. "'". $var[$i]. "'";
		} else {
			$Sql = $Sql. "'". $var[$i]. "',";
		}
	}
	$Sql = $Sql. ")";
	//Registro en base de datos	
	mysqli_query($link, $Sql);
	//-------------------------------------------------
	$mensaje = 10;	//Mensaje exitoso
	echo $mensaje;
}
//------------------------------------------------------
//Funcion generica para eliminar registro 
function Gen_Delete()
{
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
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
//------------------------------------------------------
//Funcion generica para modificar registro 
function Gen_Modif()
{
	//Captura variables
	$var[0]=$_GET['var0'];
	for($i = 1; $i <= $var[0]; $i++)
	{
		$var[$i] = $_GET['var'.$i];
	}
	$stable =$_GET['stable'];
	$swhere =$_GET['swhere'];
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
		for($i = 1; $i <= $var[0]; $i++)
		{
			//Construye substring de campo y valor
			$varset = '';
			$varset = explode('.|.', $var[$i]);
			if($i == $var[0]){
				$Sql = $Sql. $varset[0]. "='". $varset[1]. "'";
			} else {
				$Sql = $Sql. $varset[0]. "='". $varset[1]. "',";
			}
		}
		$Sql = $Sql. " WHERE ". $swhere;
		//Registro en base de datos	
		mysqli_query($link, $Sql); 
		//-------------------------------------------------
		$mensaje = 10;	//Mensaje exitoso
	}
	echo $mensaje;
}
//-------------------------------------------------
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
		while($n = mysqli_fetch_array($p)){
			for($i = 1; $i <= $var[0]; $i++)
			{
				if($i == $var[0]){
					$sregm = $sregm. $n[$i - 1]; 
				} else {
					$sregm = $sregm. $n[$i - 1]. '.|.'; 
				}
			}
		}
		//-------------------------------------------------
		$mensaje = $sregm;	//Mensaje exitoso
		echo $mensaje;
	} else {
		echo $mensaje;
	}
}
//-------------------------------------------------
//Funcion para buscar un solo registro y campo de base
function Gen_Find_Field()
{
	$strSQL = $_GET['strSQL'];
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
		$mensaje = $n[0];
	}
	//-------------------------------------------------
	return $mensaje;
}

//--------------------------------------------------
//Procedimiento para actualizar los saldos de caja
function ActCaja()
{
	$sparvec =$_GET['strSQL'];
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
		$dSerie = $n[Consecutivo]; 
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
	$sCode =$_GET['strSQL'];
	//--------------------------------------------------------
    $strSQLS = "SELECT Consecutivo FROM XConf_Consecutivos WHERE Identificacion = '".  $sCode. "'";
	$link = Conectarse();
	//Consulta consecutivo actual
	$p = mysqli_query($link, $strSQLS) or die(mysqli_error());
	while($n = mysqli_fetch_array($p)) {
		$dSerie = $n[Consecutivo]; 
		$dSerieO = $n[Consecutivo]; 
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
	$stable = $_GET['stable'];
	$swhere = $_GET['swhere'];
	$sfield = $_GET['sfield'];
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
	$sops = $_GET['sops'];	//Tipo operacion
	$sseg = $_GET['sseg'];	//Segmento mercado
	$sidc = $_GET['sidc'];	//Identificación del cliente
	$sval = $_GET['sval'];	//Valor en usd de la operacion
	$sfec = $_GET['sfec'];	//fecha actual
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
	$strSQLS = "SELECT DISTINCT Ano_Mes FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '". sidc. "' AND Tipo_Operacion = '". $sops. "' AND Estado_Operacion = 'ACTIVO'";
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
	$swhere =$_GET['swhere'];
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
?>