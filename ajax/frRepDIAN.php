<?php
//ARCHIVO FUNCIONES FRREPDIAN.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "XmlComp":
		$calFun = XmlComp();
		break;
	case "XmlVen":
		$calFun = XmlVen();
		break;


	default:
}
//--------------------------------------------------
//funcion para envio de mensaje de progreso
function LoadMsg($sFLMs)
{
	echo "prog.|.". $sFLMs .".-.";
}
//--------------------------------------------
//Funcion ultimo mensaje de creacion exitosa o 
//con errores
function LoadFin($sFin, $sFLMs)
{
	echo $sFin .".|.". $sFLMs .".-.";
}
//----------------------------------------
//Funcion para enviar mensaje de error
function RegError($sReg, $sFact, $sErr)
{
	$sfun = '';
	$sfun = '<tr valign="middle" class="fcont">';
	$sfun = $sfun .'<td class="celrow" align="left">'. $sReg .'</td>';
	$sfun = $sfun .'<td class="celrow" align="left">'. $sFact .'</td>';
	$sfun = $sfun .'<td class="celrow" align="left">'. $sErr .'</td>';
	$sfun = $sfun .'</tr>';
	echo "error.|.". $sfun .".-.";
}
//-------------------------------------------
//Funcion para reemplazar caracteres no válidos
function sNames($sNA)
{
	return str_replace("%", "", str_replace(">", "", str_replace("<", "", str_replace("&", "Y", $sNA))));
}
//-------------------------------------------
//Funcion para crear archivo de compra
function XmlComp()
{
	set_time_limit(0); 
	ob_implicit_flush(true);
	ob_end_flush();	
	//--------------------------------------------
	//Captura varuables
	$swhere =$_GET['swhere'];
	$sano =$_GET['ano']; 
	$scons =$_GET['cons']; 
	$sserie =$_GET['serie']; 
	$screa =$_GET['crea'];
	$strim =$_GET['trim'];
	$sanotr =$_GET['anotr'];
	//------------------------------------------
	//Variables para cobntrol de errores
	$iTot = 0;
    $sErrorX = '';
    $iCont = 1;
    $dValIva = 0;
    $sFact = '';
    $bErrorX = 'false';
	//-------------------------------------------
	LoadMsg('Iniciando creación de archivo...');
    $sErrorX = "Creación nombre de archivo";
	$sNomAr[0] = $scons;
    $sNomAr[1] = "01099";
    $sNomAr[2] = "06";
    $sNomAr[3] = $sano;
    $sNomAr[4] = str_pad('', 8 - strlen($sserie), "0", STR_PAD_LEFT). $sserie;
    $sNomAr[5] = "Dmuisca_". $sNomAr[0]. $sNomAr[1]. $sNomAr[2]. $sNomAr[3]. $sNomAr[4];
	sleep(2);
    LoadMsg("Creando archivo en servidor...");
    $sErrorX = "Creación de archivo en ruta";
	$fXml = fopen("../reportes/DIAN/". $sNomAr[5] .".xml", "w");
	//-------------------------------------------
	sleep(2);
	LoadMsg("Creando encabezado de archivo...");
	$sErrorX = "Escritura encabezado de archivo";
    $i = 0;
    $sCabR = '';
    $sCabe[0] = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $sCabe[1] = '<mas xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="../xsd/1099.xsd">';
    $sCabe[2] = '<Cab>';
    $sCabe[3] = '<Ano>'. $sano .'</Ano>';
    $sCabCon = '';
    if($sNomAr[0] == "01"){
		$sCabCon = "1";
    } else { 
        $sCabCon = "2";
    }
    $sCabe[4] = '<CodCpt>'. $sCabCon .'</CodCpt>';
    $sCabe[5] = '<Formato>1099</Formato>';
    $sCabe[6] = '<Version>6</Version>';
    $sCabe[7] = '<NumEnvio>'. $sserie .'</NumEnvio>';
    $sCabe[8] = '<FecEnvio>'. $screa .'</FecEnvio>';
	//Creación de los string de fecha (superior/inferior) de reporte
    //Siempre deben ir los limites del trimestre independiente de la
    //fecha inicial y final seleccionadas en la ventana del reporte
    $sCabFecIn = '';
    $sCabFecFi = '';
	if($strim == 'Enero - Marzo'){
		$sCabFecIn = $sanotr ."-01-01";
        $sCabFecFi = $sanotr ."-03-31";
	} else if($strim == 'Abril - Junio'){
		$sCabFecIn = $sanotr ."-04-01";
        $sCabFecFi = $sanotr ."-06-30";
	} else if($strim == 'Julio - Septiembre'){
		$sCabFecIn = $sanotr ."-07-01";
        $sCabFecFi = $sanotr ."-09-30";
	} else {
		$sCabFecIn = $sanotr ."-10-01";
        $sCabFecFi = $sanotr ."-12-31";
	}
	$sCabe[9] = '<FecInicial>'. $sCabFecIn .'</FecInicial>';
    $sCabe[10] = '<FecFinal>'. $sCabFecFi .'</FecFinal>';
	//-------------------------------------------------------------
	//Obtiene el valor total del IVA
	$link=Conectarse();
	//--------------------------------------------------
    $strSQLV = "SELECT COUNT(Identificacion), SUM(ROUND(IVA)) FROM Operacion_Ventanilla WHERE ". $swhere;
	$p=mysqli_query($link, $strSQLV) or die(mysqli_error($link));
	$n=mysqli_fetch_array($p);
	$dValIva = $n[1];
	$iTot = $n[0];
    $sCabe[11] = '<ValorTotal>'. $dValIva .'</ValorTotal>';
    $sCabe[12] = '<CantReg>'. $iTot .'</CantReg>';
    $sCabe[13] = '</Cab>';
    //Escribe registro
	for ($i = 0; $i <= 13; $i++) {
		$sCabR = $sCabR. $sCabe[$i];
	}
	fwrite($fXml, $sCabR ."\r\n"); 
	//Cierra archivo para volverlo a abrir append
	fclose($fXml);
	$fXml1 = fopen("../reportes/DIAN/". $sNomAr[5] .".xml", "a");
	//----------------------------------------------
    //Consulta y escritura de registros
	sleep(2);
    LoadMsg("Consultando operaciones para archivo...");
    $sErrorX = "Consulta de operaciones";
    $strSQL = "SELECT * FROM Operacion_Ventanilla WHERE ". $swhere;
	$q=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	sleep(2);
    LoadMsg("Iniciando escritura de registros...");
	while($s=mysqli_fetch_array($q)){
		//Limpia variables
		$sReg = '';
       	$bDoDec = 1;
		for ($i = 0; $i <= 25; $i++) {
			$sDec[$i] = '';
		}
		for ($i = 0; $i <= 39; $i++) {
			$sCuer[$i] = '';
		}
		//-------------------------------------------------------------------
		usleep(1000);
        LoadMsg("Creando registro ". $iCont ." de ". $iTot);
        //----------------------------------------------------------------------
        //Captura numero de factura para error
        $sFact = $s[3] ." - ". $s[10];
        //----------------------------------------------------------------------
        //Tipo de negociación y factura
        if (strlen($s[33]) == 4){
        	$sCuer[0] = '<cdivisas tneg="2" ';
        } else {
            $sCuer[0] = '<cdivisas tneg="1" ';
        }
        $sCuer[1] = 'nfac="'. $s[11]. $s[10] .'" ';
        //----------------------------------------------------------------------
        //Información del declarante
        $sErrorX = "Consulta información Declarante ". $s[30]; 
        $strSQLC = "SELECT * FROM Clientes WHERE Identificacion = '". $s[30] ."'";
		$cd=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
		$concd=mysqli_num_rows($cd);
		if($concd != 1)
		{
			usleep(5000);
			RegError($iCont, $sFact, $sErrorX);
            $bDoDec = 'false';
            $bErrorX = 'true';
			//----------------------------------------------
			//Provisional
			//$strSQLDEL = "DELETE FROM Clientes WHERE Identificacion='". $s[30] ."' ORDER BY Fecha_Creacion Limit 1";
			//mysqli_query($link, $strSQLDEL);
		} else {
			$sd=mysqli_fetch_array($cd);
			for ($i = 0; $i <= 25; $i++) {
				$sDec[$i] = $sd[$i];
			}
            $bDoDec = 'true';
		}
		if($bDoDec == 'true'){
			//Tipo documento
			$sErrorX = "Tipo Documento Declarante";
			$strSQLTD = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento = '". $sDec[3] ."'";
			$sCuer[2] = 'tdoc="'. ReadSerie_1($link, $strSQLTD) .'" ';
			//Numero de documento y digito DV
			$sErrorX = "Número de Documento y DV de Declarante";
			$sCuer[3] = 'nid="'. $sDec[1] .'" ';
			if($sDec[2] != ''){$sCuer[4] = 'dv="'. $sDec[2] .'" ';}
			//Nombres y apellidos
			$sErrorX = "Apellidos y nombres del Declarante";
			//Apellido 1
			if($sDec[6] != ''){$sCuer[5] = 'apl1="'. sNames($sDec[6]) .'" ';}
			//Apellido 2
			if($sDec[7] != ''){$sCuer[6] = 'apl2="'. sNames($sDec[7]) .'" ';}
			//Nombre 1
			if($sDec[4] != ''){$sCuer[7] = 'nom1="'. sNames($sDec[4]) .'" ';}
			//Nombre 2
			if($sDec[5] != ''){$sCuer[8] = 'nom2="'. sNames($sDec[5]) .'" ';}
			//Direccion
			$sErrorX = "Dirección del Declarante";
			$sCuer[9] = 'dirde="'. rtrim($sDec[13]) .'" ';
			//Municipio
			$sErrorX = "Consulta Código Municipio Declarante";
			$strSQLM = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad = '". $sDec[15] ."' AND Departamento = '". $sDec[14] ."'";
			$valCDec = ReadSerie_1($link, $strSQLM);
			if(strlen($valCDec) == 4){$valCDec = '0'. $valCDec;}
			$sCuer[10] = 'mun="'. $valCDec .'" ';
			//Telefono
			$sErrorX = "Teléfono del Declarante";
			if($sDec[11] == ''){ //Celular
				$sCuer[11] = 'telde="'. $sDec[12] .'" ';
			} else {
				$sCuer[11] = 'telde="'. $sDec[11] .'" ';
			}
			//Actividad Económica
			$sErrorX = "Consulta Actividad Económica Declarante";
			$sAct = rtrim($sDec[16]);
			$strSQLA = "SELECT Codigo_Actividad FROM XConf_Actividades WHERE Actividad LIKE '%". $sAct ."%'";
			$valADec = ReadSerie_1($link, $strSQLA);
			if($valADec == ''){$valADec = '0010';}
			$sCuer[12] = 'actde="'. $valADec .'" ';
		}
		//---------------------------------------------------------
		//Información del Beneficiario
		//Valida info de beneficiario
		$sErrorX = "Validando información del beneficiario ". $s[13];
       	$bDoBen = 'true';
        $strSQLCBen = "SELECT * FROM Clientes WHERE Identificacion = '". $s[13] ."'";
		$cdb=mysqli_query($link, $strSQLCBen) or die(mysqli_error($link));
		$concdb=mysqli_num_rows($cdb);
		if($concdb != 1)
		{
			usleep(5000);
			RegError($iCont, $sFact, $sErrorX);
            $bDoBen = 'false';
            $bErrorX = 'true';
		}
		if($bDoBen == 'true'){
			//Tipo documento
			$sErrorX = "Tipo Documento Beneficiario";
			$strSQLTB = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento = '". $s[14] ."'";
			$sCuer[13] = 'tdocb="'. ReadSerie_1($link, $strSQLTB) .'" ';
			//Numero de documento y digito DV
			$sErrorX = "Consulta Número Documento Beneficiario";
			$strSQLDB = "SELECT Documento FROM Clientes WHERE Identificacion = '". $s[13] ."'";
			$sCuer[14] = 'nidb="'. ReadSerie_1($link, $strSQLDB) .'" ';
			$sErrorX = "Consulta Digito Verificación Beneficiario";
			$sDVB = '';
			$strSQLDV = "SELECT DV FROM Clientes WHERE Identificacion = '". $s[13] ."'";
			$sDVB = ReadSerie_1($link, $strSQLDV);
			if($sDVB != ''){$sCuer[15] = 'dvb="'. $sDVB .'" ';}
			//Nombres y apellidos
			$sErrorX = "Apellidos y Nombres del Beneficiario";
            if($s[14] == "NIT"){  //Razon social
                $sCuer[20] = 'razb="'. sNames($s[19]) .'" ';
            } else {
                //Apellido 1
                if($s[17] != ''){$sCuer[16] = 'apl1b="'. sNames($s[17]) .'" ';}
                //Apellido 2
                if($s[18] != ''){$sCuer[17] = 'apl2b="'. sNames($s[18]) .'" ';}
                //Nombre 1
                if($s[15] != ''){$sCuer[18] = 'nom1b="'. sNames($s[15]) .'" ';}
                //Nombre 2
                if($s[16] != ''){$sCuer[19] = 'nom2b="'. sNames($s[16]) .'" ';}
            }
            //Direccion
            $sErrorX = "Dirección del Beneficiario";
            $sCuer[21] = 'dirb="'. rtrim($s[22]) .'" ';
            //Municipio
            $sErrorX = "Consulta Código Municipio Beneficiario";
            $strSQLMB = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad = '". $s[24] ."' AND Departamento = '". $s[23] ."'";
			$valCBen = ReadSerie_1($link, $strSQLMB);
			if(strlen($valCBen) == 4){$valCBen = '0'. $valCBen;}
            $sCuer[22] = 'munb="'. $valCBen .'" ';
            //Telefono
            $sErrorX = "Consulta Teléfono del Beneficiario";
            $sTelB = '';
            $sCelB = '';
            $strSQLLB = "SELECT Telefono FROM Clientes WHERE Identificacion = '". $s[13] ."'";
            $strSQLCB = "SELECT Celular FROM Clientes WHERE Identificacion = '". $s[13] ."'";
            $sTelB = ReadSerie_1($link, $strSQLLB);
            $sCelB = ReadSerie_1($link, $strSQLCB);
            if($sTelB != "0" && $sTelB != ''){
                $sCuer[23] = 'telb="'. $sTelB .'" ';
            } else {
                $sCuer[23] = 'telb="'. $sCelB .'" ';
            }
            // Actividad Económica
            $sErrorX = "Consulta Actividad Económica Beneficiario";
            $sActB = rtrim($s[25]);
            $strSQLAB = "SELECT Codigo_Actividad FROM XConf_Actividades WHERE Actividad LIKE '%". $sActB ."%'";
			$valABen = ReadSerie_1($link, $strSQLAB);
			if($valABen == ''){$valABen = '0010';}
			$sCuer[24] = 'actb="'. $valABen .'" ';
		}
		//----------------------------------------------------------------------
		//Datos de la operacion
        $sErrorX = "Información de la Operación";
        //Moneda
         if ($s[33] == "TUSD") {
            $sCuer[25] = 'cmon="USD" ';
            } elseif ($s[33] == "USD2") {
             $sCuer[25] = 'cmon="USD" ';
            } elseif ($s[33] == "EUR5") {
             $sCuer[25] = 'cmon="EUR" ';
             } else {
            $sCuer[25] = 'cmon="'. $s[33] .'" ';
        }

        //Cantidad
        $sCuer[26] = 'monto="'. round($s[36], 2) .'" ';
        //Precio Compra
        $sCuer[27] = 'tcom="'. round($s[35], 4) .'" ';
        //Valor en pesos
        $sCuer[28] = 'valpe="'. round($s[37], 2) .'" ';
        //Valor IVA
        $sCuer[29] = 'valiva="'. round($s[41], 0) .'" ';
        //Rete IVA
        $sCuer[30] = 'retiva="'. round($s[44], 0) .'" ';
        //Rete Fuente
        $sCuer[31] = 'retfte="'. round($s[42], 0) .'" ';
        //Rete ICA
        $sCuer[32] = 'retica="'. round($s[43], 0) .'" ';
        //4 x 1000
        $sCuer[33] = 'grav="'. round($s[45], 0) .'" ';
        //Valor Neto
        $sCuer[34] = 'valne="'. round($s[46], 2) .'" ';
		//-------------------------------------------------------------------------
        //Forma de Pago
		$sErrorX = "Forma de Pago de la Operación";
		if($s[48] == "EFECTIVO"){
			$sCuer[35] = 'fpag="1" ';
		} else {
			$sCuer[35] = 'fpag="2" ';
			//Consulta codigo Banco
			$sErrorX = "Consulta Código de Banco";
			$strSQLBA = "SELECT Codigo_Banco FROM XConf_Bancos WHERE Nombre_Banco = '". $s[50] ."'";
			$sCuer[36] = 'codba="'. ReadSerie_1($link, $strSQLBA) .'" ';
			$sCuer[37] = 'nunch="'. $s[52] .'" ';
		}
		$sErrorX = "Últimos dos campos del registro";
		$sCuer[38] = 'numdec="'. $s[11]. $s[10] .'" ';
		$sCuer[39] = 'fecdec="'. $s[6] .'"';
		//----------------------------------------------------------------------
        //Escribe registro en archivo
		usleep(5000);
        LoadMsg("Escribiendo registro ". $iCont ." de ". $iTot);
        $sErrorX = "Escritura de registro ". $iCont;
		for ($i = 0; $i <= 39; $i++) {
			$sReg = $sReg. $sCuer[$i];
		}
		fwrite($fXml1, $sReg ."/>\r\n"); 
        //----------------------------------------------------------------------
        //Suma unidad a registro y pasa a siguiente
        $iCont++;
	}
	//---------------------------------------------------------	
	//Cierra archivo y base de datos
	sleep(1);
    LoadMsg("Terminando creación de archivo...");
	fwrite($fXml1, "</mas>"); 
	fclose($fXml1);
	//--------------------------------------------------------------------------------
    //Mensaje terminación de archivo
	sleep(1);
	if($bErrorX == 'false'){
		//LoadMsg("Archivo XML Creado exitosamente!");
		sleep(2);
		LoadFin('finsin', "El archivo XML de Compra de Divisas, con consecutivo 000". $sserie ." se ha creado exitosamente con el nombre ". $sNomAr[5] .".xml. Se recomienda enviar este reporte directamente y no intentar abrirlo o modificarlo.");
		sleep(2);
		echo $sNomAr[5] .".xml";
	} else {
		//LoadMsg("Errores en Archivo XML!");
		sleep(2);
		LoadFin('finerror', "La información del reporte contiene errores. En la tabla de la parte inferior se listan las operaciones con errores las cuales deben ser corregidas.");
		sleep(2);
		echo $sNomAr[5] .".xml";
	}
}
//-------------------------------------------
//Funcion para crear archivo de venta
function XmlVen()
{
	set_time_limit(0); 
	ob_implicit_flush(true);
	ob_end_flush();	
	//--------------------------------------------
	//Captura varuables
	$swhere =$_GET['swhere'];
	$sano =$_GET['ano']; 
	$scons =$_GET['cons']; 
	$sserie =$_GET['serie']; 
	$screa =$_GET['crea'];
	$strim =$_GET['trim'];
	$sanotr =$_GET['anotr'];
	//------------------------------------------
	//Variables para cobntrol de errores
	$iTot = 0;
    $sErrorX = '';
    $iCont = 1;
    $dValIva = 0;
    $sFact = '';
    $bErrorX = 'false';
	//-------------------------------------------
	LoadMsg('Iniciando creación de archivo...');
    $sErrorX = "Creación nombre de archivo";
	$sNomAr[0] = $scons;
    $sNomAr[1] = "01100";
    $sNomAr[2] = "06";
    $sNomAr[3] = $sano;
    $sNomAr[4] = str_pad('', 8 - strlen($sserie), "0", STR_PAD_LEFT). $sserie;
    $sNomAr[5] = "Dmuisca_". $sNomAr[0]. $sNomAr[1]. $sNomAr[2]. $sNomAr[3]. $sNomAr[4];
	sleep(2);
    LoadMsg("Creando archivo en servidor...");
    $sErrorX = "Creación de archivo en ruta";
	$fXml = fopen("../reportes/DIAN/". $sNomAr[5] .".xml", "w");
	//-------------------------------------------
	sleep(2);
	LoadMsg("Creando encabezado de archivo...");
	$sErrorX = "Escritura encabezado de archivo";
    $i = 0;
    $sCabR = '';
    $sCabe[0] = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $sCabe[1] = '<mas xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="../xsd/1100.xsd">';
    $sCabe[2] = '<Cab>';
    $sCabe[3] = '<Ano>'. $sano .'</Ano>';
    $sCabCon = '';
    if($sNomAr[0] == "01"){
		$sCabCon = "1";
    } else { 
        $sCabCon = "2";
    }
    $sCabe[4] = '<CodCpt>'. $sCabCon .'</CodCpt>';
    $sCabe[5] = '<Formato>1100</Formato>';
    $sCabe[6] = '<Version>6</Version>';
    $sCabe[7] = '<NumEnvio>'. $sserie .'</NumEnvio>';
    $sCabe[8] = '<FecEnvio>'. $screa .'</FecEnvio>';
	// Creación de los string de fecha (superior/inferior) de reporte
    // Siempre deben ir los limites del trimestre independiente de la
    // fecha inicial y final seleccionadas en la ventana del reporte
    $sCabFecIn = '';
    $sCabFecFi = '';
	if($strim == 'Enero - Marzo'){
		$sCabFecIn = $sanotr ."-01-01";
        $sCabFecFi = $sanotr ."-03-31";
	} else if($strim == 'Abril - Junio'){
		$sCabFecIn = $sanotr ."-04-01";
        $sCabFecFi = $sanotr ."-06-30";
	} else if($strim == 'Julio - Septiembre'){
		$sCabFecIn = $sanotr ."-07-01";
        $sCabFecFi = $sanotr ."-09-30";
	} else {
		$sCabFecIn = $sanotr ."-10-01";
        $sCabFecFi = $sanotr ."-12-31";
	}
	$sCabe[9] = '<FecInicial>'. $sCabFecIn .'</FecInicial>';
    $sCabe[10] = '<FecFinal>'. $sCabFecFi .'</FecFinal>';
	//-------------------------------------------------------------
	//Obtiene el valor total del IVA
	$link=Conectarse();
	//--------------------------------------------------
    $strSQLV = "SELECT COUNT(Identificacion), SUM(ROUND(IVA)) FROM Operacion_Ventanilla WHERE ". $swhere;
	$p=mysqli_query($link, $strSQLV) or die(mysqli_error($link));
	$n=mysqli_fetch_array($p);
	$dValIva = $n[1];
	$iTot = $n[0];
    $sCabe[11] = '<ValorTotal>'. $dValIva .'</ValorTotal>';
    $sCabe[12] = '<CantReg>'. $iTot .'</CantReg>';
    $sCabe[13] = '</Cab>';
    //Escribe registro
	for ($i = 0; $i <= 13; $i++) {
		$sCabR = $sCabR. $sCabe[$i];
	}
	fwrite($fXml, $sCabR ."\r\n"); 
	//Cierra archivo para volverlo a abrir append
	fclose($fXml);
	$fXml1 = fopen("../reportes/DIAN/". $sNomAr[5] .".xml", "a");
	//----------------------------------------------
    //Consulta y escritura de registros
	sleep(2);
    LoadMsg("Consultando operaciones para archivo...");
    $sErrorX = "Consulta de operaciones";
    $strSQL = "SELECT * FROM Operacion_Ventanilla WHERE ". $swhere;
	$q=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	sleep(2);
    LoadMsg("Iniciando escritura de registros...");
	while($s=mysqli_fetch_array($q)){
		//Limpia variables
		$sReg = '';
       	$bDoDec = 1;
		for ($i = 0; $i <= 25; $i++) {
			$sDec[$i] = '';
		}
		for ($i = 0; $i <= 42; $i++) {
			$sCuer[$i] = '';
		}
		//-------------------------------------------------------------------
		usleep(1000);
        LoadMsg("Creando registro ". $iCont ." de ". $iTot);
        //----------------------------------------------------------------------
        //Captura numero de factura para error
        $sFact = $s[3] ." - ". $s[10];
        //----------------------------------------------------------------------
        //Tipo de negociación y factura
        if (strlen($s[33]) == 4){
        	$sCuer[0] = '<vdivisas tneg="2" ';
        } else {
            $sCuer[0] = '<vdivisas tneg="1" ';
        }
        $sCuer[1] = 'nfac="'. $s[11]. $s[10] .'" ';
        //----------------------------------------------------------------------
        // Información del declarante
        $sErrorX = "Consulta información Declarante ". $s[30]; 
        $strSQLC = "SELECT * FROM Clientes WHERE Identificacion = '". $s[30] ."'";
		$cd=mysqli_query($link, $strSQLC) or die(mysqli_error($link));
		$concd=mysqli_num_rows($cd);
		if($concd != 1)
		{
			usleep(5000);
			RegError($iCont, $sFact, $sErrorX);
            $bDoDec = 'false';
            $bErrorX = 'true';
		} else {
			$sd=mysqli_fetch_array($cd);
			for ($i = 0; $i <= 25; $i++) {
				$sDec[$i] = $sd[$i];
			}
            $bDoDec = 'true';
		}
		//-------------------------------------------------
		if($bDoDec == 'true'){
			//Tipo documento
			$sErrorX = "Tipo Documento Declarante";
			$strSQLTD = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento = '". $sDec[3] ."'";
			$sCuer[2] = 'tdoc="'. ReadSerie_1($link, $strSQLTD) .'" ';
			//Numero de documento y digito DV
			$sErrorX = "Número de Documento y DV de Declarante";
			$sCuer[3] = 'nid="'. $sDec[1] .'" ';
			if($sDec[2] != ''){$sCuer[4] = 'dv="'. $sDec[2] .'" ';}
			//Nombres y apellidos
			$sErrorX = "Apellidos y nombres del Declarante";
			//Apellido 1
			if($sDec[6] != ''){$sCuer[5] = 'apl1="'. sNames($sDec[6]) .'" ';}
			//Apellido 2
			if($sDec[7] != ''){$sCuer[6] = 'apl2="'. sNames($sDec[7]) .'" ';}
			//Nombre 1
			if($sDec[4] != ''){$sCuer[7] = 'nom1="'. sNames($sDec[4]) .'" ';}
			//Nombre 2
			if($sDec[5] != ''){$sCuer[8] = 'nom2="'. sNames($sDec[5]) .'" ';}
			//Direccion
			$sErrorX = "Dirección del Declarante";
			$sCuer[9] = 'dirde="'. rtrim($sDec[13]) .'" ';
			//Municipio
			$sErrorX = "Consulta Código Municipio Declarante";
			$strSQLM = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad = '". $sDec[15] ."' AND Departamento = '". $sDec[14] ."'";
			$valCDec = ReadSerie_1($link, $strSQLM);
			if(strlen($valCDec) == 4){$valCDec = '0'. $valCDec;}
			$sCuer[10] = 'mun="'. $valCDec .'" ';
			//Telefono
			$sErrorX = "Teléfono del Declarante";
			if($sDec[11] == ''){ //Celular
				$sCuer[11] = 'telde="'. $sDec[12] .'" ';
			} else {
				$sCuer[11] = 'telde="'. $sDec[11] .'" ';
			}
			//Actividad Económica
			$sErrorX = "Consulta Actividad Económica Declarante";
			$sAct = rtrim($sDec[16]);
			$strSQLA = "SELECT Codigo_Actividad FROM XConf_Actividades WHERE Actividad LIKE '%". $sAct ."%'";
			$valADec = ReadSerie_1($link, $strSQLA);
			if($valADec == ''){$valADec = '0010';}
			$sCuer[12] = 'actde="'. $valADec .'" ';
		}
		//---------------------------------------------------------
		// Información del Beneficiario
		// Valida info de beneficiario
		$sErrorX = "Validando información del beneficiario ". $s[13];
       	$bDoBen = 'true';
        $strSQLCBen = "SELECT * FROM Clientes WHERE Identificacion = '". $s[13] ."'";
		$cdb=mysqli_query($link, $strSQLCBen) or die(mysqli_error($link));
		$concdb=mysqli_num_rows($cdb);
		if($concdb != 1)
		{
			usleep(5000);
			RegError($iCont, $sFact, $sErrorX);
            $bDoBen = 'false';
            $bErrorX = 'true';
		}
		if($bDoBen == 'true'){
			//Tipo documento
			$sErrorX = "Tipo Documento Beneficiario";
			$strSQLTB = "SELECT Codigo_DIAN FROM XConf_TiposDoc WHERE Tipo_Documento = '". $s[14] ."'";
			$sCuer[13] = 'tdocb="'. ReadSerie_1($link, $strSQLTB) .'" ';
			//Numero de documento y digito DV
			$sErrorX = "Consulta Número Documento Beneficiario";
			$strSQLDB = "SELECT Documento FROM Clientes WHERE Identificacion = '". $s[13] ."'";
			$sCuer[14] = 'nidb="'. ReadSerie_1($link, $strSQLDB) .'" ';
			$sErrorX = "Consulta Digito Verificación Beneficiario";
			$sDVB = '';
			$strSQLDV = "SELECT DV FROM Clientes WHERE Identificacion = '". $s[13] ."'";
			$sDVB = ReadSerie_1($link, $strSQLDV);
			if($sDVB != ''){$sCuer[15] = 'dvb="'. $sDVB .'" ';}
			//Nombres y apellidos
			$sErrorX = "Apellidos y Nombres del Beneficiario";
            if($s[14] == "NIT"){  //Razon social
                $sCuer[20] = 'razb="'. sNames($s[19]) .'" ';
            } else {
                //Apellido 1
                if($s[17] != ''){$sCuer[16] = 'apl1b="'. sNames($s[17]) .'" ';}
                //Apellido 2
                if($s[18] != ''){$sCuer[17] = 'apl2b="'. sNames($s[18]) .'" ';}
                //Nombre 1
                if($s[15] != ''){$sCuer[18] = 'nom1b="'. sNames($s[15]) .'" ';}
                //Nombre 2
                if($s[16] != ''){$sCuer[19] = 'nom2b="'. sNames($s[16]) .'" ';}
            }
            //Direccion
            $sErrorX = "Dirección del Beneficiario";
            $sCuer[21] = 'dirb="'. rtrim($s[22]) .'" ';
            //Municipio
            $sErrorX = "Consulta Código Municipio Beneficiario";
            $strSQLMB = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad = '". $s[24] ."' AND Departamento = '". $s[23] ."'";
			$valCBen = ReadSerie_1($link, $strSQLMB);
			if(strlen($valCBen) == 4){$valCBen = '0'. $valCBen;}
            $sCuer[22] = 'munb="'. $valCBen .'" ';
            //Telefono
            $sErrorX = "Consulta Teléfono del Beneficiario";
            $sTelB = '';
            $sCelB = '';
            $strSQLLB = "SELECT Telefono FROM Clientes WHERE Identificacion = '". $s[13] ."'";
            $strSQLCB = "SELECT Celular FROM Clientes WHERE Identificacion = '". $s[13] ."'";
            $sTelB = ReadSerie_1($link, $strSQLLB);
            $sCelB = ReadSerie_1($link, $strSQLCB);
            if($sTelB != "0" && $sTelB != ''){
                $sCuer[23] = 'telb="'. $sTelB .'" ';
            } else {
                $sCuer[23] = 'telb="'. $sCelB .'" ';
            }
            //Actividad Económica
            $sErrorX = "Consulta Actividad Económica Beneficiario";
            $sActB = rtrim($s[25]);
            $strSQLAB = "SELECT Codigo_Actividad FROM XConf_Actividades WHERE Actividad LIKE '%". $sActB ."%'";
			$valABen = ReadSerie_1($link, $strSQLAB);
			if($valABen == ''){$valABen = '0010';}
			$sCuer[24] = 'actb="'. $valABen .'" ';
		}
		//----------------------------------------------------------------------
		//Datos de la operacion
        $sErrorX = "Información de la Operación";
        //Moneda
        
        //cod de rodrigo
       /* if($s[33] == "TUSD"){
        	$sCuer[25] = 'cmon="USD" ';
        } else {
            $sCuer[25] = 'cmon="'. $s[33] .'" ';
        }
        */ 
        // codigo juan c incluyo EUR5 
       
        if ($s[33] == "TUSD") 
        {
            $sCuer[25] = 'cmon="USD" ';
            } elseif ($s[33] == "EUR5") {
              $sCuer[25] = 'cmon="EUR" ';
            } elseif ($s[33] == "USD2") {
             $sCuer[25] = 'cmon="USD" ';
            } else {
            $sCuer[25] = 'cmon="'. $s[33] .'" ';
        }
        
        
        
        //Cantidad
        $sCuer[26] = 'monto="'. round($s[36], 2) .'" ';
        //Precio venta sin iva
        $sCuer[27] = 'tasav="'. round($s[35], 4) .'" ';
        //Valor en pesos
        $sCuer[28] = 'valpe="'. round($s[37], 2) .'" ';
        //Promedio compra
        $dProm = $s[34] - $s[39];
        $sCuer[29] = 'prom="'. round($dProm, 4) .'" ';
        //Margen e ingreso
        if($s[39] < 0){
        	$sCuer[30] = 'difta="0" ';
            $sCuer[31] = 'basliq="0" ';
		} else {
            $sCuer[30] = 'difta="'. round($s[39], 4) .'" ';
            $sCuer[31] = 'basliq="'. round($s[40], 4) .'" ';
		}
        //Valor IVA
        $sCuer[32] = 'valiva="'. round($s[41], 0) .'" ';
        //Rete IVA
        $sCuer[33] = 'retiva="'. round($s[44], 0) .'" ';
        //Rete Fuente
        $sCuer[34] = 'retfte="'. round($s[42], 0) .'" ';
        //Rete ICA
        $sCuer[35] = 'retica="'. round($s[43], 0) .'" ';
        //4 x 1000
        $sCuer[36] = 'grav="'. round($s[45], 0) .'" ';
        //Valor Neto
        $sCuer[37] = 'valne="'. round($s[46], 2) .'" ';
		//-------------------------------------------------------------------------
        //Forma de Pago
		$sErrorX = "Forma de Pago de la Operación";
		if($s[48] == "EFECTIVO"){
			$sCuer[38] = 'fpag="1" ';
		} else {
			$sCuer[38] = 'fpag="2" ';
			//Consulta codigo Banco
			$sErrorX = "Consulta Código de Banco";
			$strSQLBA = "SELECT Codigo_Banco FROM XConf_Bancos WHERE Nombre_Banco = '". $s[50] ."'";
			$sCuer[39] = 'codba="'. ReadSerie_1($link, $strSQLBA) .'" ';
			$sCuer[40] = 'nunch="'. $s[52] .'" ';
		}
		$sErrorX = "Últimos dos campos del registro";
		$sCuer[41] = 'numdec="'. $s[11]. $s[10] .'" ';
		$sCuer[42] = 'fecdec="'. $s[6] .'"';
		//----------------------------------------------------------------------
        //Escribe registro en archivo
		usleep(5000);
        LoadMsg("Escribiendo registro ". $iCont ." de ". $iTot);
        $sErrorX = "Escritura de registro ". $iCont;
		for ($i = 0; $i <= 42; $i++) {
			$sReg = $sReg. $sCuer[$i];
		}
		fwrite($fXml1, $sReg ."/>\r\n"); 
        //----------------------------------------------------------------------
        //Suma unidad a registro y pasa a siguiente
        $iCont++;
	}
	//---------------------------------------------------------	
	//Cierra archivo y base de datos
	sleep(1);
    LoadMsg("Terminando creación de archivo...");
	fwrite($fXml1, "</mas>"); 
	fclose($fXml1);
	//--------------------------------------------------------------------------------
    // Mensaje terminación de archivo
	sleep(1);
	if($bErrorX == 'false'){
		//LoadMsg("Archivo XML Creado exitosamente!");
		sleep(2);
		LoadFin('finsin', "El archivo XML de Venta de Divisas, con consecutivo 000". $sserie ." se ha creado exitosamente con el nombre ". $sNomAr[5] .".xml. Se recomienda enviar este reporte directamente y no intentar abrirlo o modificarlo.");
		sleep(2);
		echo $sNomAr[5] .".xml";
	} else {
		//LoadMsg("Errores en Archivo XML!");
		sleep(2);
		LoadFin('finerror', "La información del reporte contiene errores. En la tabla de la parte inferior se listan las operaciones con errores las cuales deben ser corregidas.");
		sleep(2);
		echo $sNomAr[5] .".xml";
	}
}
?>
