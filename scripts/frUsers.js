// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frUsers.PHP
//=============================================================
//Funcion para abrir vector de modulose acceso
function txUAcces()
{
	var txAc = document.getElementById("tx5");
	if(txAc.value != '')
	{
		sChAcces = txAc.value.split('|');
		//Configuracion
		chPActUser(sChAcces[0], 1);	
		//Ventanilla
		chPActUser(sChAcces[1], 2);	
		//Coordinacion
		chPActUser(sChAcces[2], 3);	
		//Contabilidad
		chPActUser(sChAcces[4], 4);	
		//Cumplimiento
		chPActUser(sChAcces[5], 5);	
	} else {
		for(i = 1; i <= 5; i++){
			var chCtr = document.getElementById('ch' + i); 
			chCtr.checked = false;
		}
	}
}
//Funcion para chequiar box
function chPActUser(sUVec, ich)
{
	var chUserA = document.getElementById("ch" + ich);
	if(parseInt(sUVec) == 1){
		chUserA.checked = true;
	} else {
		chUserA.checked = false;
	}
}
//--------------------------------------------
//Funcion digitacion de documento de usuario
function txId_Change()
{
	//Limpia controles
	ctrclen(9, 1);
	//Deshabilita botones
	disbtn('btmodif');
	disbtn('btdelete');
	enabtn('btaccept');
	//Llama funcion de acceso
	txUAcces();
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Identificacion = '" + txId.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 9, 'Usuarios', swhere, 1);	
		if(iFindSuc == 1)
		{
			//Deshabilita botones
			enabtn('btmodif');
			enabtn('btdelete');
			disbtn('btaccept');
			//Llama funcion de acceso
			txUAcces();
		}
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(suser)
{
	var txId = document.getElementById("tx1");
	var user = suser.attributes["name"].value;
	txId.value = user;
	txId_Change();
}
//--------------------------------------
//Funcion para construir string de acceso
function strAcces()
{
	var sAcces = '';
	for(i = 1; i <= 5; i++){
		var chCtr = document.getElementById('ch' + i); 
		if(i == 4){sAcces = sAcces + '0|';} 		
		if(chCtr.checked == false){
			sAcces = sAcces + '0|';
		} else {
			sAcces = sAcces + '1|';
		}
	}
	sAcces = sAcces + '0';
	document.getElementById("tx5").value = sAcces;
}
//-----------------------------------------
//Funcion para aceptar usuario
function User_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//---------------------------------------------------
	//Construye string acceso
	strAcces();
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Valida si existe login igual
	var swhere = "Identificacion <> '" + document.getElementById("tx1").value + "' AND ClaveAcceso = '" + document.getElementById("tx3").value + "'";	
	var userep = GenConret_1('General', 'RegCount', 'Usuarios', swhere, false);
	if(userep != '0')
	{
		hidcap('dWait');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No puede crear usuarios con la misma clave de acceso. Verifique la información.", 1);
		return false;
	}
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 9, 'Usuarios');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabuser = document.getElementById("tabUsers");
		var strSQL = "SELECT * FROM Usuarios";
		tabuser.innerHTML = GenConretField('ajax/frUsers', 'UpDate_Users', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
		disbtn('btaccept');
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-----------------------------------------
//Funcion para modificar usuario
function Modi_User()
{
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//---------------------------------------------------
	//Construye string acceso
	strAcces();
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Valida si existe login igual
	var swhere = "Identificacion <> '" + document.getElementById("tx1").value + "' AND ClaveAcceso = '" + document.getElementById("tx3").value + "'";	
	var userep = GenConret_1('General', 'RegCount', 'Usuarios', swhere, false);
	if(userep != '0')
	{
		hidcap('dWait');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No puede crear usuarios con la misma clave de acceso. Verifique la información.", 1);
		return false;
	}
	//----------------------------------------------------
	var swhere = "Identificacion='" + document.getElementById("tx1").value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 9, 'Usuarios', swhere);
	if(isuc == 10)
	{
		var tabuser = document.getElementById("tabUsers");
		var strSQL = "SELECT * FROM Usuarios";
		tabuser.innerHTML = GenConretField('ajax/frUsers', 'UpDate_Users', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------
//Funcion para eliminar usuario
function Del_User()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_User_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar el usuario con documento " + document.getElementById("tx1").value + "?", 1);
}
//Continuación eliminar
function Del_User_1()
{
	var swhere = "Identificacion = '" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'Usuarios', swhere, false);
	if(isuc == 10)
	{
		var tabuser = document.getElementById("tabUsers");
		var strSQL = "SELECT * FROM Usuarios";
		tabuser.innerHTML = GenConretField('ajax/frUsers', 'UpDate_Users', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha eliminado exitosamente en la base de datos.", 1);
		//Limpia controles
		ValueCtr('tx1', '');
		txId_Change();
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//----------------------------------------------
//Funcion para ordenar tabla
function User_Order(sfield, sdir)
{
	viscap('dWait');
	var tabuser = document.getElementById("tabUsers");
	var strSQL = "SELECT * FROM Usuarios ORDER BY " + sfield + " " + sdir;
	tabuser.innerHTML = GenConretField('ajax/frUsers', 'UpDate_Users', strSQL, false);
	hidcap('dWait');
}
//------------------------------------------
//Funcion para exportar tabla de usuarios
function Export_User(sCompa)
{
	viscap('dWait');
	ExportToHtml('Usuarios', sCompa, 'Listado de usuarios', 'tabUsers');
	hidcap('dWait');
}