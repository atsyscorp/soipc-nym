// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frRepDIAN.PHP
//=============================================================
//Funcion Poner fecha y hora de envío
function CalcTime()
{
	document.getElementById("tx5").value = hoyday() + 'T' + hoyhour();
}
var ComTime; //--> Variable para el timeout 
comsecs = 1000;
function UpTime()
{
	CalcTime();
	//Repite proceso
	ComTime = setTimeout(function() { UpTime() }, comsecs);
}
//---------------------------------------
//Funcion cambio de trimestre
function CallTrim(smi, smf, sdi, sdf)
{
	document.getElementById("cbMonth").value = smi;
	document.getElementById("cbMonth1").value = smf;
	//LLama funcion para carga de dias
	getmdays()
	getmdays_1()
	document.getElementById("cbDay").value = sdi;
	document.getElementById("cbDay1").value = sdf;
}
function cbTrim_Change()
{
	var cbtrim = document.getElementById("tx6");
	var today = new Date();
	var year = today.getFullYear();
	//-----------------------------------------
	//Pone año
	document.getElementById("cbYear").value = year;
	document.getElementById("cbYear1").value = year;
	getmdays()
	getmdays_1()
	//-----------------------------------------
	if(cbtrim.value == 'Enero - Marzo'){
		CallTrim('01','03','01','31')
	} else if(cbtrim.value == 'Abril - Junio'){
		CallTrim('04','06','01','30')
	} else if(cbtrim.value == 'Julio - Septiembre'){
		CallTrim('07','09','01','30')
	} else if(cbtrim.value == 'Octubre - Diciembre'){
		CallTrim('10','12','01','31')
	}
}
//-------------------------------------------
//Funcion clic en crear archivo
function Crea_Clic()
{
	//Valida campos obligatorios
	if(fEmpty(6, 1) == true) {return 0;}
	//Valida fechas
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
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha inicial del reporte debe ser menor a la fecha final.", 1);
		return false;	
	}
	//------------------------------------------------
	//Valida cantidad de registros
	viscap('dbloc');
	viscap('dWait');
	var cbType = document.getElementById('tx3')
    var swhere = "Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "' AND Tipo_Operacion = '" + cbType.value + "' AND Estado_Operacion = 'ACTIVO'";
	var iTot = GenConret_1('General', 'RegCount', 'Operacion_Ventanilla', swhere, false);
	hidcap('dWait');
	if(iTot >= 5000)
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El rango de fecha contiene más de 5,000 operaciones (" + iTot + "). Intente fraccionar el archivo en rangos de fecha mas cortos.", 1);
	} else {
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); Crea_Clic_1();", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "El archivo XML será creado con " + iTot + " operaciones. ¿Desea continuar con la generación del archivo?", 1);
	}	
}
//----------------------------------
//Continuacion aceptar
function Crea_Clic_1()
{
	var cbType = document.getElementById("tx3");
	if(cbType.value == 'COMPRA DE DIVISAS')
	{
		Gen_File('XmlComp');	//Llama funcion con argumento de funcion php
	} else {
		Gen_File('XmlVen');
	}
}
//----------------------------------------
//Funcion para generar archivo
function Gen_File(sType)
{
	//Muestra capa de progreso
	viscap('dWait');
	viscap('dProg');
	//----------------------------------------------------------
	//Variables
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	var cbYear1 = document.getElementById('cbYear1');
	var cbMonth1 = document.getElementById('cbMonth1');
	var cbDay1 = document.getElementById('cbDay1');
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
	var cbAno = document.getElementById('tx1');
	var cbCons = document.getElementById('tx2');
	var cbType = document.getElementById('tx3');
	var txSerie = document.getElementById('tx4');
	var txCrea = document.getElementById('tx5');
	var txTrim = document.getElementById('tx6');
	var swhere = "Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "' AND Tipo_Operacion = '" + cbType.value + "' AND Estado_Operacion = 'ACTIVO' ORDER BY Identificacion";
	if(cbCons.value == 'NUEVO REPORTE')
	{
		var scons = '01';
	} else {
		var scons = '02';
	}
	var dprog = document.getElementById('dPrMsg');
	var dtab = document.getElementById('tabError');
	var iMsgC = 0;	//Contador de vector de mensajes
	//----------------------------------------------------------
	//Limpia tabla de errores	
	var strerror = document.getElementById('trError').innerHTML;
	dtab.innerHTML = '<tr class="bgcol_6 fwhite" id="trError">' + strerror + '</tr>';
	//----------------------------------------------------------
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/frRepDIAN.php?sFun=" + sType + "&swhere=" + swhere + "&ano=" + cbAno.value + "&cons=" + scons + "&serie=" + txSerie.value + "&crea=" + txCrea.value + "&trim=" + txTrim.value + "&anotr=" + cbYear.value, true);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				txms = request.responseText;
				hidcap('dWait');
				var restot = txms.split(".-.");
				document.getElementById('aDown').href = 'reportes/DIAN/' + restot[parseInt(restot.length) - 1];
				document.getElementById('aShow').href = 'reportes/DIAN/' + restot[parseInt(restot.length) - 1];
				//------------------------------------------
				//Captura mensaje nuevamente
				var resvec = restot[parseInt(restot.length) - 2].split(".|.");	//Coge vector actual de mensaje
				if(resvec[0] == 'finerror') {
					hidcap('dProg');
					dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", resvec[1], 1);
				} else if(resvec[0] == 'finsin') {
					hidcap('dProg');
					dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); viscap('dFinal');", "", resvec[1], 1);
				}				
			} else if(request.readyState > 2 ){
				var txms = '';
				txms = request.responseText;
				//Valida el tipo de mensaje que llega
				var restot = txms.split(".-.");	//Abre vector de mensajes
				var resvec = restot[iMsgC].split(".|.");	//Coge vector actual de mensaje
				iMsgC++;
				if(resvec[0] == 'prog')
				{
					dprog.innerHTML = resvec[1];
				} else if(resvec[0] == 'error') {
					dtab.innerHTML = dtab.innerHTML + resvec[1];	
//				} else if(resvec[0] == 'finerror') {
//					hidcap('dProg');
//					dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", resvec[1], 1);
//				} else if(resvec[0] == 'finsin') {
//					hidcap('dProg');
//					dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); viscap('dFinal');", "", resvec[1], 1);
				}				
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;
}
//--------------------------------------
//Funcion para exportar tabla de errores
function Export_Err(sCompa)
{
	var cbYear = document.getElementById('cbYear');
	viscap('dWait');
	ExportToHtml('Errores XML', sCompa, 'Errores XML ' + document.getElementById('tx3').value + '<br />Trimestre: ' + document.getElementById('tx6').value + ' de ' + cbYear.value, 'dError');
	hidcap('dWait');
}
