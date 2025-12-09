// JavaScript Document
//=============================================================
//FUNCIONES GENERALES PARA TODOS LOS FORMULARIOS
//=============================================================
//Variables publicas


//=============================================================
//Función para validar acceso a ventanas fuera de iframe
function ValWinFrame()
{
	var dfifr = window.parent.document.getElementById('frMain');
	if(dfifr == null){
		window.location = 'index.php';
	}
}
//=============================================================
//Mostrar capas
function viscap(sdiv)
{
	var dobj = document.getElementById(sdiv);
	dobj.style.visibility = 'visible';
}
//ocultar capas
function hidcap(sdiv)
{
	var dobj = document.getElementById(sdiv);
	dobj.style.visibility = 'hidden';
}
//Funcion para mostrar o ocultar capa
function menusol(nomcap){ 
	var dcap = document.getElementById(nomcap)
	if (dcap.style.visibility == "hidden"){
		viscap(nomcap);
	} else {
		hidcap(nomcap);
	}
}
//Funcion para mostrar mensajes de error o exito
function dmsshow(clot, clin, txmsg, i)
{
	var MsDiv = document.getElementById('dMsj' + i);
	var MsTex = document.getElementById('dMsjm' + i);
	//Cambia las clases
	MsDiv.className = clot;
	MsTex.className = clin;
	//Mensaje
	MsTex.innerHTML = txmsg;
	//muestra
	viscap('dMsj' + i);
}
//Funcion completa mostrar mensajes de alerta
function dmsshowc(clot, clin, scanstyle, sacfun, scanfun, txmsg, i)
{
	var MsDiv = document.getElementById('dMsj' + i);
	var MsTex = document.getElementById('dMsjm' + i);
	var btAccept = document.getElementById('btaccept' + i);
	var btCancel = document.getElementById('btcancel' + i);
	//Cambia las clases
	MsDiv.className = clot;
	MsTex.className = clin;
	//----------------------------------------
	//Estilo boton cancelar
	btCancel.style.visibility = scanstyle;
	//Funcion botones
	btAccept.setAttribute("onclick", sacfun);
	btCancel.setAttribute("onclick", scanfun);
	//Mensaje
	MsTex.innerHTML = txmsg;
	//muestra
	viscap('dMsj' + i);
	btAccept.focus();
}

//Funcion para poner imagen wait dentro de div
function waitim(nomcap)
{
	var dcap = document.getElementById(nomcap)
	dcap.innerHTML = "<div style='text-align:center; margin:5px'><img src='images/wait.gif' width='21px' height='21px' /></div>";
}
//------------------------------------------
//Función para cerrar ventanas
function closewin()
{
	var dfifr = window.parent.document.getElementById('frMain');
	//----------------------------------------------------------
	//Cambia la direccion del frame
	dfifr.src = '';
}
//--------------------------------------------
//Funcion para mantener siempre el valor de un txbox
function keepval(scap, sval)
{
	var dcap = document.getElementById(scap);
	dcap.value = sval;
	alert(sval);
}
//-----------------------------------------------------------------------------
//Funcion para poner boton en estado habilitado
function enabtn(sbtn)
{
	var dcap = document.getElementById(sbtn);
	dcap.disabled = false;
	dcap.className = 'btcont';
}
//Funcion para poner boton en estado deshabilitado
function disbtn(sbtn)
{
	var dcap = document.getElementById(sbtn);
	dcap.disabled = true;
	dcap.className = 'btcontdis';
}
//Funcion para limpiar contenido de control
function InnerCtr(sCtr, sCont)
{
	var dcap = document.getElementById(sCtr);
	dcap.innerHTML = sCont;
}
//Funcion para limpiar valor de control
function ValueCtr(sCtr, sCont)
{
	var dcon = document.getElementById(sCtr);
	dcon.value = sCont;
}
//Funcion para poner foco en control
function ctrfocus(sCtr)
{
	var dcon = document.getElementById(sCtr);
	dcon.focus();
}
//-----------------------------------------------------------------------------
//Funcion para permitir solo caracteres numericos
sDecsepa = '.';	//Variable publica separador decimal
sMilsepa = ','; //Separador de miles
function Onlynum(txnum, e) {
	var code = e.keyCode;
	if ((code >= 48 && code <= 57) || (code >= 93 && code <= 105) || (code == 8) || (code == 9) || (code == 37) || (code == 39)) {
		return true;	
	} else {
		return false;	
	}
}
//Con punto decimal
function OnlynumDec(txnum, e) {
	var code = e.keyCode;
	if(code == 110 || code == 190)
	{
		if(txnum.value.indexOf(sDecsepa) != -1 || txnum.value == '')
		{
			return false;
		}
	} else {
		if ((code >= 48 && code <= 57) || (code >= 93 && code <= 105) || (code == 8) || (code == 9) || (code == 37) || (code == 39)) {
			return true;	
		} else {
			return false;	
		}
	}
}
//--------------------------------------------------
//Funcion formato numerico miles
function NumFormat(snum)
{
	var funval = '';
	snum = snum.toString().replace(sMilsepa, '');
	snum = snum.toString().replace(sMilsepa, '');	//Toca hacer esto dos veces por que con 8 caracteres no funciona
	if(isNaN(snum) == false){
		var istr = snum.length;
		if(istr <= 6 && istr >= 4){
			funval = snum.substring(0, istr - 3) + sMilsepa + snum.substring(istr - 3);				
		} else if(istr >= 7) {
			funval = snum.substring(0, istr - 6) + sMilsepa + snum.substring(istr - 6, istr - 3) + sMilsepa + snum.substring(istr - 3);				
		} else {
			funval = snum;
		}
		return funval;
	} else { 
		return snum;
	}
}
//Funcion formato numerico
function NumFormProp(snum)
{
	var funval = '';
	var stemp = '';
	snum = snum.toString().replace(sMilsepa, '');
	snum = snum.toString().replace(sMilsepa, '');	//Toca hacer esto dos veces por que con 8 caracteres no funciona
	if(isNaN(snum) == false){
	    var iDecSepa = snum.indexOf(sDecsepa)
        if(iDecSepa == -1 && snum != '') {
                funval = NumFormat(snum);
		} else if (iDecSepa != -1) {
           	stemp = snum.split(sDecsepa);
			if(parseInt(snum) < 0 && parseInt(snum) > -1) {
				funval = "-" + NumFormat(stemp[0]) + sDecsepa + stemp[1];
			} else {
            	funval = NumFormat(stemp[0]) + sDecsepa + stemp[1];;
			}
		}
		return funval;
	} else {
		return 0;
	}
}
//Funcion cambio de texto en campo poner formato numerico
function txChange_Num(dcap)
{
	document.getElementById(dcap).value = NumFormProp(document.getElementById(dcap).value);	
}
//Funcion para quitar separador de miles a campos numericos
function DelMilsepa(snum)
{
	var stemp = '';
	stemp = snum.toString().replace(sMilsepa, '');
	stemp = stemp.toString().replace(sMilsepa, '');
	if(isNaN(stemp) == false){
		return stemp;
	} else {
		return snum;
	}
}
//-----------------------------------------------------------------------------
//Funcion general para consultar y retornar valor
function GenConret(sfile, sfunc, stable, swhere, sasinc)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/" + sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere, sasinc);
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
//-------------------------------------------------------------------
//Funcion para retornar valor dentro del ajax
function GenConret_0(sfile, sfunc, stable, swhere, sasinc, ctrid)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/" + sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere, sasinc);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				//Muestra la cantidad de usuarios conectados
				funval = txms;
				document.getElementById(ctrid).innerHTML = funval;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
//---------------------------------------------------------------------
//Funcion para retornar valor dentro del ajax
function GenConret_0_1(sfile, sfunc, stable, swhere, sasinc, ctrid)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/" + sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere, sasinc);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				//Muestra la cantidad de usuarios conectados
				funval = txms;
				var dctr = document.getElementById(ctrid); 
				dctr.innerHTML = dctr.innerHTML + funval;
				dctr.scrollTop = dctr.scrollHeight;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}

//---------------------------------------------------------------------
//Funcion general para consultar y retornar valor
function GenConret_1(sfile, sfunc, stable, swhere, sasinc)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere, sasinc);
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
//-------------------------------------------------------------------
//Funcion general para consultar y retornar valor
function GenConret_2(sfile, sfunc, stable, swhere, sasinc, bbloc, bwait, bprog, sprog)
{
	var jl = 0;
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere, sasinc);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				//Muestra la cantidad de usuarios conectados
				funval = txms;
			} else if(request.readyState > 2 ){
				viscap(bbloc);
				viscap(bwait);
				viscap(bprog);
				jl++;
				InnerCtr('dPrMsg', sprog + jl)
				var txms = '';
				txms = request.responseText;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;
}
//-------------------------------------------------------------------
//Funcion general para modificar un campo en base de datos
function GenUpdateField(sfile, sfunc, stable, swhere, sfield, sasinc)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfunc + "&stable=" + stable + "&swhere=" + swhere + "&sfield=" + sfield, sasinc);
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
//-------------------------------------------------------------------
//Funcion general para consultar y retornar valor un solo campo
function GenConretField(sfile, sfunc, strSQL, sasinc)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfunc + "&strSQL=" + strSQL, sasinc);
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
//-------------------------------------------
//Funcion para anteponer cero 
function antcero(ivar)
{
	var funval = '';
	if(parseInt(ivar) < 10){
		funval = "0" + ivar;
	} else {
		funval = ivar;
	}
	return funval;
}
//-----------------------------------------
//Funcion para crear string AAAAMMDDHHMMSS
function dateid()
{
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	var day = today.getDate();
	var hour = today.getHours();
	var mins = today.getMinutes();
	var secs = today.getSeconds();
	var idreg = year.toString() + antcero(month) + antcero(day) + antcero(hour) + antcero(mins) + antcero(secs);
	return idreg;
}
//-----------------------------------------
//Funcion para crear fecha hoy
function hoyday()
{
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth() + 1;
	var day = today.getDate();
	var fdate = year + "-" + antcero(month) + "-" + antcero(day);
	return fdate;
}
//---------------------------------------
//Funcion para crear hora hoy
function hoyhour()
{
	var today = new Date();
	var hour = today.getHours();
	var mins = today.getMinutes();
	var secs = today.getSeconds();
	var fdate = antcero(hour) + ":" + antcero(mins) + ":" + antcero(secs);
	return fdate;
}
//Funcion en cambio de fecha obtiene el numero de días del mes
function getmdays()
{
	var cbyear = document.getElementById('cbYear');
	var cbmonth = document.getElementById('cbMonth');
	var cbday = document.getElementById('cbDay');
	//Desactiva combo dias
	cbday.selectedIndex = 0;
	while(cbday.length > 1){
		cbday.remove(cbday.length-1);
	}
	if(cbyear.value != '' && cbmonth.value != '')
	{
		cbday.disabled = true;
		//Rellena combo
		var idays = new Date(cbyear.value, cbmonth.value, 0).getDate();
		for(i = 1; i < idays + 1; i++){
			cbday.options[i] = new Option(antcero(i), antcero(i));
		}
		cbday.disabled = false;
	}
}
//Funcion en cambio de fecha obtiene el numero de días del mes --> combos 1
function getmdays_1()
{
	var cbyear = document.getElementById('cbYear1');
	var cbmonth = document.getElementById('cbMonth1');
	var cbday = document.getElementById('cbDay1');
	//Desactiva combo dias
	cbday.selectedIndex = 0;
	while(cbday.length > 1){
		cbday.remove(cbday.length-1);
	}
	if(cbyear.value != '' && cbmonth.value != '')
	{
		cbday.disabled = true;
		//Rellena combo
		var idays = new Date(cbyear.value, cbmonth.value, 0).getDate();
		for(i = 1; i < idays + 1; i++){
			cbday.options[i] = new Option(antcero(i), antcero(i));
		}
		cbday.disabled = false;
	}
}
//-----------------------------------------------------------
//Funcion general buscar registros y ponerlos en formulario
function genfind(sfile, sfun, iparam, stable, swhere, ijump)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + "&var0=" + iparam + "&stable=" + stable + "&swhere=" + swhere, false);
		//request.setRequestHeader("Content-Type: text/html; charset=utf-8");
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				if(txms != ''){
					var resvec = txms.split(".|.");
					for(i = 1; i <= iparam; i++){
						if(i != ijump){
							var evtx = '';
							evtx = document.getElementById('tx' + i); 
							evtx.value = resvec[i - 1];
						}
					}
					funval = 1;
				}
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;
}
//--------------------------------------------------------------
//Funcion para limpiar campos de ventana
function ctrclen(ccont, ijump)
{
	var i = 0;
	for(i = 1; i <= ccont; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		if(i != ijump){
			//Limpia control a excepcion del utilizado como id
			evtx.value = '';
		}
	}
}
//-------------------------------------------
//Funcion general para hacer registro en base de datos por GET
function genaccept(sfile, sfun, iparam, stable)
{
	//-----------------------------------------------
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i < iparam + 1; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		sparam = sparam + "&var" + i + "=" + (DelMilsepa(evtx.value)).toUpperCase(); 
	}
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;			
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//-------------------------------------------
//Funcion general para hacer registro en base de datos por GET
//Con marcador de controles cuando hay mas de un proceso de aceptar
function genaccept_1(sfile, sfun, iparam, stable, smar)
{
	//-----------------------------------------------
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i < iparam + 1; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + smar + i); 
		sparam = sparam + "&var" + i + "=" + (DelMilsepa(evtx.value)).toUpperCase(); 
	}
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;			
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//-------------------------------------------
//Funcion general para hacer registro sin convertir a mayuscula
function genaccept_2(sfile, sfun, iparam, stable)
{
	//-----------------------------------------------
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i < iparam + 1; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		sparam = sparam + "&var" + i + "=" + (DelMilsepa(evtx.value)); 
	}
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;			
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//-------------------------------------------
//Funcion para validacion de campos obligatorios
function fEmpty(ccont, dmsj)
{
	//-----------------------------------------
	var i = 0;
	var j = 0;
	var fEmpty = false;
	for(i = 1; i <= ccont; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		if(evtx.className == 'txboxo'){
			//Regresa al color normal por si hubo mensaje de error anterior
			evtx.style.backgroundColor = '#EBEBEB';		
			if(evtx.value == ''){
				j = 1;
				evtx.style.backgroundColor = '#FFF2EC';		
			}
		}
	}
	//----------------------------------
	if(j == 1){
		fEmpty = true;
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + dmsj + "'); hidcap('dbloc')", "", "Los cuadros con fondo gris son campos obligatorios. Verifique la información.", dmsj);
	}
	return fEmpty;
}
//--------------------------------------------------------------
//Funcion general para modificar registro en base de datos
function genmodif(sfile, sfun, iparam, stable, swhere)
{
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i < iparam + 1; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		sparam = sparam + "&var" + i + "=" + evtx.name + ".|." + (DelMilsepa(evtx.value)).toUpperCase(); 
	}
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable + "&swhere=" + swhere, false);
		//request.setRequestHeader("Content-Type: text/html; charset=utf-8");
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//--------------------------------------------------------------
//Funcion general para modificar registro en base de datos --> No convierte textos a mayusculas
function genmodif_1(sfile, sfun, iparam, stable, swhere)
{
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	for(i = 1; i < iparam + 1; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		sparam = sparam + "&var" + i + "=" + evtx.name + ".|." + (DelMilsepa(evtx.value)); 
	}
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable + "&swhere=" + swhere, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//---------------------------------------------------
//Funcion para actualizar tabla de info rapida
function MainTable(sType, sSerie, sDesc, sCur, sPrice, sCant, sMed)
{
		var funval = '';
		var tbOps = window.parent.document.getElementById('lstMCList');
		var dOps = window.parent.document.getElementById('dMCList');
		tbOps.innerHTML = tbOps.innerHTML + '<tr valign="middle" style="cursor:pointer" class="fcont trwhite">' +
							'<td class="celrow">' + sType + '</td>' +
							'<td class="celrow">' + sSerie + '</td>' + 
							'<td class="celrow">' + sDesc + '</td>' + 
							'<td class="celrow">' + sCur + '</td>' +
							'<td class="celrow">' + sPrice + '</td>' +
							'<td class="celrow">' + sCant + '</td>' + 
							'<td class="celrow">' + sMed + '</td>' +
							'</tr>';
		dOps.scrollTop = dOps.scrollHeight;
}
//-------------------------------------------------
//Funcion para actualizar saldos de caja
function ActCaja(iSuc, iCaja, iCur, sType, iCant)
{
	//Captura variables	
	var suc = document.getElementById('tx' + iSuc);
	var caja = document.getElementById('tx' + iCaja);
	var cur = document.getElementById('tx' + iCur);
	var cant = document.getElementById('tx' + iCant);
	//-------------------------------------------------
	//Construye string
	var param = suc.value + ".|." + caja.value + ".|." + cur.value + ".|." + sType + ".|." + DelMilsepa(cant.value);
	var ifun = GenConretField('General', 'ActCaja', param, false);
	//Si el valor de ifun es 0 agrega item a combo de main
	cbCurM = window.parent.document.getElementById('cbMCCurr');
	if(ifun == 0)
	{
		cbCurM.options[cbCurM.length] = new Option(cur.value, cur.value);
	}
	//------------------------------------------------
	//actualiza seleccion de combo en main
	if(cbCurM.selectedIndex > 0)
	{
		var isel = cbCurM.selectedIndex;
		cbCurM.selectedIndex = 0;
		cbCurM.selectedIndex = isel;
		cbCurM.onchange();
	}
}
//-----------------------------------------------------
//Funcion para impresion de formatos
function GenPrint(sForm, sId, iCopy)
{
	//Direcciona el frame al formato de impresion
	for(i = 1; i <= parseInt(iCopy); i++){
		var frPr = document.getElementById('frPrint' + i);
		//--------------------------------------------
		//Hace impresion
		window.frames['frPrint' + i].focus();
		frPr.src = "sFormats/" + sForm + ".php?var1=" + sId;
	}
}
//Funcion imprimir onload --> Se hace en dos funciones para asegurar la carga de documento antes de impresion
//La función se llama en el onload del formato
var PrintTime; 
iprint1 = 800;
iprcon = 0;
function GenPrint1(iCopy)	
{
	if(iCopy == '')
	{
		iprcon++;
		PrintTime = setTimeout(function() { GenPrint1(iCopy) }, iprint1);
		if(iprcon == 5){
			window.print();
			//iprcon = 0;
		}
	}
}
//---------------------------------------------
//Fncion para exportar a nuevo html
function ExportToHtml(shtName, shtCompa, shtHead, lstList)
{
	var htmlWin = window.open("sFormats/Reports.html", "_blank");
	htmlWin.onload = function(){
		//Define titulo
		htmlWin.document.title = shtName;
		//Capa de contenido
		var dcont = htmlWin.document.getElementById('dCont');
		var sComp = '<span class="fcont" style="font-size:23px">SOIPC - ' + shtCompa + '<br />';
		var sTab = '<span class="fcont" style="font-size:18px">' + shtHead + '<p></p>';
		dcont.innerHTML = sComp + sTab + document.getElementById(lstList).innerHTML;
	}
	htmlWin.focus();
}
//-------------------------------------------------------------------
//Funcion general para calificacion sipla sistema
function SiplaOps(sops, sseg, sidc, sval, sfec)
{
	var funval = '';
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "General.php?sFun=SiplaOps" + "&sops=" + sops + "&sseg=" + sseg + "&sidc=" + sidc + "&sval=" + sval + "&sfec=" + sfec, false);
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
