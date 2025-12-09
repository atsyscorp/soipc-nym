// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfCostCen.PHP
//=============================================================
//--------------------------------------------
//Funcion digitacion de centro de costos
function txId_Change()
{
	//Deshabilita botones
	disbtn('btdelete');
	enabtn('btaccept');
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Centro_Costos = '" + txId.value + "'";
		var iFindSuc = GenConret_1('General', 'RegCount', 'XConf_CostCenter', swhere, false);;	
		if(iFindSuc == 1)
		{
			//Deshabilita botones
			enabtn('btdelete');
			disbtn('btaccept');
		}
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(scost)
{
	var txId = document.getElementById("tx1");
	var cost = scost.attributes["name"].value;
	txId.value = cost;
	txId_Change();
}
//-----------------------------------------
//Funcion para aceptar cuenta
function Cost_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(1, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 1, 'XConf_CostCenter');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabcost = document.getElementById("tabCosts");
		var strSQL = "Select * From XConf_CostCenter";
		tabcost.innerHTML = GenConretField('ajax/frConfCostCen', 'UpDate_Costs', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
		disbtn('btaccept');
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------
//Funcion para eliminar cuenta
function Del_Cost()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Cost_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar el centro de costos " + document.getElementById("tx1").value + "?", 1);
}
//Continuación eliminar
function Del_Cost_1()
{
	var swhere = "Centro_Costos = '" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'XConf_CostCenter', swhere, false);
	if(isuc == 10)
	{
		var tabcost = document.getElementById("tabCosts");
		var strSQL = "Select * From XConf_CostCenter";
		tabcost.innerHTML = GenConretField('ajax/frConfCostCen', 'UpDate_Costs', strSQL, false);
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
