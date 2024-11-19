// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfBanks.PHP
//=============================================================
//Funcion cambio seleccion nombre banco
function cbBank_Change()
{
	//Limpia controles
	ValueCtr('tx3', '');
	ValueCtr('tx4', '');
	//-------------------------------
	var cbBank = document.getElementById("tx2");
	if(cbBank.value != '')
	{
		viscap('dWait');
		var strSQN = "Select NIT From XConf_Bancos Where Nombre_Banco='" + cbBank.value + "'";
		var strSQC = "Select Codigo_Banco From XConf_Bancos Where Nombre_Banco='" + cbBank.value + "'";
		ValueCtr('tx3', GenConretField('General', 'Gen_Find_Field', strSQN, false));
		ValueCtr('tx4', GenConretField('General', 'Gen_Find_Field', strSQC, false));
		hidcap('dWait');
	}
}
//--------------------------------------------
//Funcion digitacion de numero de cuenta
function txId_Change()
{
	//Limpia controles
	ctrclen(10, 1);
	//Deshabilita botones
	disbtn('btmodif');
	disbtn('btdelete');
	enabtn('btaccept');
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Numero_Cuenta = '" + txId.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 10, 'Cuentas_Bancarias', swhere, 1);	
		if(iFindSuc == 1)
		{
			//Deshabilita botones
			enabtn('btmodif');
			enabtn('btdelete');
			disbtn('btaccept');
		}
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(scount)
{
	var txId = document.getElementById("tx1");
	var count = scount.attributes["name"].value;
	txId.value = count;
	txId_Change();
}
//-----------------------------------------
//Funcion para aceptar cuenta
function Count_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(10, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 10, 'Cuentas_Bancarias');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabcount = document.getElementById("tabBanks");
		var strSQL = "Select * From Cuentas_Bancarias";
		tabcount.innerHTML = GenConretField('ajax/frConfBanks', 'UpDate_Banks', strSQL, false);
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
//Funcion para modificar cuenta
function Modi_Count()
{
	//Valida campos obligatorios
	if(fEmpty(10, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	var swhere = "Numero_Cuenta='" + document.getElementById("tx1").value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 10, 'Cuentas_Bancarias', swhere);
	if(isuc == 10)
	{
		var tabcount = document.getElementById("tabBanks");
		var strSQL = "Select * From Cuentas_Bancarias";
		tabcount.innerHTML = GenConretField('ajax/frConfBanks', 'UpDate_Banks', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------
//Funcion para eliminar cuenta
function Del_Count()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Count_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar la cuenta bancaria número " + document.getElementById("tx1").value + "?", 1);
}
//Continuación eliminar
function Del_Count_1()
{
	var swhere = "Numero_Cuenta = '" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'Cuentas_Bancarias', swhere, false);
	if(isuc == 10)
	{
		var tabcount = document.getElementById("tabBanks");
		var strSQL = "Select * From Cuentas_Bancarias";
		tabcount.innerHTML = GenConretField('ajax/frConfBanks', 'UpDate_Banks', strSQL, false);
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

