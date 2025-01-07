// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsVenta.PHP
//=============================================================
//Funcion para load carga fecha
function frOpsVenta_Load(sDec, sDoCon)
{
	document.getElementById("tx7").value = hoyday();
	document.getElementById("tx8").value = hoyhour();
	//------------------------------------------------
	// Año y mes
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
	//-------------------------------------------
	//Validación de resolucion facturación
	if(sDoCon != '')
	{
		UpTit(); //--> Llama funcion titileo
	}
}
//-----------------------------------------
//Relog para titileo alerta de consecutivo
icontit = 1;
iconseg = 1500;
var TitTime;
function TitFact()
{
	//Aparece o desaparece
	menusol("dMsj2");
}
function UpTit()
{
	icontit = icontit + 1;
	TitFact();
	//Repite proceso
	if(icontit <=15){
		TitTime = setTimeout(function() { UpTit() }, iconseg);
	} else {
		hidcap("dMsj2");
	}
}
//----------------------------------------------------
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
//--------------------------------------------
//Cambio de selección de moneda reinicia valores de precio a 0
function cbCurr_Change(ssuc, scaja)
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	//Limpia controles
	ValueCtr('txOVPType', '');
	ValueCtr('lbOVBaseIva', '0');
	ValueCtr('tx40', '0');
	document.getElementById('chOVPsinIva').checked = false;
	document.getElementById('chOVPconIva').checked = false;
	document.getElementById('chOVPsinIva').disabled = true;
	document.getElementById('chOVPconIva').disabled = true;
	document.getElementById('tx35').disabled = true;
	document.getElementById('tx35').className = 'txboxdis';
	document.getElementById('tx36').disabled = true;
	document.getElementById('tx36').className = 'txboxdis';
	//---------------------------------------------
	var txCur = document.getElementById('tx34');
	if(txCur.value == '')
	{
		ValueCtr('tx35', '0');
		ValueCtr('tx36', '0');
	} else {
		viscap('dWait');
		//Consulta precios y tipo de iva
		var strSQL = "Select Precio_Venta From Tasas Where Moneda='" + txCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
		var sPV = GenConretField('General', 'Gen_Find_Field', strSQL, false);
		//la siguiente linea de strSQLB es la origina de rodrigo, donde toma el precio promedio de compra pero siempre de la 01 en la linea siguiente el 29 07 2023 juan camilo hace que tome es el de la respectiva estacion
		var strSQLB = "Select Precio_Base From Tasas Where Moneda='" + txCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
	//se borra la mia el 23012024 por que afectaba cuando uno compra otras monedas por x estacion y las quiere luego vender en otra estacion, se concluyo que para dar solucion provisional es crear una estacion 4	var strSQLB = "Select Precio_Base From Tasas Where Moneda='" + txCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion = '" + scaja + "'";
		var sPB = GenConretField('General', 'Gen_Find_Field', strSQLB, false);
		var strSQLT = "Select Tipo_Venta From Tasas Where Moneda='" + txCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
		var sTV = GenConretField('General', 'Gen_Find_Field', strSQLT, false);
		//Consulta precio de moneda selccionada
		document.getElementById('txOVPType').value = sTV;
		document.getElementById('lbOVBaseIva').value = NumFormProp(sPB);
		ValueCtr('tx35', '0');
		ValueCtr('tx36', '0');
		if(sTV == 'SIN IVA')
		{
			ValueCtr('tx35', NumFormProp(sPV));
			document.getElementById('chOVPsinIva').disabled = false;
			document.getElementById('chOVPconIva').disabled = true;
			//Llama funcion cambio precios
			//txPrecios_Changed();			
		} else {
			ValueCtr('tx36', NumFormProp(sPV));
			document.getElementById('chOVPsinIva').disabled = true;
			document.getElementById('chOVPconIva').disabled = false;
			//Llama funcion cambio precios
			//txPrecios_Changed();
		}
		//---------------------------------------------------------
		//Calculo promedio de compra
		var dDCEntra = 0;
        var dDVEntra = 0;
		var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
        //Consulta en cierre
        var strSQLCC0 = "SELECT SUM(Cantidad_Saldo_Cierre) FROM Cierres_Ventanilla WHERE Sucursal = '" + ssuc + "' AND Fecha = '" + dcls + "' AND Moneda = '" + txCur.value + "'";
		var vCS0 = GenConretField('General', 'Gen_Find_Field', strSQLCC0, false);
        var strSQLCC1 = "SELECT SUM(Valor_Saldo_Cierre) FROM Cierres_Ventanilla WHERE Sucursal = '" + ssuc + "' AND Fecha = '" + dcls + "' AND Moneda = '" + txCur.value + "'"
		var vCS1 = GenConretField('General', 'Gen_Find_Field', strSQLCC1, false);
        //Consulta de cantidad ventas para ver con qué promedio se calcula el margen. Si el total
        //de ventas es menor a la cantidad de cierre del dia anterior, el promedio es el de ayer, en
        //caso contrario el promedio es con las entradas de hoy
		var dfechav = document.getElementById('tx7');
        var strSQLVV0 = "SELECT SUM(Cantidad) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha = '" + dfechav.value + "' AND Moneda = '" + txCur.value + "' AND Estado_Operacion = 'ACTIVO' AND Sucursal = '" + ssuc + "'";
		var vVV0 = GenConretField('General', 'Gen_Find_Field', strSQLVV0, false);
        var strSQLVV1 = "SELECT SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha = '" + dfechav.value + "' AND Moneda = '" + txCur.value + "' AND Estado_Operacion = 'ACTIVO' AND Sucursal = '" + ssuc + "'";
		var vVV1 = GenConretField('General', 'Gen_Find_Field', strSQLVV1, false);
		if(isNaN(vVV0) == false && isNaN(vCS0) == false && vVV0 < vCS0){
			if(vCS0 != 0){
				var calbcierre = (parseFloat(vCS1) / parseFloat(vCS0)).toFixed(2);
				document.getElementById('lbOVBaseIva').value = NumFormProp(calbcierre);
			} else {
				document.getElementById('lbOVBaseIva').value = NumFormProp(sPB);
			}
		} else {
			//Consulta entradas del mismo día para calculo de base iva
			//Compras
			var strSQLDC0 = "SELECT SUM(Cantidad) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha = '" + dfechav.value + "' AND Moneda = '" + txCur.value + "' AND Estado_Operacion = 'ACTIVO' AND Sucursal = '" + ssuc + "'";
			var vCE0 = GenConretField('General', 'Gen_Find_Field', strSQLDC0, false);
			var strSQLDC1 = "SELECT SUM(Valor) FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha = '" + dfechav.value + "' AND Moneda = '" + txCur.value + "' AND Estado_Operacion = 'ACTIVO' AND Sucursal = '" + ssuc + "'";
			var vCE1 = GenConretField('General', 'Gen_Find_Field', strSQLDC1, false);
            dDCEntra = parseFloat(dDCEntra) + parseFloat(vCE0);
            dDVEntra = parseFloat(dDVEntra) + parseFloat(vCE1);
			//Traslados Entradas
			var strSQLTE0 = "SELECT SUM(Cantidad) FROM Traslados_Ventanilla WHERE Codigo_Operacion='142' AND Fecha='" + dfechav.value + "' AND Moneda='" + txCur.value + "' AND Estado='ACTIVO' AND Sucursal='" + ssuc + "' AND Estacion='01' AND Origen_Destino NOT LIKE '%CAJA%'";
			var vDE0 = GenConretField('General', 'Gen_Find_Field', strSQLTE0, false);
			var strSQLTE1 = "SELECT SUM(Valor) FROM Traslados_Ventanilla WHERE Codigo_Operacion='142' AND Fecha='" + dfechav.value + "' AND Moneda='" + txCur.value + "' AND Estado='ACTIVO' AND Sucursal='" + ssuc + "' AND Estacion='01' AND Origen_Destino NOT LIKE '%CAJA%'";
			var vDE1 = GenConretField('General', 'Gen_Find_Field', strSQLTE1, false);
            dDCEntra = parseFloat(dDCEntra) + parseFloat(vDE0);
            dDVEntra = parseFloat(dDVEntra) + parseFloat(vDE1);
			//-----------------------------------
			//Pone precio promedio
			if(isNaN(dDCEntra) == false && dDCEntra != 0){
				var calbcierre1 = (parseFloat(dDVEntra) / parseFloat(dDCEntra)).toFixed(2);
				document.getElementById('lbOVBaseIva').value = NumFormProp(calbcierre1);
			} else {
				document.getElementById('lbOVBaseIva').value = NumFormProp(sPB);
			}
		}
		//----------------------------------------------------------
		//Margen de intermediación
		txPrecios_Changed();
		//----------------------------------------------------------
		//Habilita boton calcular operacion
		enabtn('btcalcop');
		//----------------------------------------------------------
		hidcap('dWait');
	}
}
//---------------------------------------------
//Funcion cambio de texto en precios con y sin iva
function txPrecios_Changed()
{
	//Deshabilita aceptar e imprimir
	disbtn('btaccept');
	disbtn('btprint');
	var txPsin = document.getElementById('tx35');
	var txPcon = document.getElementById('tx36');
	var txPbas = document.getElementById('lbOVBaseIva');
	var txIva = document.getElementById('cbOVTasaIva');
	if(isNaN(DelMilsepa(txPsin.value)) == false && isNaN(DelMilsepa(txPcon.value)) == false || txIva.value != '')
	{
	    //Captura impuesto de iva
        var dTax = parseFloat(txIva.value) / 100;
		if(document.getElementById('txOVPType').value == 'SIN IVA')
		{
			txPcon.value = NumFormProp((parseFloat(DelMilsepa(txPsin.value)) + (dTax * (parseFloat(DelMilsepa(txPsin.value)) - parseFloat(DelMilsepa(txPbas.value))))).toFixed(4));
		} else {	
			txPsin.value = 	NumFormProp(((1 / (1 + dTax)) * (parseFloat(DelMilsepa(txPcon.value)) + (dTax * parseFloat(DelMilsepa(txPbas.value))  ))).toFixed(4));				
		}
        //Hace calculo de intermediación
        document.getElementById('tx40').value = NumFormProp((parseFloat(DelMilsepa(txPsin.value)) - parseFloat(DelMilsepa(txPbas.value))).toFixed(2));
		//Si margen negativo resalta en rojo
		if(parseFloat(DelMilsepa(document.getElementById('tx40').value)) < 0)
		{ 
			document.getElementById('tx40').className = 'txlabel falert';
		} else {
			document.getElementById('tx40').className = 'txlabel fgreen';
		}
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
			var strSQL = "Select Precio_Venta From Tasas Where Moneda='" + cbCur.value + "' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
			txPr.value = NumFormProp(GenConretField('General', 'Gen_Find_Field', strSQL, false));
			txPrecios_Changed();			
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
	var cbRte = document.getElementById('cbOVRteTax');
	var cbBas = document.getElementById('cbOVRteBase');
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
/*
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
	//Valida cantidad diferente de cero
	if(document.getElementById("tx37").value == '' || document.getElementById("tx37").value == '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La cantidad de divisa no es válida. Verifique la información.", 1);
		return false;
	}
	//Valida tasa de retención y base de retención
	if(document.getElementById("cbOVRteTax").value != '0' && document.getElementById("cbOVRteBase").value == 'NINGUNO'){
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
		var strSQL = "Select Precio_Venta From Tasas Where Moneda='USD' AND Sucursal='" + ssuc + "' AND Estacion ='01'";
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
	var sPrSin = DelMilsepa(document.getElementById("tx35").value);
	var sPrIva = DelMilsepa(document.getElementById("tx36").value);
	var sCant = DelMilsepa(document.getElementById("tx37").value);
	var sMarg = DelMilsepa(document.getElementById("tx40").value);
	//Subtotal
	document.getElementById("tx38").value = NumFormProp(Math.round(parseFloat(sPrSin) * parseFloat(sCant)));
	//Ingreso
	document.getElementById("tx41").value = NumFormProp(Math.round(parseFloat(sMarg) * parseFloat(sCant)));
	var sIng = DelMilsepa(document.getElementById("tx41").value);
	//Valor IVA
    if(parseFloat(sMarg) <= 0)
	{
		document.getElementById("tx42").value = '0';
	} else {
		document.getElementById("tx42").value = NumFormProp((parseFloat(sIng) * (document.getElementById("cbOVTasaIva").value / 100)).toFixed(2));
	}
    //Valor retención
	if(document.getElementById("cbOVRteBase").value == 'MARGEN')
	{
		document.getElementById("tx43").value = NumFormProp(((parseFloat(sIng) * parseFloat(document.getElementById("cbOVRteTax").value))/100).toFixed(2));
	} else if(document.getElementById("cbOVRteBase").value == 'VALOR DE DIVISAS') {
		document.getElementById("tx43").value = NumFormProp((parseFloat(DelMilsepa(document.getElementById("tx38").value)) * parseFloat(document.getElementById("cbOVRteTax").value) / 100).toFixed(2));
	} else {
		document.getElementById("tx43").value = '0';
	}
	//Rte ICa
	document.getElementById("tx44").value = NumFormProp((parseFloat(sIng) * (document.getElementById("cbOVRteIca").value / 100)).toFixed(2));
	//Rte IVA
	var sIva = DelMilsepa(document.getElementById("tx42").value);
	document.getElementById("tx45").value = NumFormProp((parseFloat(sIva) * (document.getElementById("cbOVRteIva").value / 100)).toFixed(2));
	//4 x 1000
	document.getElementById("tx46").value = NumFormProp((parseFloat(DelMilsepa(document.getElementById("tx38").value)) * parseFloat(document.getElementById("cbOVGmf").value) / 100).toFixed(2));
	//Neto a recibir
	var sRteFte = DelMilsepa(document.getElementById("tx43").value);
	var sRteIca = DelMilsepa(document.getElementById("tx44").value);
	var sRteIva = DelMilsepa(document.getElementById("tx45").value);
	var sRteGmf = DelMilsepa(document.getElementById("tx46").value);
	document.getElementById("tx47").value = NumFormProp(((parseFloat(sPrIva) * parseFloat(sCant)) - parseFloat(sRteFte) - parseFloat(sRteIca) - parseFloat(sRteIva) - parseFloat(sRteGmf)).toFixed(2));
	//Valor descontado
    document.getElementById("tx48").value = (parseFloat(DelMilsepa(document.getElementById("tx38").value)) - parseFloat(sIng)).toFixed(2);
	//----------------------------------------------------------
	//Si entra desde cliente activa aceptar
	if(document.getElementById("tx20").value != '')
	{
		enabtn('btaccept');		
	}
	

}
*/
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
	if(document.getElementById("cbOVRteTax").value != '0' && document.getElementById("cbOVRteBase").value == 'NINGUNO'){
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
	if(document.getElementById("cbOVRteBase").value == 'MARGEN')
	{
		var dMargen = parseFloat(DelMilsepa(document.getElementById("tx42").value)) / 0.16;
		document.getElementById("tx43").value = NumFormProp(((dMargen * parseFloat(document.getElementById("cbOVRteTax").value))/100).toFixed(2));
	} else if(document.getElementById("cbOVRteBase").value == 'VALOR DE DIVISAS') {
		document.getElementById("tx43").value = NumFormProp((parseFloat(DelMilsepa(document.getElementById("tx38").value)) * parseFloat(document.getElementById("cbOVRteTax").value) / 100).toFixed(2));
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
function cmAccept_Click()
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
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Debe ingresar a Venta de Divisas desde Conocimiento del Cliente, para poder realizar operaciones.", 1);
		return false;	
	}
	//Campos vacíos
	if(fEmpty(56, 1) == true) {return 0;}
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
	//VALIDACION INCLUIDA POR JUAN CAMILO EL 05/04/2023
if(sMedPay == 'BANCOS')
{
    var tx53 = document.getElementById("tx53").value.trim();
    if (tx53.length > 16 || !/^[a-zA-Z0-9]+$/.test(tx53)) {
        viscap('dbloc');
        dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El campo del numero del cheque no cumple con los requisitos (no simbolos, no espacios, no mas de 16 caracteres.", 1);
        return false;
    }
    // Eliminar espacios en blanco al final
    document.getElementById("tx53").value = tx53.replace(/\s+$/, '');
}
	
	
	
	
	
	
	//-------------------------------------------------------------------------
	viscap('dbloc');
	//Consulta acumulados de cliente
	var dADia = 0;
	var dAMes = 0;
	var dAAno = 0;
	//Fechas
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	//Fecha actual
	var hoyfec = hoyday();
	var hoymes = year + "-" + antcero(month) + "-01";	
	var hoyano = year + "-01-01";	
	//Consulta
	//Mismo Dia
	var strSQLD = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha = '" + hoyfec + "' AND Codigo_Operacion = '141' AND Estado_Operacion = 'ACTIVO'";
	var cADia = GenConretField('General', 'Gen_Find_Field', strSQLD, false);
	if(cADia != ''){dADia = cADia;}
	//Mismo Mes
	var strSQLM = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha >= '" + hoymes + "' AND Fecha <= '" + hoyfec + "' AND Codigo_Operacion = '141' AND Estado_Operacion = 'ACTIVO'";
	var cAMes = GenConretField('General', 'Gen_Find_Field', strSQLM, false);
	if(cAMes != ''){dAMes = cAMes;}
	//Mismo Año
	var strSQLA = "SELECT SUM(Valor_En_USD) FROM Operacion_Ventanilla WHERE Documento_Beneficiario = '" + document.getElementById("tx14").value +    "' AND Fecha >= '" + hoyano + "' AND Fecha <= '" + hoyfec + "' AND Codigo_Operacion = '141' AND Estado_Operacion = 'ACTIVO'";
	var cAAno = GenConretField('General', 'Gen_Find_Field', strSQLA, false);
	if(cAAno != ''){dAAno = cAAno;}
	//-------------------------------------
	//Validación de acumulados
	//Consutla tope diario y mensual
	//Diario
	var dTopD = 0;
	var cTopD = GenConretField('General', 'Gen_Find_Field', "SELECT Tope_Diario FROM Parametros_Segmentacion WHERE Operacion ='VENTA DE DIVISAS' AND Segmento ='" + document.getElementById("tx28").value + "'", false);
	if(cTopD != ''){dTopD = cTopD;}
	//Mensual
	var dTopM = 0;
	var cTopM = GenConretField('General', 'Gen_Find_Field', "SELECT Tope_Mensual FROM Parametros_Segmentacion WHERE Operacion ='VENTA DE DIVISAS' AND Segmento ='" + document.getElementById("tx28").value + "'", false);
	if(cTopM != ''){dTopM = cTopM;}
	//Construye string de acumulados
	var sAcum = "Los acumulados del cliente " + document.getElementById("tx20").value + " en venta de divisas son:<br />Acumulado diario: " + 	NumFormProp(dADia) + " dólares<br />Acumulado mensual: " + NumFormProp(dAMes) + " dólares<br />Acumulado anual: " + NumFormProp(dAAno) + " dólares";
	//Dependiendo de acumulado para o conntinua
	var dAcumD = parseFloat(dADia) + parseFloat(DelMilsepa(document.getElementById("tx39").value));
	var dAcumM = parseFloat(dAMes) + parseFloat(DelMilsepa(document.getElementById("tx39").value));
	if(dAcumD >= dTopD || dAcumM >= dTopM)
	{
		sAcum = sAcum + "<p></p>Con la operación actual, el cliente sobrepasa los topes permitidos. No puede continuar con la operación.";
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", sAcum, 1);
		return false;	
	} else {
		sAcum = sAcum + "<br />¿Desea continuar con la operación de venta de divisas?";
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
	var dCalC = GenConretField('General', 'Gen_Find_Field', "Select Calificacion_Ventanilla From Calificacion_Alerta Where Operacion ='VENTA DE DIVISAS'", false);
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
	document.getElementById('tx54').value = SiplaOps('VENTA DE DIVISAS', document.getElementById('tx28').value, document.getElementById('tx14').value, DelMilsepa(document.getElementById('tx39').value), hoyday());
	//Calificacion total
	document.getElementById('tx56').value = parseFloat(document.getElementById('tx54').value) + parseFloat(document.getElementById('tx55').value);	
	//--------------------------------------
	//Consulta consecutivo
	//var sConsN = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '141'", false);
	//Suma unidad a consecutivo
	var sConsM = GenConretField('General', 'AddSerieNew', '141' + document.getElementById('tx4').value, false);
	document.getElementById('tx11').value = sConsM;
	//-----------------------------------------------------------------
	//Construccion de varibles pendientes
	//Id de operacion
    document.getElementById('tx1').value = dateid() + "V" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx11').value;
	// Año
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	document.getElementById('tx9').value = year;
	// Mes_año
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
		ActCaja(4, 5, 34, 'Ventas', 37);
		ActCaja(4, 5, 'Curr', 'Entradas', 47);
		//---------------------------------------------------
		//Registro para envio correo 
		var IdFactura = document.getElementById('tx1').value;
		var IdDeclarante = document.getElementById('tx31').value;
		var swhere = IdFactura+','+IdDeclarante;
		var AddCorreo  = GenConret_1('General','AddCorreo','Correos',swhere,false);
		//---------------------------------------------------
		//Registro para integración API SIIGO
		var swhereFE = IdFactura;
		var SaveInvoiceToSend  = GenConret_1('General','SaveInvoiceToSend','Factura_Electronica',swhereFE,false);
		//---------------------------------------------------
		//Activa botón imprimir y hace pregunta al usuario. En caso afirmativo, ejecuta procedimiento
        enabtn('btprint');
		//----------------------------------------------------------------
		//Mensaje imprimir
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Com_Print('1')", "Com_Print('0')", "La operación de venta " + document.getElementById('tx11').value + " se ha registrado exitosamente. ¿Desea imprimir la Factura de Venta?", 1);
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
		var iCPrint = GenConretField('General', 'Gen_Find_Field', "Select Copias_Venta From Configuracion_Contable Where Sucursal ='" + document.getElementById('tx4').value + "'", false);
		GenPrint("FACTURA", document.getElementById('tx1').value, parseInt(iCPrint));	
	}
	//------------------------------------------------
	//Vuelve a traer consecutivo 
	document.getElementById('tx11').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '141'", false);
	//----------------------------------------------------------------------
	disbtn('btaccept');
	//----------------------------------------------------------------------
	//Muestra ventana de vueltas si medio de pago es efectivo
	if(document.getElementById("tx49").value == 'EFECTIVO')
	{
		ValueCtr('txCANeto', document.getElementById("tx47").value);
		viscap('frOpsCambio');
		document.getElementById("txCACancela").focus();
	} else {
		//Validacion calificacion operacion como inusual
		if(document.getElementById('tx55').value != '0')
		{
			dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "GoToObs(); hidcap('dbloc')", "hidcap('dMsj1'); hidcap('btcancel1'); hidcap('dbloc')", "Ha calificado la operación como Inusual. ¿Desea abrir la ventana para realizar el comentario?", 1);
		} else {
			hidcap('dbloc');
		}
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
	//var iCPrint = GenConretField('General', 'Gen_Find_Field', "Select Copias_Venta From Configuracion_Contable Where Sucursal ='" + document.getElementById('tx4').value + "'", false);
	GenPrint("FACTURA", document.getElementById('tx1').value, 1);	
	//disbtn('btprint');
}
//-------------------------------------
//Funciones ventana cambio
//Función cierre de ventana
function Close_Cam()
{
	//Oculta ventana
	hidcap('frOpsCambio');
	//Si es calificada como inusual muestra mensaje
	if(document.getElementById('tx55').value != '0')
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "GoToObs(); hidcap('dbloc')", "hidcap('dMsj1'); hidcap('btcancel1'); hidcap('dbloc')", "Ha calificado la operación como Inusual. ¿Desea abrir la ventana para realizar el comentario?", 1);
	} else {
		hidcap('dbloc');
	}
}
//Calculo cambio
function Calc_Cambio()
{
	var sNeto = DelMilsepa(document.getElementById("txCANeto").value);
	var sCanc = DelMilsepa(document.getElementById("txCACancela").value);
	if(isNaN(sCanc) == false)
	{
		document.getElementById('txCACambio').value = NumFormProp(parseFloat(sCanc) - parseFloat(sNeto));
	}
}
//Key press de cancelado
function CambioNum(txnum, e) {
	var code = e.keyCode;
	if ((code >= 48 && code <= 57) || (code >= 93 && code <= 105) || (code == 8) || (code == 9) || (code == 37) || (code == 39)) {
		return true;	
	} else if(code == 13) {
		Close_Cam()
	} else {
		return false;	
	}
}

