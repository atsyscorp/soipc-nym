// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA INDEX.PHP
//=============================================================
//-----------------------------------------------
//Funcion login
function login()
{
	var taccess = document.getElementById('tx1');
	var tlogin = document.getElementById('tx2');
	var tcaja = document.getElementById('tx3');	
	//----------------------------------------------
	hidcap('dMsj');
	//Validaciones
	if (taccess.value == ""){ 
		dmsshow('drod_1 dlin_5 bgcol_3', 'falert', 'Digite la clave de acceso para ingresar a la aplicacion.', '');
		taccess.focus();
	   	return 0; 
	}
	if (tlogin.value == ""){ 
		dmsshow('drod_1 dlin_5 bgcol_3', 'falert', 'Digite el permiso de usuario para ingresar a la aplicacion.', '');
		tlogin.focus();
	   	return 0; 
	}
	//------------------------------------------
	//Hace login
	logingo();
}
//------------------------------------------
//Enter ejecuta aceptar login
function entsend(txr, e) {
	var code = e.keyCode;
	if (code == 13) {
		login();	
	}
}
//------------------------------------------
//Funcion aceptar caja
function loginc()
{
	var tcaja = document.getElementById('tx3');	
	//----------------------------------------------
	hidcap('dMsj');
	//Validaciones
	if (tcaja.value == ""){ 
		dmsshow('drod_1 dlin_5 bgcol_3', 'falert', 'Seleccione la estacion desde la que va a trabajar.', '');
		tcaja.focus();
	   	return 0; 
	}
	//------------------------------------------
	//Hace login
	logingo();
}
//--------------------------------------------------
//Funcion para entrar a aplicacion
function logingo()
{
	var taccess = document.getElementById('tx1');
	var tlogin = document.getElementById('tx2');
	var tcaja = document.getElementById('tx3');
	var dmsj = document.getElementById('dpup2');
	//----------------------------------------------
	//Procesando	
	hidcap('dMsj');
	viscap('dbloc');
	viscap('dWait');
	//------------------------------------------------------
	//Ajax
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", "ajax/login.php?var0=" + taccess.value + "&var1=" + tlogin.value + "&var2=" + tcaja.value, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				if(isNaN(txms) == true){
					//Desaparece procesando
					hidcap('dbloc');
					hidcap('dWait');
					dmsj.innerHTML=txms;
				} else if (parseInt(txms) < 100) {	//Muestra seleccion de caja
					for(i = 1; i <= parseInt(txms); i++){
						if (i <= 9){
							tcaja.options[i] = new Option("0" + i, "0" + i);
						} else {
							tcaja.options[i] = new Option(i, i);
						}
					}
					//Desaparece procesando
					hidcap('dbloc');
					hidcap('dWait');
					document.getElementById('dpup3').style.visibility = 'visible';
				} else {
					document.freguser.submit();
				}
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
}
