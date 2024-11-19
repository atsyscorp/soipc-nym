// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCumpEstClie.PHP
//=============================================================
function txID_Press(txid, e)
{
	var code = e.keyCode;
	if(code == 13) {
		Find_Clic();
		return false;		
	}
}
//-----------------------------------------------
//Funcion para buscar cliente
function Find_Clic()
{
	viscap('dbloc');
	//Valida campos obligatorios
	var txId = document.getElementById("txECClie");
	var cbEst = document.getElementById("cbECEstado");
	var txObs = document.getElementById("txECObs");
	//Identificacion cliente
	if(txId.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('txECClie')", "", "Digite el número de identificación del cliente que desea buscar.", 1);
		return false;
	}
	//-----------------------------------------------	
	//limpia tabla
	var sTab = document.getElementById("trECTit").innerHTML;
	InnerCtr('lstECList', '<table cellpadding="0" cellspacing="0" width="100%" id="tbECList"><tr class="bgcol_6 fwhite" id="trECTit">' + sTab + '</tr></table>');
	//Deshabilita guardar
	disbtn('btsave');
	//------------------------------------------------------
	//Define string de consulta
	var strSQL = "SELECT * FROM Clientes WHERE Identificacion='" + txId.value + "'";
	//------------------------------------------------------------------
	var sOpsF = GenConretField("ajax/frCumpEstClie", "Gen_Find_CLI", strSQL, false);
	//Valida si operacion existe
	if(sOpsF == '')
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El cliente con identificación " + txId.value + " no fue encontrado en la base de datos. Verifique el número de identificación.", 1);
	} else {
		var sTab = document.getElementById("tbECList");
		sTab.innerHTML = sTab.innerHTML + sOpsF;
		hidcap('dbloc');
		enabtn('btsave');
		//Pone datos en controles superiores
		cbEst.value = document.getElementById("tx25").value;
		txObs.value = document.getElementById("tx22").value;
	}
}
//------------------------------------------
//Funcion para modificar registro
function cmModif_Clic()
{
	//Valida estado
	var cbEst = document.getElementById("cbECEstado");
	if(cbEst.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('cbECEstado')", "", "Seleccione el estado de cliente.", 1);
		return false;
	}
	//----------------------------------------------
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); viscap('dWait'); cmModif_Clic_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); hidcap('dbloc')", "¿Confirma que desea modificar el estado de cliente con documento: " + document.getElementById("tx1").value + "?", 1);
}
//Continuación de modificar
function cmModif_Clic_1()
{
	//---------------------------------------------------
	//Pone valor de estado y observciones en controles numerados
	var cbEst = document.getElementById("cbECEstado");
	var txObs = document.getElementById("txECObs");
	document.getElementById("tx25").value = cbEst.value;
	document.getElementById("tx22").value = txObs.value;
	//---------------------------------------------------
    var swhere = "Identificacion = '" + document.getElementById("tx1").value + "'";
	//Llama funcion para modificar cliente
	var isuc = genmodif('General', 'Gen_Modif', 30, 'Clientes', swhere);
	//-------------------------------------------------------------		
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}