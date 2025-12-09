// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frRepUIAF.PHP
//=============================================================
//Funcion carga de ventana
function Rep_Load()
{
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	var fdate = antcero(month) + year.toString().substr(2,2);
	document.getElementById("tx1").value = fdate;
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
	var cbtrim = document.getElementById("tx4");
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
	if(fEmpty(4, 1) == true) {return 0;}
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
	//Llama funcion para continuar con archivo
	Crea_Clic_1();
}
//---------------------------------
//Continuacion de archivo
function Crea_Clic_1()
{
	//Muestra capa de progreso
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
	var txDate = document.getElementById('tx1');
	var txSec = document.getElementById('tx2');
	var txCode = document.getElementById('tx3');
	var txTrim = document.getElementById('tx4');
	var swhere = "Fecha >= '" + sIni + "' AND Fecha <= '" + sFin + "' AND Estado_Operacion = 'ACTIVO'";
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
		request.open("GET", "ajax/frRepUIAF.php?sFun=Reporte&swhere=" + swhere + "&sdate=" + txDate.value + "&sect=" + txSec.value + "&code=" + txCode.value + "&trim=" + txTrim.value + "&anotr=" + cbYear.value, true);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				txms = request.responseText;
				hidcap('dWait');
				var restot = txms.split(".-.");
				document.getElementById('aDown').href = 'reportes/UIAF/' + restot[parseInt(restot.length) - 1];
				document.getElementById('aShow').href = 'reportes/UIAF/' + restot[parseInt(restot.length) - 1];
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
	ExportToHtml('Errores UIAF', sCompa, 'Errores UIAF ' + '<br />Trimestre: ' + document.getElementById('tx4').value + ' de ' + cbYear.value, 'dError');
	hidcap('dWait');
}
