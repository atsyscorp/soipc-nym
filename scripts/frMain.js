// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA PRINCIPAL FRMAIN.PHP
//=============================================================
//Variables publicas
//Id de Sesion
sesid = "";
//-------------------------------------------------------------
function IniSesion(iduser, nomuser, ssuc, scaja)
{
	//Función para el registro de Sesion en el load de la ventana principal
	//-------------------------------------------------------------------------
	//Variables de id de Sesion, fecha y hora		
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	var day = today.getDate();
	var hour = today.getHours();
	var mins = today.getMinutes();
	var secs = today.getSeconds();
	//Id de registro
	sesid = year.toString() + month + day + hour + mins + secs + iduser + ssuc + scaja;
	var fdate = year + "-" + month + "-" + day;
	var fhour = hour + ":" + mins + ":" + secs;
	//---------------------------------------------------------------------
	//Hace registro en base de datos llamando ajax
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/frMain.php?sFun=IniSesion&var0=" + sesid +  "&var1=" + iduser + "&var2=" + nomuser + "&var3=" + ssuc + "&var4=" + scaja +  "&var5=" + fdate + "&var6=" + fhour);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
//-----------------------------------------------------------
//Funcion para salir de SOIPC
function exitapp()
{
	window.location.href = 'index.php';	
}
//-----------------------------------------------------------
//Funcion para cerrar Sesion
window.onbeforeunload = function() {
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/frMain.php?sFun=EndSesion&var0=" + sesid, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
//--------------------------------------------------
//Funcion para consulta de alertas de usuario
function UpDateAlert(ssucid, suserid)
{
	//Construye variable swhere
	var swhere = "(Sucursal='" + ssucid + "' OR Sucursal='TODAS') AND (Usuario='" + suserid + "' OR Usuario='TODOS') AND Leido='NO'";
	//Llama procedimiento
	GenConret_0('../General', 'RegCount', 'Alertas_Usuarios', swhere, true, 'dalcant');
}
var AlertTime; //--> Variable para el timeout de consulta de usuarios
ialert = 60000;
idsuca = '';
idusea = '';
function ConsAlert(ssucid, suserid)
{
	idsuca = ssucid;
	idusea = suserid;
	UpDateAlert(idsuca, idusea);
	//Repite proceso
	AlertTime = setTimeout(function() { ConsAlert(idsuca, idusea) }, ialert);
}
//-------------------------------------------------
//Funcion para mostrar alertas de usuario
function ShowAlerts(ssucid, suserid)
{
	//Construye variable swhere
	var swhere = "(Sucursal='" + ssucid + "' OR Sucursal='TODAS') AND (Usuario='" + suserid + "' OR Usuario='TODOS') AND Leido='NO'";
	//Llama procedimiento
	document.getElementById('dMAlerts1').innerHTML = GenConret('frMain', 'ShowAlerts', 'Alertas_Usuarios', swhere, false);
}
//--------------------------------------------------
//Funcion para cambiar estado de alerta a Leido = SI
function ChanAlert(salid)
{
	hidcap('dMAlerts');
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/frMain.php?sFun=ChanAlert&var0=" + salid);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
//----------------------------------------------
//Funciones para ingreso de traslados --> Mensaje de alerta
function UpDateTras(ssucid, scaja)
{
	//Construye variable swhere --> Si es caja uno traslados desde sucursales y desde otra caja
	//Si es otra caja > 01, solo traslados entre cajas
	if(scaja == '01')
	{
		var swhere = "Estado='ACTIVO' AND (Origen_Destino='" + ssucid + "' OR (Origen_Destino='CAJA " + scaja + "' AND Sucursal='" + ssucid + "'))";
	} else {
		var swhere = "Estado='ACTIVO' AND (Origen_Destino='CAJA " + scaja + "' AND Sucursal='" + ssucid + "')";
	}
	//Llama procedimiento
	//document.getElementById('dtracan').innerHTML = GenConret('../General', 'RegCount', 'Traslados_Ventanilla_Temp', swhere, false);
	GenConret_0('../General', 'RegCount', 'Traslados_Ventanilla_Temp', swhere, true, 'dtracan');
}
var TrasTime; //--> Variable para el timeout de consulta de traslados
itras = 30000;
idsucT = '';
icajaT = '';
function ConsTras(ssucid, scaja)
{
	idsucT = ssucid;
	icajaT = scaja;
	UpDateTras(idsucT, icajaT);
	//Repite proceso
	TrasTime = setTimeout(function() { ConsTras(idsucT, icajaT) }, itras);
}
//-------------------------------------------------
//Funcion para mostrar alertas de usuario
function ShowTrasa(ssucid, scaja, suser)
{
	//Construye variable swhere
	if(scaja == '01')
	{
		var swhere = "Estado='ACTIVO' AND (Origen_Destino='" + ssucid + "' OR (Origen_Destino='CAJA " + scaja + "' AND Sucursal='" + ssucid + "'))";
	} else {
		var swhere = "Estado='ACTIVO' AND (Origen_Destino='CAJA " + scaja + "' AND Sucursal='" + ssucid + "')";
	}
	//Llama procedimiento
	document.getElementById('dTrasa1').innerHTML = GenConret('frMain', 'ShowTrasa', ssucid + ".|." + scaja + ".|." + suser, swhere, false);
}
//Funcion para abrir ajuste de recursos desde alerta
function ShowAjuste(svecvar)
{
	hidcap('dTrasa');
	var svec = svecvar.attributes["name"].value;
	//Abre vector de variables
	var vec = svec.split(".|.")
	//Abre ventana de ajuste
	clicmen('frOpsAjustes_1', 'var1=' + vec[0] + '&var2=' + vec[1] + '&var3=' + vec[2] + '&var4=' + vec[3]);	
}
//----------------------------------------------
//Funcion para expandir y contraer menu
function MenuAccess(dcap)
{
	var cap = document.getElementById(dcap);
	if(cap.style.height == '0px')
	{
		cap.style.height = 'auto';
	} else {
		cap.style.height = '0px';
	}
}
//---------------------------------------------
//Muestra capa info tiempo real
function DRealTime()
{
	var dcap = document.getElementById('drealtime');
	if(dcap.style.right == '0px')
	{
		dcap.style.right = '260px';
	} else {
		dcap.style.right = '0px';
	}
}
//=====================================================
//Funciones para chat
//-----------------------------------------------------
//Funcion para calcular la cantidad de registros
var ichat;
ichat = 0;
function CalcChat()
{
	var dhoy = hoyday();
	var icha = 0;
	var swhere = "Fecha='" + dhoy + "'";
	icha = GenConret('../General', 'RegCount', 'Chat', swhere, false);
	if(icha != ichat)
	{
		UpDateChat(ichat, icha - ichat);
		ichat = icha;
	}
}
//----------------------------------------------
//Consulta constante de chat
var ChatTime; //--> Variable para el timeout de consulta de usuarios
ichsecs = 1000;
function ConsChat()
{
	CalcChat();
	//Repite proceso
	ChatTime = setTimeout(function() { ConsChat() }, ichsecs);
}
//-----------------------------------
//Funcion para actualizar el chat
function UpDateChat(iini, iregs)
{
	var dhoy = hoyday();
	var swhere = "Fecha='" + dhoy + "'";
	var dchat = document.getElementById('dChat'); 
//	dchat.innerHTML = dchat.innerHTML + GenConret('frMain', 'ShowChat', iini+ '|' + iregs, swhere, false);
	GenConret_0_1('frMain', 'ShowChat', iini+ '|' + iregs, swhere, true, 'dChat');
	dchat.scrollTop = dchat.scrollHeight;
}
//----------------------------------------------
//Funcion para aceptar registro de chat
function Accept_Chat()
{
	//Crea valores de fecha y hora
	var txid = document.getElementById('tx1');
	var txdate = document.getElementById('tx2');
	var txhour = document.getElementById('tx3');
	txid.value = dateid();
	txdate.value = hoyday();
	txhour.value = hoyhour();
	//-----------------------------------------------
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i <= 6; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		sparam = sparam + "&var" + i + "=" + evtx.value; 
	}
	sparam = "sFun=Gen_Accept&var0=" + 6 + sparam;
	//--------------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "General.php?" + sparam + "&stable=Chat", true);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				if(txms == "10"){
					document.getElementById('tx6').value = '';
				}
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
//--------------------------------------------
//Enter ejecuta aceptar chat
function entchat(txr, e) {
	var code = e.keyCode;
	if (code == 13 && txr.value != '') {
		Accept_Chat();
		return false;	
	}
}
//=====================================================
//Consulta constante de usuarios conectados
var LogsTime; //--> Variable para el timeout de consulta de usuarios
ilogsecs = 60000;
function ConsLogs()
{
	UpDateLogs();
	//Repite proceso
	LogsTime = setTimeout(function() { ConsLogs() }, ilogsecs);
}

//Funcion para actualizar el chat
function UpDateLogs()
{
	//var dlogs = document.getElementById('dLogs'); 
	//dlogs.innerHTML = GenConret('frMain', 'ShowLogs', 'Sesion', '', false, 'dLogs');
	GenConret_0('frMain', 'ShowLogs', 'Sesion', '', false, 'dLogs');
}
//---------------------------------------------
//Funcion consulta de saldos de sucursales
//Consulta constante de saldos
var SaldTime; //--> Variable para el timeout de consulta de usuarios
isalsecs = 60000;
function ConsSals(sSuc)
{
	UpDateSals(sSuc);
	//Repite proceso
	SaldTime = setTimeout(function() { ConsSals(sSuc) }, isalsecs);
}
function UpDateSals(sSuc)
{
	//--------------------------------------
	//Captura el estado actual de los divs de arqueo de cada sucursal para dejarlos como
	//estaban despues de la consulta de saldos
	var isuc = GenConretField('ajax/frMain', 'SucCount', sSuc, false);
	var i = 0;
	var dsals = document.getElementById('dSaldos'); 
	var dsst = [];
	if(dsals.innerHTML != ''){
		for(i = 1; i <= parseInt(isuc) + 1; i++){
			var dsuc = '';
			var dsuc = document.getElementById('Arq' + i);
			dsst[i] = dsuc.style.height;
		}
	}
	//--------------------------------------
	dsals.innerHTML = GenConretField('ajax/frMain', 'ShowSals', sSuc, false);
	//--------------------------------------
	for(i = 1; i <= parseInt(isuc) + 1; i++){
		var dsuc = '';
		var dsuc = document.getElementById('Arq' + i);
		if(dsst[i] != ''){dsuc.style.height = dsst[i];}	
	}
}
//============================================
//Funciones para actualización de tasas en tiempo real --> info rápida
var TasasTime; //--> Variable para el timeout de consulta 
itasasecs = 60000;
function ConsTasasCV(sSuc)
{
	UpDateTasasCV(sSuc);
	//Repite proceso
	TasasTime = setTimeout(function() { ConsTasasCV(sSuc) }, itasasecs);
}
function UpDateTasasCV(sSuc)
{
	//--------------------------------------
	//Captura el estado actual de los divs de arqueo de cada sucursal para dejarlos como
	//estaban despues de la consulta de saldos
	var isuc = GenConretField('ajax/frMain', 'SucCount_1', sSuc, false);
	var i = 0;
	var dsals = document.getElementById('dTasasCV'); 
	var dsst = [];
	if(dsals.innerHTML != ''){
		for(i = 1; i <= parseInt(isuc); i++){
			var dsuc = '';
			var dsuc = document.getElementById('Tsa' + i);
			dsst[i] = dsuc.style.height;
		}
	}
	//--------------------------------------
	dsals.innerHTML = GenConretField('ajax/frMain', 'ShowTasasCV', sSuc, false);
	//--------------------------------------
	for(i = 1; i <= parseInt(isuc); i++){
		var dsuc = '';
		var dsuc = document.getElementById('Tsa' + i);
		if(dsst[i] != ''){dsuc.style.height = dsst[i];}	
	}
}
//------------------------------------
//Funciones para alertas de cambio de tasas
var TasasAlTime; //--> Variable para el timeout de consulta 
itasaalert = 60000;
function ConsTasasAlert(sSuc)
{
	TasasAlert(sSuc);
	//Repite proceso
	TasasAlTime = setTimeout(function() { ConsTasasAlert(sSuc) }, itasaalert);
}
function TasasAlert(sSuc)
{
	var a = document.getElementById('dTasaAlM');
	var b = document.getElementById('dTasaAlC');
	//-------------------------------------------
	//Consulta modificación de tasas en Tasas_Mod
	var isuc = GenConretField('ajax/frMain', 'Tasa_Alert', sSuc, false);
	if(isuc != '100'){
		InnerCtr('tbTasasCH', isuc);
		a.style.backgroundColor = 'rgba(30,30,30,0.85)';
		a.style.visibility = 'visible';
		b.style.marginTop = '40px';
		//-----------------------------------
		//Modifica los estados de alerta a leidos
		var mod = GenConretField('ajax/frMain', 'Tasa_Alert_Mod', sSuc, false);
	}
}
//Función para ocultar alerta de tasas
function HideAlTasa()
{
	var a = document.getElementById('dTasaAlM');
	var b = document.getElementById('dTasaAlC');
	a.style.backgroundColor = 'rgba(255,255,255,0.85)';
	a.style.visibility = 'hidden';
	b.style.marginTop = '-700px';
}
//---------------------------------------------
//Muestra capa info saldos
function DQuickInfo()
{
	var dcap = document.getElementById('dquick');
	dcap.style.width = '100%';
	dcap.style.width = parseInt(window.getComputedStyle(dcap, null)['width'].replace('px', '')) - 210 + 'px';
	if(dcap.style.top == '100%')
	{
		var tpos = parseInt(window.getComputedStyle(dcap, null)['top'].replace('px', ''));
		if(tpos == '100')
		{
			dcap.style.top = parseInt(window.innerHeight) - 250 + 'px';
		} else {
			dcap.style.top = tpos - 250 + 'px';
		}
	} else {
		dcap.style.top = '100%';
	}
}
//----------------------------------------------------
//Funcion para ajustar iframe a tamaño de pagina
function resizeIframe(obj) 
{	//40 , 210
	//var obj = document.getElementById('dfrMain');
	obj.style.width = '100%';
	obj.style.height = '100%';
	obj.style.height = parseInt(window.getComputedStyle(obj, null)["height"].replace("px", "")) - 40 + "px";
	obj.style.width = parseInt(window.getComputedStyle(obj, null)["width"].replace("px", "")) - 210 + "px";
}
//------------------------------------------
//Función para abrir herramientas desde menu
function clicmen(sfile, sargs)
{
	var dfifr = document.getElementById('frMain');
	//----------------------------------------------------------
	//Cambia la direccion del frame
	viscap('dWait');
	dfifr.src = sfile + ".php?" + sargs;
	dfifr.style.width = '100%';
	dfifr.style.height = '100%';
	dfifr.style.height = parseInt(window.getComputedStyle(dfifr, null)["height"].replace("px", "")) - 40 + "px";
	dfifr.style.width = parseInt(window.getComputedStyle(dfifr, null)["width"].replace("px", "")) - 210 + "px";
	hidcap('dWait');
}
//-----------------------------------------------------
//Funcion consulta ultimo cierre
function LoadClose(sSuc, sCaja)
{
	var txClose = document.getElementById('txMCCierre');
	var dCDate = '';
	var strSQL = "SELECT MAX(Fecha) FROM Cierres_Ventanilla WHERE Sucursal = '" + sSuc + "' AND Estacion = '" + sCaja + "'";
	dCDate = GenConretField('General', 'Gen_Find_Field', strSQL, false);
	if(dCDate == '')
	{
		var today = new Date();
		var dalt = new Date();
		dalt.setDate(today.getDate() - 1);
		//---------------------------------------------------------	
		var ayear = dalt.getFullYear();
		var amonth = dalt.getMonth() + 1;
		var aday = dalt.getDate();
		var fdate = ayear + "-" + antcero(amonth) + "-" + antcero(aday);
		txClose.value =	fdate;	
	} else {
		txClose.value = dCDate;
	}
}
//--------------------------------------------------------
//Consulta constante fecha ultimo cierre por que coordinacion
//puede anular los cierres y tiene que verse la fecha en la sucursal
var CloseTime; 
iclose = 10000;
function ConsClose(sSuc, sCaja)
{
	LoadClose(sSuc, sCaja);
	//Repite proceso
	CloseTime = setTimeout(function() { ConsClose(sSuc, sCaja) }, iclose);
}
//--------------------------------------------------------
//Funcion para cargar operaciones en info rapida
function LoadOpsInf(sSuc, sCaja)
{
	var tbOps = document.getElementById('lstMCList');
	//Fecha actual
	var hoyfec = hoyday();
	//Operaciones
	var strSQLOps = "SELECT Tipo_Operacion, Consecutivo, Nombre_Completo, Moneda, Precio_Con_Iva, Cantidad, Medio_Pago FROM Operacion_Ventanilla WHERE Sucursal = '" + sSuc + "' AND Estacion ='" + sCaja + "' AND Fecha = '" + hoyfec + "' AND Estado_Operacion = 'ACTIVO'";
	tbOps.innerHTML = tbOps.innerHTML + GenConretField('ajax/frMain', 'LoadOpsInf', strSQLOps, false);
	//traslados
	var strSQLTrs = "SELECT Tipo_Movimiento, Consecutivo, Origen_Destino, Moneda, Precio_Promedio, Cantidad, Medio_Pago FROM Traslados_Ventanilla WHERE Sucursal = '" + sSuc + "' AND Estacion ='" + sCaja + "' AND Fecha = '" + hoyfec + "' AND Estado = 'ACTIVO'"
	tbOps.innerHTML = tbOps.innerHTML + GenConretField('ajax/frMain', 'LoadOpsInf', strSQLTrs, false);
	//Egresos
	var strSQLEgr = "SELECT Tipo_Movimiento, Consecutivo, Descripcion, Moneda, Precio, Total_Pagar, Medio_Pago FROM Egresos_Ventanilla WHERE Sucursal = '" + sSuc + "' AND Estacion ='" + sCaja + "' AND Fecha = '" + hoyfec + "' AND Estado = 'ACTIVO'";
	tbOps.innerHTML = tbOps.innerHTML + GenConretField('ajax/frMain', 'LoadOpsInf', strSQLEgr, false);
}
//--------------------------------------
//Clic en listado de operaciones --> Vacia por que no ejecuta ninguna funcion
function lstfun(scur){}
//-------------------------------------------------
//Cambio de sleccion moneda en informacion rapida
function cbMCCurr_Change(sSuc, sCaja)
{
	//Limpia los controles de saldos
	for(i = 1; i <= 6; i++){
		document.getElementById('dC' + i).innerHTML = "0";
	}
	//---------------------------------------------
	//Hace consulta de cada valor
	var cbCurr = document.getElementById('cbMCCurr');
	if(cbCurr.value != '')
	{
		viscap('dWait');
		for(i = 1; i <= 6; i++){
			var dci = '';
			dci = document.getElementById('dC' + i);
			var strSQL = "SELECT " + dci.title + " FROM Arqueo_Ventanilla WHERE Sucursal = '" + sSuc + "' AND Estacion = '" + sCaja + "' AND Moneda = '" + cbCurr.value + "'";
			var dress = GenConretField('General', 'Gen_Find_Field', strSQL, false);
			if(parseInt(dress) < 0)
			{
				dci.className = 'falert';
			} else {
				dci.className = '';
			}
			dci.innerHTML = NumFormProp(dress);
		}
		hidcap('dWait');
	}
}