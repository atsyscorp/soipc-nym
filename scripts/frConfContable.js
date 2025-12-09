// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfContable.PHP
//=============================================================
//Funcion Cambio de sucursal
function txId_Change(tipo='venta', fechaIni, fechaFin)
{
	//Limpia controles
	ctrclen(10, 1);
	//Deshabilita botones
	disbtn('btmodif');
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Sucursal='" + txId.value + "' AND Tipo_Resolucion='" + tipo + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 10, 'Configuracion_Contable', swhere, 1);	
		if(iFindSuc == 1)
		{
			//Habilita botones
			enabtn('btmodif');
			//Abre fecha y la pone en controles
			//var vFac = document.getElementById('tx6').value.split("-");
			var vFac = fechaIni.split("-");
			document.getElementById('cbYear').value = vFac[0];
			document.getElementById('cbMonth').value = vFac[1];	
			getmdays();
			document.getElementById('cbDay').value = vFac[2];

			var vVen = fechaFin.split("-");
			document.getElementById("cbYear1").value = vVen[0];
			document.getElementById("cbMonth1").value = vVen[1];	
			getmdays_1();
			document.getElementById("cbDay1").value = vVen[2];
		}
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(ssuc)
{
	var txId = document.getElementById("tx1");
	var suc = ssuc.attributes["name"].value;
	var tipo = ssuc.children[8].innerHTML;
	var fechaIni = ssuc.children[6].innerHTML;
	var fechaFin = ssuc.children[7].innerHTML;
	txId.value = suc;
	txId_Change(tipo, fechaIni, fechaFin);
}
//-----------------------------------------
//Funcion para modificar usuario
function Modi_Conf()
{
	var syear = document.getElementById("cbYear").value;
	var smont = document.getElementById("cbMonth").value;
	var sday = document.getElementById("cbDay").value;

	var eyear = document.getElementById("cbYear1").value;
	var emont = document.getElementById("cbMonth1").value;
	var eday = document.getElementById("cbDay1").value;

	//Valida campos obligatorios
	if(fEmpty(10, 1) == true) {return 0;}
	//Valida fecha
	if(syear == '' || smont == '' || sday == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha de inicio de facturación.", 1);
		return false;
	}

	if(eyear == '' || emont == '' || eday == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha de vencimiento de facturación.", 1);
		return false;
	}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Construye string de fecha
	document.getElementById("tx7").value = syear + "-" + smont + "-" + sday;
	document.getElementById("tx8").value = eyear + "-" + emont + "-" + eday;
	//--------------------------------------------------
	var swhere = "Sucursal='" + document.getElementById("tx1").value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 10, 'Configuracion_Contable', swhere);
	if(isuc == 10)
	{
		var tabconf = document.getElementById("tabConta");
		var strSQL = "Select * From Configuracion_Contable";
		tabconf.innerHTML = GenConretField('ajax/frConfContable', 'UpDate_Conta', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//------------------------------------------
//Funcion para exportar tabla de configuracion
function Export_Config(sCompa)
{
	viscap('dWait');
	ExportToHtml('Configuraci�n Contable', sCompa, 'Configuraci�n Contable', 'tabConta');
	hidcap('dWait');
}