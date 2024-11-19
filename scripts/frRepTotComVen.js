// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA FRREPTOTCOMVEN.PHP
//=============================================================
//Carga de ventana
function frRep_Load(sOrigen, sSuc, sCaja)
{
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
		if(sOrigen != 'PRI') 
		{
			//Pone caja y deshabilita
			cbest.value = sCaja;
			cbest.disabled = true;
			cbest.className = 'txboxdis';
			RepTit();							
		}
	}
}
//------------------------------------------
//Funcion cambio de sucursal
function cbSuc_Change()
{
	//Limpia combo caja
	InnerCtr('tx2', '<option value=""></option><option value="TODAS">TODAS</option>');
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
	//-------------------------------
	//Llama procedimiento titulo de reporte
	RepTit();
}
//-----------------------------------
//Funcion para poner en título de reporte sucursal y caja
function RepTit()
{
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	InnerCtr('dSuc', cbSuc.value + " - " + cbEst.value);
	disbtn('btexport');
}
//-----------------------------------------------
//Funcion para generar reporte
function cmAccept_Clic()
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
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
	var dini = new Date(sIni);
	var dfin = new Date(sFin);
	if(dfin < dini){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha inicial del reporte debe ser menor o igual a la fecha final.", 1);
		return false;	
	}
	//-------------------------------------------
	//Limpia listado
	var sCabe = document.getElementById('trTCList').innerHTML
	InnerCtr('tbTCList', '<tr class="bgcol_6 fwhite" id="trTCList">' + sCabe + '</tr>');	
	//-------------------------------------------
	//Inicio de reporte
	viscap('dbloc');
	viscap('dWait');
	//Define criterios de consulta
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	var sSucS = '';
	if(cbSuc.value != 'TODAS'){sSucS = " AND Sucursal ='" + cbSuc.value + "'";}
	var sCajS = '';
	if(cbEst.value != 'TODAS'){sCajS = " AND Estacion ='" + cbEst.value + "'";}
	var strSUC = sSucS + sCajS
	var strFEC = "Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "'";
	//---------------------------------------------------
	var lstRep = document.getElementById('tbTCList');
	var sTbIni = lstRep.innerHTML; 
	lstRep.innerHTML = sTbIni +  GenConret_1('ajax/frRepTotComVen', 'RepTot', strSUC, strFEC, false);
	hidcap('dbloc');
	hidcap('dWait');
	//------------------------------------
	//Activa boton exportar
	enabtn('btexport')
}
//--------------------------------------
//Funcion para exportar tabla 
function cmExport_Click(sCompa)
{
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	var cbYear1 = document.getElementById('cbYear1');
	var cbMonth1 = document.getElementById('cbMonth1');
	var cbDay1 = document.getElementById('cbDay1');
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
	viscap('dWait');
	ExportToHtml('Totales compra y venta', sCompa, 'Totales Compra y Venta<br />Desde: ' + sIni + ' Hasta: ' + sFin, 'dRepExpo');
	hidcap('dWait');
}
