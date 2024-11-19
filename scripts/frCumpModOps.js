// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCumpModOps.PHP
//=============================================================
function cbBOType_Change()
{
	//Limpia tabla de datos
	var sTab = document.getElementById("trOpsTit").innerHTML;
	InnerCtr('lstBOList', '<table cellpadding="0" cellspacing="0" width="284px" id="tbBOList"><tr class="bgcol_6 fwhite" id="trOpsTit">' + sTab + '</tr></table>');
	//Deshabilita guardar
	disbtn('btsave');
}
function Find_Clic()
{
	viscap('dbloc');
	//Valida campos obligatorios
	var cbOps = document.getElementById("cbBOType");
	var cbSuc = document.getElementById("cbBOSucur");
	var txCon = document.getElementById("txBOSerie");
	//Tipo operacion
	if(cbOps.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('cbBOType')", "", "Seleccione el tipo de operación que desea buscar.", 1);
		return false;
	}
	//Sucursal
	if(cbBOSucur.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('cbBOSucur')", "", "Seleccione la sucursal que realizó la operación.", 1);
		return false;
	}
	//Consecutivo
	if(txCon.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('txBOSerie')", "", "Digite el consecutivo de la operación que desea buscar.", 1);
		return false;
	}
	//-----------------------------------------------	
	//limpia tabla
	var sTab = document.getElementById("trOpsTit").innerHTML;
	InnerCtr('lstBOList', '<table cellpadding="0" cellspacing="0" width="284px" id="tbBOList"><tr class="bgcol_6 fwhite" id="trOpsTit">' + sTab + '</tr></table>');
	//Deshabilita guardar
	disbtn('btsave');
	//------------------------------------------------------
	//Define string de consulta
    var sWhereSuc = " AND Sucursal = '" + cbSuc.value + "'";
    var sOpsTab = '';
	var sPrefix = '';
    if(cbOps.value == 'INGRESO' || cbOps.value == 'EGRESO')
	{
    	sOpsTab = "Traslados_Ventanilla WHERE Tipo_Movimiento = '";
	} else {
		sOpsTab = "Operacion_Ventanilla WHERE Tipo_Operacion = '";
		sPrefix = " AND Prefijo = '" + GenConretField("General", "Gen_Find_Field", "SELECT Prefijo FROM XConf_Consecutivos WHERE Documento='" + cbOps.value + "' AND Sucursal='" + cbSuc.value + "'", false) + "'";
	}
	var strSQL = "SELECT * FROM " + sOpsTab + cbOps.value + "' AND Consecutivo = '" + txCon.value + "'" + sWhereSuc + sPrefix;
	//------------------------------------------------------------------
	var sOpsF = GenConretField("ajax/frCumpModOps", "Gen_Find_OPS", strSQL, false);
	//Valida si operacion existe
	if(sOpsF == '')
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El tipo de movimiento " + cbOps.value + " con consecutivo " + txCon.value + " no fue encontrado. Verifique los datos de la operación.", 1);
	} else {
		var sTab = document.getElementById("tbBOList");
		sTab.innerHTML = sTab.innerHTML + sOpsF;
		hidcap('dbloc');
		enabtn('btsave');	
	}
}
//------------------------------------------
//Funcion para modificar registro
function cmModif_Clic()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); viscap('dWait'); cmModif_Clic_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); hidcap('dbloc')", "¿Confirma que desea modificar la información de la operación o movimiento número " + document.getElementById("txBOSerie").value + "?", 1);
}
//Continuación de modificar
function cmModif_Clic_1()
{
    var swhere = "Identificacion = '" + document.getElementById("tx1").value + "'";
	//Llama funcion dependiendo de tipo de operación
	var cbOps = document.getElementById("cbBOType");
    if(cbOps.value == 'INGRESO' || cbOps.value == 'EGRESO')
	{
		var isuc = genmodif('General', 'Gen_Modif', 19, 'Traslados_Ventanilla', swhere);

	} else {
		var isuc = genmodif('General', 'Gen_Modif', 56, 'Operacion_Ventanilla', swhere);
	}
	//-------------------------------------------------------------		
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}