// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frRepValTras.PHP
//=============================================================
//Procedimiento para tamaño tabla reporte
function TabSize()
{
	var dfifr = window.parent.document.getElementById('frMain');
	var sfrh = dfifr.style.height;
	var tota = document.getElementById('dTot_Tab');
	var toth = (parseInt(sfrh.replace("px", "")) - 175) + "px";
	tota.style.height = toth;
}
//-----------------------------------------------
//Funcion para generar reporte
function cmAccept_Clic()
{
	//Valida campos obligatorios
	if(fEmpty(3, 1) == true) {return 0;}
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
	//Limpia listados
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dbloc');
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		var cbTip = document.getElementById('tx1');
		var cbSuc = document.getElementById('tx2');
		var cbCur = document.getElementById('tx3');
		var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
		var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
		var sSucS = '';
		if(cbSuc.value != 'TODAS'){sSucS = " AND Sucursal ='" + cbSuc.value + "'";}
		var sCurS = '';
		if(cbCur.value != 'TODAS'){sCurS = " AND Moneda ='" + cbCur.value + "'";}
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frRepValTras.php?sFun=GenValTra" + "&stSuc=" + sSucS + "&sSucN=" + cbSuc.value + "&sType=" + cbTip.value + "&sIni=" + sIni + "&sFin=" + sFin + "&sCur=" + sCurS + "&sCurN=" + cbCur.value;
		//------------------------------------------------------------------------
		hidcap('dbloc');
		hidcap('dWait');
		enabtn('btexport');
	}, 1000);
}
//-------------------------------------------------------
//Funcion para exportar reporte
function cmExport_Clic(sCompa)
{
	var frTot = document.getElementById('frTot_Tab');
	var htmlTot = window.open(frTot.src + "&sExp=Exportar", "_blank");
}
