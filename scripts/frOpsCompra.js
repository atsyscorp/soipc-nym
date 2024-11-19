// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsCompra.PHP
//=============================================================
//Funcion para load carga fecha
function frOpsCompra_Load(sDec)
{
	document.getElementById("tx7").value = hoyday();
	document.getElementById("tx8").value = hoyhour();
	//------------------------------------------------
	//Año y mes
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	var fyeamon = year + "_" + antcero(month);
	document.getElementById("tx9").value = year;
	document.getElementById("tx10").value = fyeamon;
	document.getElementById("tx34").focus();
	//--------------------------------------------
	//Si declarante no es vacia habilita Ver Cliente
	if(sDec != '')
	{
		enabtn('btclient');
	}
}
//-----------------------------------------
//Timer para cambio de hora
function CalcTime()
{
	document.getElementById("tx8").value = hoyhour();
}
var ComTime; //--> Variable para el timeout 
comsecs = 1000;
function UpTime()
{
	CalcTime();
	//Repite proceso
	ComTime = setTimeout(function() { UpTime() }, comsecs);
}
//--------------------------------------------
//Funcion cambio de cantidad y precios
function txCanP_Change(stx)
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	//Iguala las tasas a excepeccion del numeral 1600
	var txNum = document.getElementById('tx33');
	var txSin = document.getElementById('tx35');
	var txCon = document.getElementById('tx36');
	if(txNum.value != '1600')
	{
		if(stx == 'tx35')
		{
			txCon.value = txSin.value;
			txChange_Num('tx36');
		} else {
			txSin.value = txCon.value;
			txChange_Num('tx35');
		}
	}
}
//-------------------------------------
//Funcion para limpiar cero en txcantidad
function txCant_Enter()
{
	var txCan = document.getElementById('tx37');
	if(txCan.value == '0')
	{
		ValueCtr('tx37', ''); //Llama funcion de general		
	}
}
//--------------------------------------
//Cambio de selección de moneda reinicia valores de precio a o
function cbCurr_Change(ssuc, scaja)
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	//---------------------------------------------
	var txCur = document.getElementById('tx34');
	if(txCur.value == '')
	{
		ValueCtr('tx35', '0');
		ValueCtr('tx36', '0');
	} else {
		viscap('dWait');
		//Consulta precio de moneda selccionada
		var strSQL = "Select Precio_Compra From Tasas Where Moneda='" + txCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
		document.getElementById('tx35').value = NumFormProp(GenConretField('General', 'Gen_Find_Field', strSQL, false));
		document.getElementById('tx36').value = document.getElementById('tx35').value;
		hidcap('dWait');
	}
}
//---------------------------------------------
//Funcion activación chequeo para cambio de precios
function ChPrice(chP, txP, ssuc, scaja)
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	//------------------------------------------------
	var chPr = document.getElementById(chP);
	var txPr = document.getElementById(txP);
	var cbCur = document.getElementById('tx34');
	if(chPr.checked == true) {
		//Cambia propiedades de controles
		txPr.disabled = false;
		txPr.className = 'txboxo';
	} else {
		//Cambia propiedades de control
		txPr.disabled = true;
		txPr.className = 'txboxdis';
		//Consulta precios
		if(cbCur.value != '')
		{
			viscap('dWait');
			//Consulta precio de moneda selccionada
			var strSQL = "Select Precio_Compra From Tasas Where Moneda='" + cbCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
			document.getElementById('tx35').value = NumFormProp(GenConretField('General', 'Gen_Find_Field', strSQL, false));
			document.getElementById('tx36').value = document.getElementById('tx35').value;
			hidcap('dWait');
		} else {
			txPr.value = '0';		
		}
	}
}
//-----------------------------------------
//Cambio de seleccion tasa de retencion
function cbOORteTax_Change()
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	//------------------------------------------------
    //Habilita combo base si es diferente de cero
	var cbRte = document.getElementById('cbOORteTax');
	var cbBas = document.getElementById('cbOORteBase');
	if(cbRte.value != '0')
	{
		cbBas.disabled = false;
		cbBas.className = 'txboxo';
	} else {
		cbBas.disabled = true;
		cbBas.className = 'txboxdis';
		cbBas.value = 'NINGUNO';
	}
}
//-----------------------------------------
//Cambio de seleccion Medio de Pago
function cbMedPay_Change()
{
	var cbMedPay = document.getElementById('tx49');
	if(cbMedPay.value != '')
	{
		viscap('dWait');
		//Limpia controles	
		InnerCtr('tx50', '<option value=""></option>');
		InnerCtr('tx51', '<option value=""></option>');
		InnerCtr('tx52', '<option value=""></option>');
		document.getElementById('tx53').value = '';
		//Carga opciones de medio de pago
		var cbIns = document.getElementById("tx50");
		var strSQL = "SELECT Instrumento FROM XConf_MediosPago WHERE Medio_Pago = '" + cbMedPay.value + "'";
		cbIns.innerHTML = cbIns.innerHTML + GenConretField('ajax/frOpsCompra', 'UpDate_MedPay', strSQL, false);
		if(cbMedPay.value == 'EFECTIVO')
		{
			document.getElementById("tx51").disabled = true;
			document.getElementById("tx51").className = 'txboxdis';
			document.getElementById("tx52").disabled = true;
			document.getElementById("tx52").className = 'txboxdis';
			document.getElementById("tx53").disabled = true;
			document.getElementById("tx53").className = 'txboxdis';
		} else {
			document.getElementById("tx51").disabled = false;
			document.getElementById("tx51").className = 'txboxo';
			document.getElementById("tx52").disabled = false;
			document.getElementById("tx52").className = 'txboxo';
			document.getElementById("tx53").disabled = false;
			document.getElementById("tx53").className = 'txboxo';
			//Carga opciones de medio de pago
			var cbBak = document.getElementById("tx51");
			var strSQLB = "SELECT DISTINCT Banco FROM Cuentas_Bancarias";
			cbBak.innerHTML = cbBak.innerHTML + GenConretField('ajax/frOpsCompra', 'UpDate_MedPay', strSQLB, false);
		}
		hidcap('dWait');
	}
}
//--------------------------------------------
//Funcion cambio de selección banco
function cbBank_Change()
{
	var cbBank = document.getElementById('tx51');
	//Limpia controles cuentas
	InnerCtr('tx52', '<option value=""></option>');
	if(cbBank.value != '')
	{
		viscap('dWait');
		var cbCon = document.getElementById("tx52");
		var strSQL = "SELECT Numero_Cuenta FROM Cuentas_Bancarias WHERE Banco = '" + cbBank.value + "'";
		cbCon.innerHTML = cbCon.innerHTML + GenConretField('ajax/frOpsCompra', 'UpDate_MedPay', strSQL, false);
		hidcap('dWait');
	}
}
//--------------------------------------
//Funcion para regresar a cliente
function Go_Client(sSuc, sCaja, sUser, sDec, sBen)
{
	var dfifr = window.parent.document.getElementById('frMain');
	dfifr.src = 'frOpsClientes.php?var1=Dec&var2=1&var3=' + sSuc + '&var4=' + sCaja + '&var5=' + sUser + '&var6=' + sDec + '&var7=' + sBen;
}
//-----------------------------------------------
//Funcion para calcular operacion
function cmCOOCalc_Click()
{
	//Validaciones
	//Seleccion de moneda
	if(document.getElementById("tx34").value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione una moneda que se encuentre dentro de las opciones del control.", 1);
		return false;
	}
	//Valida precio sin iva
	if(document.getElementById("tx35").value == '' || document.getElementById("tx35").value == '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El precio sin IVA no es válido. Verifique la información.", 1);
		return false;
	}
	//Valida precio con IVa
	if(document.getElementById("tx36").value == '' || document.getElementById("tx36").value == '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El precio con IVA no es válido. Verifique la información.", 1);
		return false;
	}
	//Valida precio con iva >= precio sin iva
	if(document.getElementById("tx35").value > document.getElementById("tx36").value){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El precio de compra sin IVA no puede ser mayor al precio con IVA.", 1);
		return false;
	}
	//Valida cantidad diferente de cero
	if(document.getElementById("tx37").value == '' || document.getElementById("tx37").value == '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La cantidad de divisa no es válida. Verifique la información.", 1);
		return false;
	}
	//Valida tasa de retención y base de retención
	if(document.getElementById("cbOORteTax").value != '0' && document.getElementById("cbOORteBase").value == 'NINGUNO'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si la tasa de retención es diferente de cero, la base debe ser diferente de NINGUNO.", 1);
		return false;
	}
	//-----------------------------------------
	//Hace cálculo de valor de operación en USD
	if(document.getElementById("tx34").value != 'USD')
	{
		viscap('dWait');
		var ssuc = document.getElementById("tx4").value;
		var scaja = document.getElementById("tx5").value;
		var strSQL = "Select Precio_Compra From Tasas Where Moneda='USD' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
		var sPrUsd = GenConretField('General', 'Gen_Find_Field', strSQL, false);
		if(sPrUsd != '0')
		{
	        var sPrIva = DelMilsepa(document.getElementById("tx36").value);
			var sCant = DelMilsepa(document.getElementById("tx37").value);
			document.getElementById("tx39").value = Math.round((parseFloat(sPrIva) / parseFloat(sPrUsd)) * parseFloat(sCant));
			txChange_Num("tx39");
		} else {
			document.getElementById("tx39").value = document.getElementById("tx37").value;
		}
		hidcap('dWait');
	} else {
		document.getElementById("tx39").value = document.getElementById("tx37").value;
	}
	//-----------------------------------------
	//Calculos
    //Subtotal
	var sPrSin = DelMilsepa(document.getElementById("tx35").value);
	var sPrIva = DelMilsepa(document.getElementById("tx36").value);
	var sCant = DelMilsepa(document.getElementById("tx37").value);
	document.getElementById("tx38").value = NumFormProp(Math.round(parseFloat(sPrSin) * parseFloat(sCant)));
    //Iva descontable
	document.getElementById("tx42").value = NumFormProp(((parseFloat(sPrIva) - parseFloat(sPrSin)) * parseFloat(sCant)).toFixed(2));
    //Valor retención
	if(document.getElementById("cbOORteBase").value == 'MARGEN')
	{
		var dMargen = parseFloat(DelMilsepa(document.getElementById("tx42").value)) / 0.16;
		document.getElementById("tx43").value = NumFormProp(((dMargen * parseFloat(document.getElementById("cbOORteTax").value))/100).toFixed(2));
	} else if(document.getElementById("cbOORteBase").value == 'VALOR DE DIVISAS') {
		document.getElementById("tx43").value = NumFormProp((parseFloat(DelMilsepa(document.getElementById("tx38").value)) * parseFloat(document.getElementById("cbOORteTax").value) / 100).toFixed(2));
	} else {
		document.getElementById("tx43").value = '0';
	}
	//Total a pagar
	document.getElementById("tx47").value = NumFormProp((parseFloat(DelMilsepa(document.getElementById("tx38").value)) - parseFloat(DelMilsepa(document.getElementById("tx43").value)) + parseFloat(DelMilsepa(document.getElementById("tx42").value))).toFixed(2));
	//Valor descontado
	document.getElementById("tx48").value = (parseFloat(DelMilsepa(document.getElementById("tx38").value)) - parseFloat(DelMilsepa(document.getElementById("tx43").value))).toFixed(2);
	//----------------------------------------------------------
	//Si entra desde cliente activa aceptar
	if(document.getElementById("tx20").value != '')
	{
		enabtn('btaccept');		
	}
}
//--------------------------------------------------------
//funcion aceptar operacion
function cmAccept_Click(dTope)
{
	//Validaciones
	//Fecha de cierre
	var dnow = new Date(document.getElementById('tx7').value);
	var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	if(dnow <= dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha de la operación no puede ser menor o igual a la fecha del último cierre.", 1);
		return false;	
	}
	//Que haya entrado desde cliente
	if(document.getElementById("tx20").value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Debe ingresar a Compra de Divisas desde Conocimiento del Cliente, para poder realizar operaciones.", 1);
		return false;	
	}
	//Campos vacíos
	if(fEmpty(56, 1) == true) {return 0;}
	//Tope pago en efectivo
	var dVUsd = parseFloat(DelMilsepa(document.getElementById("tx39").value));
	if((dVUsd > parseFloat(dTope)) && document.getElementById("tx49").value == 'EFECTIVO')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El monto de la operación es superior al tope permitido para pago en efectivo. Realice la operación en cheque.", 1);
		return false;	
	}
	//Medio de pago bancos --> Que haya seleccionado las opciones de banco
	var sMedPay = document.getElementById("tx49").value;
	var sIns = document.getElementById("tx50").value;
	var sBank = document.getElementById("tx51").value;
	var sCoun = document.getElementById("tx52").value;
	var sChec = document.getElementById("tx53").value;
	if(sMedPay == 'BANCOS' && (sIns == '' || sBank == '' || sCoun == '' || sChec == ''))
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el medio de pago es BANCOS, debe indicar el instrumento, nombre del banco, cuenta y número de cheque.", 1);
		return false;	
	}
	//-------------------------------------------------------------------------
	viscap('dbloc');
	//Consulta acumulados de cliente
	var dADia = 0;
	var dAMes = 0;
	var dAAno = 0;
	var dA7 = 0;
	//Fechas
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	//Fecha actual
	var hoyfec = hoyday();
	var hoymes = year + "-" + antcero(month) + "-01";	
	var hoyano = year + "-01-01";	
	//fecha ultimos 7 dias para la DDCI insertada juan camilo 08/03/2023

    today.setDate(today.getDate() - 6); // Restar 6 días para obtener los últimos 7 días
    var fechaInicio = today.getFullYear() + "-" + antcero(today.getMonth() + 1) + "-" + antcero(today.getDate());
    var fechaFin = hoyfec;
	//Consulta
	//Ultimos 7 dias para la DDCI insertada juan camilo 08/03/2023
    var strSQL7 = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value + "' AND Fecha >= '" + fechaInicio + "' AND Fecha <= '" + fechaFin + "' AND Codigo_Operacion = '140' AND Estado_Operacion = 'ACTIVO'";
    var cA7 = GenConretField('General', 'Gen_Find_Field', strSQL7, false);
    if(cA7 != ''){dA7 = cA7;}
	//Mismo Dia
	var strSQLD = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha = '" + hoyfec + "' AND Codigo_Operacion = '140' AND Estado_Operacion = 'ACTIVO'";
	var cADia = GenConretField('General', 'Gen_Find_Field', strSQLD, false);
	if(cADia != ''){dADia = cADia;}
	//Mismo Mes
	var strSQLM = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha >= '" + hoymes + "' AND Fecha <= '" + hoyfec + "' AND Codigo_Operacion = '140' AND Estado_Operacion = 'ACTIVO'";
	var cAMes = GenConretField('General', 'Gen_Find_Field', strSQLM, false);
	if(cAMes != ''){dAMes = cAMes;}
	//Mismo Año
	var strSQLA = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha >= '" + hoyano + "' AND Fecha <= '" + hoyfec + "' AND Codigo_Operacion = '140' AND Estado_Operacion = 'ACTIVO'";
	var cAAno = GenConretField('General', 'Gen_Find_Field', strSQLA, false);
	if(cAAno != ''){dAAno = cAAno;}
	//Validación de acumulados
	//Consutla tope diario y mensual
	//Diario
	var dTopD = 0;
	var cTopD = GenConretField('General', 'Gen_Find_Field', "SELECT Tope_Diario FROM Parametros_Segmentacion WHERE Operacion ='COMPRA DE DIVISAS' AND Segmento ='" + document.getElementById("tx28").value + "'", false);
	if(cTopD != ''){dTopD = cTopD;}
	//Mensual
	var dTopM = 0;
	var cTopM = GenConretField('General', 'Gen_Find_Field', "SELECT Tope_Mensual FROM Parametros_Segmentacion WHERE Operacion ='COMPRA DE DIVISAS' AND Segmento ='" + document.getElementById("tx28").value + "'", false);
	if(cTopM != ''){dTopM = cTopM;}
	//Construye string de acumulados
	var sAcum = "Los acumulados del cliente " + document.getElementById("tx20").value + " en compra de divisas son:<br />Acumulado diario: " + 	NumFormProp(dADia) + "dólares<br />Acumulado <u><b>ultimos 7 dias</b></u> para la <u><b>DDCI</b></u>: " + NumFormProp(dA7) + " dólares<br />Acumulado mensual: " + NumFormProp(dAMes) + " dólares<br />Acumulado anual: " + NumFormProp(dAAno) + " dólares";
	
	//Dependiendo de acumulado para o conntinua
	var dAcumD = parseFloat(dADia) + parseFloat(DelMilsepa(document.getElementById("tx39").value));
	var dAcumM = parseFloat(dAMes) + parseFloat(DelMilsepa(document.getElementById("tx39").value));
	if(dAcumD >= dTopD || dAcumM >= dTopM)
	{
		sAcum = sAcum + "<p></p>Con la operación actual, el cliente sobrepasa los topes permitidos. No puede continuar con la operación.";
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", sAcum, 1);
		return false;	
	} else {
		sAcum = sAcum + "<br />¿Desea continuar con la operación de compra de divisas?";
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); cmAccept_Click1();", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); hidcap('dbloc')", sAcum, 1);
	}
}
//---------------------------------------------------
//Continuacion de aceptar operacion despues de validar acumulados
function cmAccept_Click1()
{
	//Pregunta si desea calificar la operación como inusual
	//Si no, pone cero en control, en caso contrario, hace consulta de calificación y la pone en control
	//Conslta calificacion
	var dCalC = GenConretField('General', 'Gen_Find_Field', "Select Calificacion_Ventanilla From Calificacion_Alerta Where Operacion ='COMPRA DE DIVISAS'", false);
	//Muestra mensaje calificacion
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "document.getElementById('tx55').value = " + dCalC + "; hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); cmAccept_Click2();", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); cmAccept_Click2()", "¿Desea calificar la operación como Inusual?", 1);
}
//--------------------------------------------
//Continua funcion aceptar
function cmAccept_Click2()
{
	//Actualiza la fecha de ultima operacion de beneficiario y declarante
	//Beneficiario
	var sUpB = GenUpdateField('General', 'Gen_Update_Field', 'Clientes', "Identificacion='" + document.getElementById('tx14').value + "'", "Fecha_Operacion='" + document.getElementById('tx7').value + "'", false);
	//Declarante
	if(document.getElementById('tx14').value != document.getElementById('tx31').value)
	{
	 var sUpD = GenUpdateField('General', 'Gen_Update_Field', 'Clientes', "Identificacion='" + document.getElementById('tx31').value + "'", "Fecha_Operacion='" + document.getElementById('tx7').value + "'", false);	
	}
	//------------------------------------------------------
	//Procedimiento calificacion sipla
	document.getElementById('tx54').value = SiplaOps('COMPRA DE DIVISAS', document.getElementById('tx28').value, document.getElementById('tx14').value, DelMilsepa(document.getElementById('tx39').value), hoyday());
	//Calificacion total
	document.getElementById('tx56').value = parseFloat(document.getElementById('tx54').value) + parseFloat(document.getElementById('tx55').value);	
	//--------------------------------------
	//Consulta consecutivo
	//var sConsN = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '140'", false);
	//Suma unidad a consecutivo y trae el actual
	var sConsM = GenConretField('General', 'AddSerieNew', '140' + document.getElementById('tx4').value, false);
	document.getElementById('tx11').value = sConsM;
	//--------------------------------------
	//Construccion de varibles pendientes
	//Id de operacion
    document.getElementById('tx1').value = dateid() + "C" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx11').value;
	//Año
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	document.getElementById('tx9').value = year;
	//Mes_año
	document.getElementById('tx10').value = year + "_" + antcero(month);
	//--------------------------------------
	//Ingresa operacion
	var isuc = genaccept('General', 'Gen_Accept', 56, 'Operacion_Ventanilla');
	//--------------------------------------
	if(isuc == 10)
	{
		//Actualiza saldos y tablero
		//Tablero
		MainTable(document.getElementById('tx3').value, document.getElementById('tx11').value, document.getElementById('tx20').value, document.getElementById('tx34').value, document.getElementById('tx36').value, document.getElementById('tx37').value, document.getElementById('tx49').value)
		//-----------------------------------------------------------
		//Actualiza saldo en arqueo rapido	
		ActCaja(4, 5, 34, 'Compras', 37);
		ActCaja(4, 5, 'Curr', 'Salidas', 47);
		//---------------------------------------------------
		//Registro para envio correo 
		var IdFactura = document.getElementById('tx1').value;
		var IdDeclarante = document.getElementById('tx31').value;
		var swhere = IdFactura+','+IdDeclarante;
		var AddCorreo  = GenConret_1('General','AddCorreo','Correos',swhere,false);
		//---------------------------------------------------
		//Activa botón imprimir y hace pregunta al usuario. En caso afirmativo, ejecuta procedimiento
        enabtn('btprint');
		//----------------------------------------------------------------
		//Mensaje imprimir
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Com_Print('1')", "Com_Print('0')", "La operación de compra " + document.getElementById('tx11').value + " se ha registrado exitosamente. ¿Desea imprimir el Recibo de Compra?", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
}
//----------------------------------------------------
//Funcion continuacion de aceptar
function Com_Print(iPrint)
{
	//-------------------------------------------------
	//Oculta mensaje y manda impresion
	hidcap('dMsj1');
	hidcap('btcancel1');
	if(iPrint == 1)
	{
        //disbtn('btprint');
		var iCPrint = GenConretField('General', 'Gen_Find_Field', "Select Copias_Compra From Configuracion_Contable Where Sucursal ='" + document.getElementById('tx4').value + "'", false);
		GenPrint("RECIBO", document.getElementById('tx1').value, parseInt(iCPrint));	
	}
	//------------------------------------------------
	//Vuelve a traer consecutivo 
	document.getElementById('tx11').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '140'", false);
	//----------------------------------------------------------------------
	disbtn('btaccept');
	//----------------------------------------------------------------------
	//Validacion calificacion operacion como inusual
	if(document.getElementById('tx55').value != '0')
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "GoToObs(); hidcap('dbloc')", "hidcap('dMsj1'); hidcap('btcancel1'); hidcap('dbloc')", "Ha calificado la operación como Inusual. ¿Desea abrir la ventana para realizar el comentario?", 1);
	} else {
		hidcap('dbloc');
	}
}
//-------------------------------------------------
//Funcion para ir a opservacion de operacion
function GoToObs()
{
	var dfifr = window.parent.document.getElementById('frMain');
	//----------------------------------------------------------
	//Cambia la direccion del frame
	dfifr.src = 'frOpsCliComent.php?var1=' + document.getElementById('tx1').value;
}
//-------------------------------------
//Clic en boton para imprimir
function cmPrint_Click()
{
	//var iCPrint = GenConretField('General', 'Gen_Find_Field', "Select Copias_Compra From Configuracion_Contable Where Sucursal ='" + document.getElementById('tx4').value + "'", false);
	GenPrint("RECIBO", document.getElementById('tx1').value, 1);	
	//disbtn('btprint');
}