<?php
//ARCHIVO FUNCIONES FRREPUIAF.PHP
//============================================================
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8');
include("../General.php");
//-----------------------------------------------------------------
//Bloque para llamado de funciones
$sFun = $_GET['sFun'];
switch ($sFun){
	case "Reporte":
		$calFun = Reporte();
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
//Funcion para crear archivo plano
function Reporte()
{
	set_time_limit(0); 
	ob_implicit_flush(true);
	ob_end_flush();	
	//--------------------------------------------
	//Captura varuables
	$swhere =$_GET['swhere'];
	$sdate =$_GET['sdate']; 
	$ssect =$_GET['sect']; 
	$scode =$_GET['code']; 
	$strim =$_GET['trim'];
	$sanotr =$_GET['anotr'];
	//------------------------------------------
	//Variables para cobntrol de errores
	$iTot = 0;
    $sErrorX = '';
    $iCont = 1;
    $sFact = '';
    $bErrorX = 'false';
	//-------------------------------------------
	LoadMsg('Iniciando creación de archivo...');
    $sErrorX = "Creación nombre de archivo";
	$sNomAr[0] = $ssect;
    $sNomAr[1] = str_pad('', 4 - strlen($scode), "0", STR_PAD_LEFT). $scode;
    $sNomAr[2] = $sdate;
    $sNomAr[3] = $sNomAr[0]. $sNomAr[1]. $sNomAr[2];
	sleep(2);
    LoadMsg("Creando archivo en servidor...");
    $sErrorX = "Creación de archivo en ruta";
	$fUiaf = fopen("../reportes/UIAF/". $sNomAr[3] .".txt", "w");
	//-------------------------------------------
	sleep(2);
	LoadMsg("Creando encabezado de archivo...");
	$sErrorX = "Escritura encabezado de archivo";
    $i = 0;
    $sCabR = '';
    $sCabe[0] = str_pad('', 10, "0", STR_PAD_LEFT);
    $sCabe[1] = $sNomAr[0].$sNomAr[1];
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
	$sCabe[2] = $sCabFecIn;
    $sCabe[3] = $sCabFecFi;
	$sCabe[5] = str_pad('', 398, "X", STR_PAD_LEFT);
    //OJO -> cabe(4) y la escritura de encabezado en archivo se hace al final,
    //una vez se sepa cuantos registros contiene el reporte
	//-------------------------------------------------------------
	//Cierra archivo para volverlo a abrir append
	fclose($fUiaf);
	$fUiaf1 = fopen("../reportes/UIAF/". $sNomAr[3] .".txt", "a");
	//-------------------------------------------------------------
	$link=Conectarse();
	//--------------------------------------------------
    //Consulta y escritura de registros
	sleep(2);
    LoadMsg("Consultando operaciones para archivo...");
    $sErrorX = "Consulta de operaciones";
    $strSQL = "SELECT * FROM Operacion_Ventanilla WHERE ". $swhere. " ORDER BY Identificacion";
	$q=mysqli_query($link, $strSQL) or die(mysqli_error($link));
	sleep(2);
    LoadMsg("Iniciando escritura de registros...");
    $sCuerpo = '';   //Contiene todo el cuerpo del archivo
	while($s=mysqli_fetch_array($q)){
		$dAcum = 0;
        $dCant = 0;
		//----------------------------------------------------------------------
		$sErrorX = "Consulta acumulado de Beneficiario";
        $strSQLA = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE ". $swhere ." AND Documento_Beneficiario = '". $s[13] ."'";
		$cd=mysqli_query($link, $strSQLA) or die(mysqli_error($link));
		$sd=mysqli_fetch_array($cd);
        $dAcum = $sd[0];
        $dCant = $s[Valor_En_USD];
		//----------------------------------------------------------------------
        //Valida cantidad y acumulado para generar registro
        if($dCant >= 500 || $dAcum >= 2500){
	        //Escribe registro en archivo
			usleep(5000);
        	LoadMsg("Creando registro ". $iCont);
			$sReg = '';
			for ($i = 0; $i <= 17; $i++) {
				$sCuer[$i] = '';
			}
			//-----------------------------------------------------------------------
	        //Captura numero de factura para error
    	    $sFact = $s[3] ." - ". $s[10];
        	//----------------------------------------------------------------------
            $sErrorX = "Identificación Registro - Consecutivo a Fecha";
            //Consecutivo operacion
		    $sCuer[0] = str_pad('', 10 - strlen($iCont), ' ', STR_PAD_LEFT). $iCont;
            //Sucursal
            $sCuer[1] = str_pad('', 4 - strlen($s[3]), ' ', STR_PAD_LEFT). $s[3];
            //Fecha
            $sCuer[2] = $s[6];
            //--------------------------------------------------------------
            //Datos de la operación
            $sErrorX = "Datos de la operación";
            //Cantidad
            $dCantU = number_format($s[36], 2, $GLdecsepa, '');
            $sCuer[3] = str_pad('', 20 - strlen($dCantU), ' ', STR_PAD_LEFT). $dCantU;
            //Valor
            $dValU = number_format($s[37], 2, $GLdecsepa, '');
            $sCuer[4] = str_pad('', 20 - strlen($dValU), ' ', STR_PAD_LEFT). $dValU;
            //Codigo Moneda
            $strSQLM = "SELECT Codigo FROM XConf_Monedas WHERE Moneda = '". $s[33] ."'";
            $sMonU = ReadSerie_1($link, $strSQLM);
            $sCuer[5] = "0". $sMonU;
            //-----------------------------------------------------------------
            //Datos del Beneficiario
            $sErrorX = "Información del Beneficiario";
            //Tipo de Documento
            $strSQLTB = "SELECT Codigo_UIAF FROM XConf_TiposDoc WHERE Tipo_Documento = '". $s[14] ."'";
            $sCuer[6] = ReadSerie_1($link, $strSQLTB);
            //Tipo Identificacion
            $sCuer[7] = $s[13]. str_pad('', 20 - strlen($s[13]), ' ', STR_PAD_LEFT);
            //Nombre
            $sErrorX = "Apellidos y Nombres del Beneficiario";
            if($s[14] == "NIT"){ //Razon social
				$sCuer[8] = str_pad('', 40, ' ', STR_PAD_LEFT);
				$sCuer[9] = str_pad('', 40, ' ', STR_PAD_LEFT);
				$sCuer[10] = str_pad('', 40, ' ', STR_PAD_LEFT);
				$sCuer[11] = str_pad('', 40, ' ', STR_PAD_LEFT);
				$sCuer[12] = $s[19]. str_pad('', 60 - strlen($s[19]), ' ', STR_PAD_LEFT);
			} else {
            	//Apellido 1
                //if($s[17] != ''){$sCuer[8] = $s[17]. str_pad('', 40 - strlen($s[17]), ' ', STR_PAD_LEFT);}
                $sCuer[8] = $s[17]. str_pad('', 40 - strlen($s[17]), ' ', STR_PAD_LEFT);
                //Apellido 2
                //if($s[18] != ''){$sCuer[9] = $s[18]. str_pad('', 40 - strlen($s[18]), ' ', STR_PAD_LEFT);}
                $sCuer[9] = $s[18]. str_pad('', 40 - strlen($s[18]), ' ', STR_PAD_LEFT);
                //Nombre 1
                //if($s[15] != ''){$sCuer[10] = $s[15]. str_pad('', 40 - strlen($s[15]), ' ', STR_PAD_LEFT);}
                $sCuer[10] = $s[15]. str_pad('', 40 - strlen($s[15]), ' ', STR_PAD_LEFT);
                //Nombre 2
                //if($s[16] != ''){$sCuer[11] = $s[16]. str_pad('', 40 - strlen($s[16]), ' ', STR_PAD_LEFT);}
                $sCuer[11] = $s[16]. str_pad('', 40 - strlen($s[16]), ' ', STR_PAD_LEFT);
               //Razon social
				$sCuer[12] = str_pad('', 60, ' ', STR_PAD_LEFT);
			}
            //------------------------------------------------------------------
            // Dirección
            $sCuer[13] = $s[22]. str_pad('', 60 - strlen($s[22]), ' ', STR_PAD_LEFT);
            // Municipio
            $sErrorX = "Consulta Código Municipio Beneficiario";
            $strSQLMB = "SELECT Codigo FROM XConf_Ciudades WHERE Ciudad = '". $s[24] ."' AND Departamento = '". $s[23] ."'";
			$valCBen = ReadSerie_1($link, $strSQLMB);
			if(strlen($valCBen) == 4){$valCBen = '0'. $valCBen;}
            $sCuer[14] = $valCBen;
            // Teléfono
            $sErrorX = "Consulta Teléfono del Beneficiario ". $s[13];
            $sTelB = '';
            $sCelB = '';
            $sIndB = '';
            $sTelFin = '';
            $strSQLLB = "SELECT Telefono, Celular, Indicativo FROM Clientes WHERE Identificacion = '". $s[13] ."'";
			$cdt=mysqli_query($link, $strSQLLB) or die(mysqli_error($link));
			$concdt=mysqli_num_rows($cdt);
			if($concdt != 1)
			{
				usleep(5000);
				RegError($iCont, $sFact, $sErrorX);
				$bErrorX = 'true';
			} else {
				$sdt=mysqli_fetch_array($cdt);
                $sTelB = $sdt[0];
                $sCelB = $sdt[1];
                $sIndB = $sdt[2];
                if($sTelB != "0" && $sTelB != ''){
                    $sTelFin = $sIndB. $sTelB;
                } else {
                    $sTelFin = $sCelB;
                }
                $sCuer[15] = $sTelFin. str_pad('', 10 - strlen($sTelFin), ' ', STR_PAD_LEFT);
			}
			//---------------------------------------------------------------------------
            //Dos ultimos campos
            $sErrorX = "Dos ultimos campos";
            //Cambia codigos de numeral por codigos uiaf
            if($s[1] == "140"){ //Compra
            	$sCuer[16] = "9001";
            } else {
            	$sCuer[16] = "9002";
            }
            $sCuer[17] = str_pad('', 60, ' ', STR_PAD_LEFT);
			//----------------------------------------------------------------------
            //Completa registro de archivo
			for ($i = 0; $i <= 17; $i++) {
				$sReg = $sReg. $sCuer[$i];
			}
			if($sCuerpo == ''){
				$sCuerpo = $sReg;
            } else {
            	$sCuerpo = $sCuerpo ."\r\n". $sReg;
            }
			//----------------------------------------------------------------------
            //Suma unidad a registro 
            $iCont++;
		}
	}
    //-------------------------------------------------------------------------------
    //Crea encabezado
	sleep(1);
    LoadMsg("Completando encabezado de archivo...");
    $sErrorX = "Completando encabezado";
    $iTotR = $iCont - 1;
    $sCabe[4] = str_pad('', 10 - strlen($iTotR), ' ', STR_PAD_LEFT). $iTotR;
	for ($i = 0; $i <= 5; $i++) {
		$sCabR = $sCabR. $sCabe[$i];
	}
	//-------------------------------------------------------------------------------
    //Cola de archivo
    sleep(1);
	LoadMsg("Creando registro de finalización...");
    $sErrorX = "Creación de cola";
    $sCola[0] = $sCabe[0];
    $sCola[1] = $sCabe[1];
    $sCola[2] = $sCabe[4];
	$sCola[3] = str_pad('', 418, "X", STR_PAD_LEFT);
    $sCola[4] = $sCola[0]. $sCola[1]. $sCola[2]. $sCola[3];
	//-------------------------------------------------------------------------------
    //Escribe registros en archivo
	sleep(1);	
	LoadMsg("Escribiendo archivo...");
	$sErrorX = "Escritura de archivo";
	fwrite($fUiaf1, $sCabR ."\r\n"); 
	fwrite($fUiaf1, $sCuerpo ."\r\n"); 
	fwrite($fUiaf1, $sCola[4]); 
	//-------------------------------------------------------------------------------
	//Cierra archivo y base de datos
	sleep(1);
    LoadMsg("Terminando creación de archivo...");
	fclose($fUiaf1);
	//--------------------------------------------------------------------------------
    // Mensaje terminación de archivo
	sleep(1);
	if($bErrorX == 'false'){
		sleep(2);
		LoadFin('finsin', "El archivo plano de operaciones para la UIAF, se ha creado exitosamente con el nombre ". $sNomAr[3] .".txt. Se recomienda enviar este reporte directamente y no intentar abrirlo o modificarlo.");
		sleep(2);
		echo $sNomAr[3] .".txt";
	} else {
		sleep(2);
		LoadFin('finerror', "La información del reporte contiene errores. En la tabla de la parte inferior se listan las operaciones con errores las cuales deben ser corregidas.");
		sleep(2);
		echo $sNomAr[5] .".xml";
	}
}
?>