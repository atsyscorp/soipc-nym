// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA FRREPCONSOPS.PHP
//=============================================================
//Carga de ventana
function frRepConsOps_Load(sOrigen, sSuc, sCaja)
{
	//Pone día mes año en combos
	var sHoy = hoyday();
	var vHoy = sHoy.split("-"); 	
	document.getElementById('cbYear').value = vHoy[0];	
	document.getElementById('cbMonth').value = vHoy[1];	
	getmdays();
	document.getElementById('cbDay').value = vHoy[2];	
	document.getElementById('cbYear1').value = vHoy[0];	
	document.getElementById('cbMonth1').value = vHoy[1];	
	getmdays_1();
	document.getElementById('cbDay1').value = vHoy[2];	
	//Radio Agrupa
	var sagru = document.getElementsByName('rbAgrupa'); 
	var cbsuc = document.getElementById('tx1');
	var cbest = document.getElementById('tx2');
	if(sOrigen != 'COORDINA') 
	{
		//Pone sucursal en combo y deshabilita
		cbsuc.value = sSuc;
		//Llama procedimiento cambio de sucursal
		cbSuc_Change()		
		cbsuc.disabled = true;
		cbsuc.className = 'txboxdis';				
		//Deshabilita radio total empresa
		sagru[1].disabled = true;		
		//Si es caja O cierre		
		if(sOrigen != 'PRI') 
		{
			//Deshabilita radio por sucursales
			sagru[2].disabled = true;		
			//Pone caja y deshabilita
			cbest.value = sCaja;
			cbest.disabled = true;
			cbest.className = 'txboxdis';				
			if(sOrigen == 'CIERRE') 
			{
				document.getElementById('cbYear').disabled = true;			
				document.getElementById('cbYear').className = 'txboxdis';		
				document.getElementById('cbMonth').disabled = true;			
				document.getElementById('cbMonth').className = 'txboxdis';		
				document.getElementById('cbDay').disabled = true;			
				document.getElementById('cbDay').className = 'txboxdis';		
				document.getElementById('cbYear1').disabled = true;			
				document.getElementById('cbYear1').className = 'txboxdis';		
				document.getElementById('cbMonth1').disabled = true;			
				document.getElementById('cbMonth1').className = 'txboxdis';		
				document.getElementById('cbDay1').disabled = true;			
				document.getElementById('cbDay1').className = 'txboxdis';		
			}						
		}
	}
}
//------------------------------------------
//Funcion cambio de sucursal
function cbSuc_Change()
{
	//Deshabilita cierre de ventanilla
	disbtn('btclose');
	//Limpia combo caja
	InnerCtr('tx2', '<option value=""></option><option value="TODAS">TODAS</option>');
	var sagru = document.getElementsByName('rbAgrupa'); 
	sagru[1].disabled = false;
	sagru[2].disabled = false;
	//---------------------------------------------
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	if(cbSuc.value == "TODAS")
	{
		cbEst.value = 'TODAS';
	} else if(cbSuc.value != '') {
		viscap('dWait');
		//Consulta la cantidad de estaciones de la sucursal seleccionada
		var iCajas = GenConretField("General", "Gen_Find_Field", "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal='" + cbSuc.value + "'", false);
		for(i = 1; i <= parseInt(iCajas); i++){
			var sCero = '0';
			if(i > 9){sCero = '';}
			document.getElementById("tx2").options[i + 1] = new Option(sCero + i, sCero + i);
		}
		hidcap('dWait');
	}
}
//Procedimiento para tamaño de tablas
function TabSize()
{
	var dfifr = window.parent.document.getElementById('frMain');
	var sfrh = dfifr.style.height;
	var tota = document.getElementById('dTot_Tab');
	var comp = document.getElementById('dComp_Tab');
	var vent = document.getElementById('dVent_Tab');
	var tras = document.getElementById('dTras_Tab');
	var pago = document.getElementById('dPago_Tab');
	var genh = (parseInt(sfrh.replace("px", "")) - 195) + "px";
	var toth = (parseInt(sfrh.replace("px", "")) - 380) + "px";
	tota.style.height = toth;
	comp.style.height = genh;
	vent.style.height = genh;
	tras.style.height = genh;
	pago.style.height = genh;
}
//-----------------------------------------------------
//Funcion para cambio de pestañas
function chancls(sBtn)
{
	var btn = document.getElementById(sBtn);
	btn.className = 'bttab';
}
function Tab_Change(sTab, sBtn)
{
	//Cambia la clase de todos los botones a normal
	chancls('bttot');
	chancls('btcom');
	chancls('btven');
	chancls('btaju');
	chancls('btegr');
	//Oculta todos los divs
	hidcap('dTot');
	hidcap('dComp');
	hidcap('dVent');
	hidcap('dTras');
	hidcap('dPago');
	//Visible tab a mostrar
	viscap(sTab);	
	//Cambia clase de boton
	document.getElementById(sBtn).className = 'bttabsel';	
}
//----------------------------------------------
//Cambio de seleccion de caja
function cbCaja_Change()
{
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	//Deshabilita cierre
	disbtn('btclose');
	//Radio Agrupa
	var sagru = document.getElementsByName('rbAgrupa'); 
	//--------------------------------------------------
	//Habilita check dependiendo de selección
	sagru[0].checked = true;
	if(cbEst.value != '')
	{
		if(cbSuc.value == 'TODAS')
		{
			sagru[0].disabled = false;
			sagru[1].disabled = false;
			sagru[2].disabled = false;
		} else if(cbSuc.value != ''){
			if(cbEst.value == 'TODAS')
			{
				sagru[0].disabled = false;
				sagru[1].disabled = true;
				sagru[2].disabled = false;
			} else if(cbEst.value != ''){
				sagru[0].disabled = false;
				sagru[1].disabled = true;
				sagru[2].disabled = true;
			}
		}
	}
}
//-----------------------------------------------
//Funcion para generar reporte
function cmAccept_Clic(sOrigen, sCajero)
{
	//Valida campos obligatorios
	if(fEmpty(2, 1) == true) {return 0;}
	//Valida seleccion de fechas
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	if(cbYear.value == '' || cbMonth.value == '' || cbDay.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha inicial.", 1);
		return false;
	} 
	var cbYear1 = document.getElementById('cbYear1');
	var cbMonth1 = document.getElementById('cbMonth1');
	var cbDay1 = document.getElementById('cbDay1');
	if(cbYear1.value == '' || cbMonth1.value == '' || cbDay1.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha final.", 1);
		return false;
	} 
	//Valida fecha inicial menor a fecha final
	var dini = new Date(cbYear.value + "-" + cbMonth.value + "-" + cbDay.value);
	var dfin = new Date(cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value);
	if(dfin < dini){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha inicial del reporte debe ser menor o igual a la fecha final.", 1);
		return false;	
	}
	//-------------------------------------------
	//Inicio de reporte
	viscap('dbloc');
	viscap('dWait');
	viscap('dProg');
	//-----------------------------------------------
	//ConsProg();	
	setTimeout(function(){
	//Limpia listados
	InnerCtr('dTot_Tab', '');
	InnerCtr('dTotComVen', '');
	InnerCtr('dTotPagTrs', '');
	var frCo = document.getElementById('frComp_Tab');
	var frVe = document.getElementById('frVent_Tab');
	var frTr = document.getElementById('frTras_Tab');
	var frPa = document.getElementById('frPago_Tab');
	frCo.src = '';
	frVe.src = '';
	frTr.src = '';
	frPa.src = '';
	//-------------------------------------------
	//Consulta de listados de acuerdo a criterios
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
	var sSucS = '';
	if(cbSuc.value != 'TODAS'){sSucS = " AND Sucursal ='" + cbSuc.value + "'";}
	var sCajS = '';
	if(cbEst.value != 'TODAS'){sCajS = " AND Estacion ='" + cbEst.value + "'";}
	//Compras
	InnerCtr('dPrMsg', 'Consultando compras...');
	var strSQLC = "SELECT * FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS + " ORDER BY Identificacion";
	setTimeout(function(){
	//InnerCtr('dComp_Tab', GenConret_1('ajax/frRepConsOps', 'LoadRepTable', 12, strSQLC, false));
	frCo.src = 'ajax/frRepConsOpsTab.php?swhere=' + strSQLC + '&stable=12';
	//Ventas
	InnerCtr('dPrMsg', 'Consultando ventas...');
	var strSQLV = "SELECT * FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS + " ORDER BY Identificacion";
	setTimeout(function(){
	//InnerCtr('dVent_Tab', GenConret_1('ajax/frRepConsOps', 'LoadRepTable', 12, strSQLV, false));
	frVe.src = 'ajax/frRepConsOpsTab.php?swhere=' + strSQLV + '&stable=12';
	//Traslados
	InnerCtr('dPrMsg', 'Consultando traslados...');
	var strSQLT = "SELECT * FROM Traslados_Ventanilla WHERE Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS + " ORDER BY Identificacion";
	setTimeout(function(){
	//InnerCtr('dTras_Tab', GenConret_1('ajax/frRepConsOps', 'LoadRepTable', 8, strSQLT, false));
	frTr.src = 'ajax/frRepConsOpsTab.php?swhere=' + strSQLT + '&stable=8';
	//Pagos
	InnerCtr('dPrMsg', 'Consultando pagos...');
	var strSQLP = "SELECT * FROM Egresos_Ventanilla WHERE Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS + " ORDER BY Identificacion";
	setTimeout(function(){
	//InnerCtr('dPago_Tab', GenConret_1('ajax/frRepConsOps', 'LoadRepTable', 26, strSQLP, false));
	frPa.src = 'ajax/frRepConsOpsTab.php?swhere=' + strSQLP + '&stable=26';
	//---------------------------------------------------------------------------
    //Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
	InnerCtr('dPrMsg', 'Generando saldos por moneda...');
	setTimeout(function(){
	var sagru = document.getElementsByName('rbAgrupa'); 
	if(cbSuc.value == 'TODAS')
	{
		if(sagru[1].checked == true) {	//Total empresa
			InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, '', '', 'TOTAL', 'TODAS', sIni, sFin, sCajero));
		} else if(sagru[2].checked == true) {	//Por sucursales
			InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, '', '', 'SUCURSAL', 'TODAS', sIni, sFin, sCajero));
		} else if(sagru[0].checked == true) {	//Por estaciones
			InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, '', '', 'CAJA', 'TODAS', sIni, sFin, sCajero));
		}
	} else if(cbSuc.value != '') {
		if(cbEst.value == 'TODAS')
		{
			if(sagru[2].checked == true) {	//Total sucursal
				InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, cbSuc.value, 'TODAS', 'SUCURSAL', cbSuc.value, sIni, sFin, sCajero));
			} else if(sagru[0].checked == true) {	//Por sucursales
				InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, cbSuc.value, 'TODAS', 'CAJA', cbSuc.value, sIni, sFin, sCajero));
			}
		} else if(cbEst.value != '') {
			InnerCtr('dTot_Tab', GenRepCons(sSucS, sCajS, cbSuc.value, cbEst.value, 'CAJA', cbSuc.value, sIni, sFin, sCajero));
		}
	}
	//------------------------------------------------------------------------
	//Hace cálculo de totales
	InnerCtr('dPrMsg', 'Calculando totales de reporte...');
	setTimeout(function(){
	//Operaciones compra y venta
	//Valor total caja
	var sTbCoVe = '<table cellpadding="0" cellspacing="0" style="width:235px; margin-left:2px; margin-top:2px"><tr><td style="width:135px"></td><td style="width:100px"></td></tr>';
	var iR = parseInt(document.getElementById("lstTot_Tab").rows.length) - 1;
	var dTotCajaX = 0;
	for(i = 1; i <= iR; i++){
		var svec = document.getElementById("txo" + i).value;
		var vec = svec.split(".|.");
		dTotCajaX = dTotCajaX + parseFloat(vec[15]);		
	}
	dTotCajaX = Math.round(parseFloat(dTotCajaX));
	sTbCoVe = sTbCoVe + '<tr class="fcont"><td class="celrow">Valor total caja</td><td style="text-align:right" class="celrow">' + NumFormProp(dTotCajaX) + '</td></tr>'; 
	//Venta de divisas
	InnerCtr('dPrMsg', 'Calculando totales de venta...');
	setTimeout(function(){
	sTbCoVe = sTbCoVe + TitTotal('VENTA DE DIVISAS');
	var strSQLVT = "SELECT SUM(Valor) AS Valor_Ventas, SUM(IVA) AS IVA_Generado, SUM(Rete_Fuente) AS Rte_Fuente_Ventas, SUM(Rete_ICA) AS Retencion_ICA, SUM(Rete_IVA) AS Retencion_IVA, SUM(GMF) AS GMF_4x1000, SUM(Ingreso) AS Ingreso_Base_Impuestos FROM Operacion_Ventanilla WHERE Codigo_Operacion = '141' AND Estado_Operacion = 'ACTIVO' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS;
	sTbCoVe = sTbCoVe + GenConretField("ajax/frRepConsOps", "AddTotals", strSQLVT, false);
	//Compra de divisas
	InnerCtr('dPrMsg', 'Calculando totales de compra...');
	setTimeout(function(){
	sTbCoVe = sTbCoVe + TitTotal('COMPRA DE DIVISAS');
	var strSQLCT = "SELECT SUM(Valor) AS Valor_Compras, SUM(IVA) AS IVA_Descontable, SUM(Rete_Fuente) AS Rte_Fuente_Compras FROM Operacion_Ventanilla WHERE Codigo_Operacion = '140' AND Estado_Operacion = 'ACTIVO' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS;
	sTbCoVe = sTbCoVe + GenConretField("ajax/frRepConsOps", "AddTotals", strSQLCT, false);
	sTbCoVe = sTbCoVe + '</table>';
	InnerCtr('dTotComVen', sTbCoVe); 
	//------------------------------------------------------------------------
	//Pagos y traslado
	var sTbPaTr = '<table cellpadding="0" cellspacing="0" style="width:235px; margin-left:2px; margin-top:2px"><tr><td style="width:135px"></td><td style="width:100px"></td></tr>'; 
	//Pagos ventanilla
	InnerCtr('dPrMsg', 'Calculando traslados y pagos...');
	setTimeout(function(){
	sTbPaTr = sTbPaTr + TitTotal('PAGOS VENTANILLA');
	var strSQLPT = "SELECT SUM(Subtotal) AS Valor_Subtotal, SUM(Valor_IVA) AS IVA_Pagado, SUM(Rete_Fuente) AS Retencion_Fuente, SUM(Rete_ICA) AS Retencion_ICA, SUM(Rete_IVA) AS Retencion_IVA, SUM(Total_Pagar) AS Total_Pagado FROM Egresos_Ventanilla WHERE Codigo_Movimiento = '144' AND Estado = 'ACTIVO' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS;
	sTbPaTr = sTbPaTr + GenConretField("ajax/frRepConsOps", "AddTotals", strSQLPT, false);
	//Traslados
	sTbPaTr = sTbPaTr + TitTotal('TRASLADOS RECURSOS');
	//Egresos		
	var strSQLTE = "SELECT SUM(Valor) AS Valor_Egresos FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '143' AND Estado = 'ACTIVO' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS;
	sTbPaTr = sTbPaTr + GenConretField("ajax/frRepConsOps", "AddTotals", strSQLTE, false);
	//Ingresos		
	var strSQLTI = "SELECT SUM(Valor) AS Valor_Ingresos FROM Traslados_Ventanilla WHERE Codigo_Movimiento = '142' AND Estado = 'ACTIVO' AND Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'" + sSucS + sCajS;
	sTbPaTr = sTbPaTr + GenConretField("ajax/frRepConsOps", "AddTotals", strSQLTI, false);
	sTbPaTr = sTbPaTr + '</table>';
	InnerCtr('dTotPagTrs', sTbPaTr); 
	//------------------------------------------------------------------------
	//Habilita botones
	enabtn('btexport');
	if(sOrigen == 'CIERRE'){enabtn('btclose');}	
	//------------------------------------------------------------------------
	hidcap('dbloc');
	hidcap('dWait');
	hidcap('dProg');
	InnerCtr('dPrMsg', 'Iniciando proceso...');
	}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);}, 1000);
}
//---------------------------------------------------------
//Funcion para generar reporte
function GenRepCons(stSuc, stCaj, sSucN, sCajN, sType, SucVal, sIni, sFin, sCajero)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/frRepConsOps.php?sFun=GenRepCons" + "&stSuc=" + stSuc + "&stCaj=" + stCaj + "&sSucN=" + sSucN + "&sCajN=" + sCajN + "&sType=" + sType + "&SucVal=" + SucVal + "&sIni=" + sIni + "&sFin=" + sFin + "&sCajero=" + sCajero, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				//Muestra la cantidad de usuarios conectados
				funval = txms;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;
}
//-------------------------------------------------
//Funcion para agragar titulos a tablas de totales
function TitTotal(sTit)
{
	var funval = '';
	funval = '<tr class="fwhite bgcol_6"><td class="celrow">' + sTit + '</td><td class="celrow"></td></tr>';
	return funval;
}
//-------------------------------------------------------
//Funcion para exportar reporte
function cmExport_Clic(sUser, sCompa, sOrigen, sNit)
{
	var htmlWin = window.open("sFormats/Reports.html", "_blank");
	htmlWin.onload = function(){
		//Define titulo
		htmlWin.document.title = 'Totales reporte';
		//Capa de contenido
		var dcont = htmlWin.document.getElementById('dCont');
		var sRow1 = '<span class="fcont" style="font-size:18px">Reporte de movimiento - ' + sCompa + "-" + sNit + '<br />';
		var sRow2 = '<span class="fcont" style="font-size:16px">Sucursal: ' + document.getElementById('tx1').value + '<br />';
		var sRow3 = '<span class="fcont" style="font-size:16px">Estación: ' + document.getElementById('tx2').value + '<br />';
		var sRow4 = '<span class="fcont" style="font-size:16px">Fecha inicial: ' + document.getElementById('cbYear').value + "-" + document.getElementById('cbMonth').value + "-" + document.getElementById('cbDay').value + '<br />';
		var sRow5 = '<span class="fcont" style="font-size:16px">Fecha final: ' + document.getElementById('cbYear1').value + "-" + document.getElementById('cbMonth1').value + "-" + document.getElementById('cbDay1').value + '<br />';
		var sRow6 = '<span class="fcont" style="font-size:16px">Agrupación por: ' + document.getElementById('rbAgrupa').value + '<p></p>';
		dcont.innerHTML = sRow1 + sRow2 + sRow3 + sRow4 + sRow5 + sRow6 + document.getElementById('dTot_Tab').innerHTML + '<p></p>' + document.getElementById('dTotals1').innerHTML.replace(/height:154px/g, '');
	}
	//---------------------------------------------
	//Exporta tablas de datos
	var frCo = document.getElementById('frComp_Tab');
	var frVe = document.getElementById('frVent_Tab');
	var frTr = document.getElementById('frTras_Tab');
	var frPa = document.getElementById('frPago_Tab');
	var htmlCom = window.open(frCo.src + "&srep=Operaciones compra de divisas", "_blank");
	var htmlVen = window.open(frVe.src + "&srep=Operaciones venta de divisas", "_blank");
	var htmlTra = window.open(frTr.src + "&srep=Traslados de recursos", "_blank");
	var htmlPag = window.open(frPa.src + "&srep=Pagos desde ventanilla", "_blank");
}
//---------------------------------------------------------
//Funcion para cierre de ventanilla
function cmClose_Clic(sUser)
{
	//Valida que capa de totales no sea vacia --> Hay registros en el reporte
	var iR = parseInt(document.getElementById("lstTot_Tab").rows.length) - 1;
	
	
	// CODIGO JUAN CAMILO EL 12/12/23  para utilizar  la PRI y poder hacer cierres de ventanilla
	if (sUser !== "GERENTE") {
        var iR = parseInt(document.getElementById("lstTot_Tab").rows.length) - 1;

        if (iR <= 1) {
            viscap('dbloc');
            dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No se encuentran monedas en el listado de saldos para hacer el cierre de ventanilla.", 1);
            return false;
        }
    }
	
	
	/*
	if(iR <= 1)
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No se encuentran monedas en el listado de saldos para hacer el cierre de ventanilla." + sUser + ".", 1);
		return false;
	}
	*/
	
	//Valida fecha ultimo cierre y cierre actual
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var dnow = new Date(sIni);
	var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	//Consulta si hay registros de cierre con la misma fecha por si hay dos equipos registrados con la misma estación
	var ivalc = GenConret_1('General', 'RegCount', 'Cierres_Ventanilla', "Sucursal='"+document.getElementById('tx1').value+"' AND Estacion='"+document.getElementById('tx2').value+"' AND Fecha='"+sIni+"'", false)
	if(dnow <= dcls || ivalc != '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha actual que desea cerrar es menor o igual a la fecha del último cierre.", 1);
		return false;	
	}
	//------------------------------------------------------
	//Valida saldos negativos --> Recorre vectores y valida
	for(i = 1; i <= iR; i++){
		var svec = document.getElementById("txo" + i).value;
		var vec = svec.split(".|.");
		if(parseFloat(vec[14]) < 0)
		{
			viscap('dbloc');
			dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La moneda " + vec[6] + " tiene saldo negativo y no se puede realizar el cierre.", 1);
			return false;	
		}
	}
	//----------------------------------------------------------------------------
	//Confirmacion de ciere
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); cmClose_Clic_1('" + sUser + "', '" + sIni + "')", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea realizar el cierre de la estación " + document.getElementById("tx2").value + " para la fecha " + sIni + "?", 1);
}
//Continuación de cierre
function cmClose_Clic_1(sUser, sIni)
{
	viscap('dWait');
	//----------------------------------------------------------
	//Validacion de operaciones fuera de cierre
	var dLast = window.parent.document.getElementById('txMCCierre');
	var cbSuc = document.getElementById("tx1");
	var cbEst = document.getElementById("tx2");
	var swhere = "Fecha > '" + dLast.value + "' AND Fecha < '" + sIni + "' AND Sucursal = '" + cbSuc.value + "' AND Estacion = '" + cbEst.value + "'";
	//Compra y venta
	var VClosCV = GenConret_1("General", "RegCount", 'Operacion_Ventanilla', swhere + " AND Estado_Operacion='ACTIVO'", false);
	if(VClosCV > 0){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Se encuentran operaciones de compra o venta de divisas en fechas sin cerrar después del último cierre. Verifique los cierres de ventanilla de días anteriores.", 1);
		hidcap('dWait');
		return false;	
	}
	//Traslados
	var VClosTR = GenConret_1("General", "RegCount", 'Traslados_Ventanilla', swhere + " AND Estado='ACTIVO'", false);
	if(VClosTR > 0){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Se encuentran traslados de ventanilla en fechas sin cerrar después del último cierre. Verifique los cierres de ventanilla de días anteriores.", 1);
		hidcap('dWait');
		return false;	
	}
	//Egresos
	var VClosEG = GenConret_1("General", "RegCount", 'Egresos_Ventanilla', swhere + " AND Estado='ACTIVO'", false);
	if(VClosEG > 0){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Se encuentran pagos desde ventanilla en fechas sin cerrar después del último cierre. Verifique los cierres de ventanilla de días anteriores.", 1);
		hidcap('dWait');
		return false;	
	}
	//-------------------------------------------------------------
	//Recorre listado cierre en y hace registro en base de datos
	var iR = parseInt(document.getElementById("lstTot_Tab").rows.length) - 1;		
	var dLocC = 0; 	//Variable captura pesos en efectivo
	for(i = 1; i <= iR; i++){
		var svec = document.getElementById("txo" + i).value;
		var vec = svec.split(".|.");
		//Registro en base de datos
		var iReg = RegClose(svec);
		//--------------------------------
		//Modifica arqueo
		if(vec[6] != 'COP')
		{
			var swhere = "Sucursal ='" + cbSuc.value + "' AND Estacion ='" + cbEst.value + "' AND Moneda ='" + vec[6] + "'"; 
			var ModArq = GenConret_1("ajax/frRepConsOps", "ModArqueo", DelMilsepa(vec[14]), swhere, false);
		} else {
			if(vec[16] == 'EFECTIVO'){
				//dLocC = dLocC + vec[14]; --> Antes se sumaba efectivo y bancos. En caso de volverlo a hacer poner parseFloat en la suma
				dLocC = DelMilsepa(vec[14]);
			}
		}
		//--------------------------------
		//Modifica promedio tasas
		if(vec[6] != 'COP')
		{
			var swhereT = "Sucursal ='" + cbSuc.value + "' AND Estacion ='" + cbEst.value + "' AND Moneda ='" + vec[6] + "'"; 
			var sUpPr = GenUpdateField('General', 'Gen_Update_Field', 'Tasas', swhereT, "Precio_Base='" + DelMilsepa(vec[8]) + "'", false);
		}
	}
	//------------------------------------------------------------
	//Modifica arqueo de COP
	var swhere1 = "Sucursal ='" + cbSuc.value + "' AND Estacion ='" + cbEst.value + "' AND Moneda ='COP'"; 
	var ModArqC = GenConret_1("ajax/frRepConsOps", "ModArqueo", dLocC, swhere1, false);
	//------------------------------------------------------------
	//Desactiva boton de cierre y cambia fecha ultimo cierre
	disbtn('btclose');
	dLast.value = sIni;
	window.parent.document.getElementById('cbMCCurr').value = '';
	//Cierre exitoso
	hidcap('dWait');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj1'); hidcap('dbloc')", "", "Cierre exitoso de la estación " + cbEst.value + " con fecha " + sIni, 1);
}
//------------------------------------------------
//Funcion para hacer registro de cierre en base de datos
function RegClose(svec1)
{
	//-----------------------------------------------
	var funval = '';
	//Construye string de parametros
	var vec = svec1.split(".|.");
	var sparam = '';
	var j = 1;
	for(l = 0; l <= 18; l++){
		sparam = sparam + "&var" + j + "=" + DelMilsepa(vec[l]); 
		j++;
	}
	sparam = "&var0=" + 21 + sparam + "&var20=&var21";	//Campo 20 y 21 vacios para luego ingresar firma de cajerop
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "General.php?sFun=Gen_Accept" + sparam + "&stable=Cierres_Ventanilla", false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;			
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¿Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
