// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCoorAlerta.PHP
//=============================================================
//--------------------------------------
//Clic en listado
function lstfun(ssuc)
{
	var txId = document.getElementById("tx1");
	var suc = ssuc.attributes["name"].value;
	txId.value = suc;
	//Limpia controles
	ctrclen(8, 1);
	//Deshabilita botones
	disbtn('btmodif');
	disbtn('btdelete');
	enabtn('btaccept');
	//--------------------------------------
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Identificacion='" + txId.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 8, 'Alertas_Usuarios', swhere, 1);	
		if(iFindSuc == 1)
		{
			//Deshabilita botones
			enabtn('btmodif');
			enabtn('btdelete');
		}
		hidcap('dWait');
	}
}
//-------------------------------
//Funcion para aceptar alerta
function Alert_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//--------------------------------------------
	//Consutrye id y pone valores predeterminados
	document.getElementById("tx3").value = hoyday();
	document.getElementById("tx8").value = 'NO';
	document.getElementById('tx1').value = dateid()	;
	document.getElementById("tx2").value = document.getElementById("tx1").value;
	//---------------------------------------------	
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept_2('General', 'Gen_Accept', 8, 'Alertas_Usuarios');
	//--------------------------------------------------
	if(isuc == 10)
	{
		//Actualiza tabla
		var tabuser = document.getElementById("tabAlerts");
		var strSQL = "SELECT * FROM Alertas_Usuarios ORDER BY Fecha DESC LIMIT 0, 30";
		tabuser.innerHTML = GenConretField('ajax/frCoorAlerta', 'UpDate_Alerts', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
/*--------------------------------*/
/*Funcion para modificar alerta*/
function Modi_Alert()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	var swhere = "Identificacion='" + document.getElementById("tx1").value + "'";
	//Funcion generica modificar
	var isuc = genmodif_1('General', 'Gen_Modif', 8, 'Alertas_Usuarios', swhere);
	if(isuc == 10)
	{
		var tabuser = document.getElementById("tabAlerts");
		var strSQL = "SELECT * FROM Alertas_Usuarios ORDER BY Fecha DESC LIMIT 0, 30";
		tabuser.innerHTML = GenConretField('ajax/frCoorAlerta', 'UpDate_Alerts', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//---------------------------
//Funcion para modificar alerta
function Del_Alert()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Alert_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar la alerta " + document.getElementById("tx1").value + "?", 1);
}
//Continuación
function Del_Alert_1()
{
	var swhere = "Identificacion='" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'Alertas_Usuarios', swhere, false);
	if(isuc == 10)
	{
		//Actualiza tabla
		var tabuser = document.getElementById("tabAlerts");
		var strSQL = "SELECT * FROM Alertas_Usuarios ORDER BY Fecha desc LIMIT 0, 30";
		tabuser.innerHTML = GenConretField('ajax/frCoorAlerta', 'UpDate_Alerts', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha eliminado exitosamente en la base de datos.", 1);
		//Limpia controles
		ctrclen(8, 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}