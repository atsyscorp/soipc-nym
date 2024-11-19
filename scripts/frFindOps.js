// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frFindOps.PHP
//=============================================================
//Funcion cambio tipo de operación
function cbBOType_Change()
{
	//Limpia tabla de datos
	var sTab = document.getElementById("trOpsTit").innerHTML;
	InnerCtr('lstBOList', '<table cellpadding="0" cellspacing="0" width="284px" id="tbBOList"><tr class="bgcol_6 fwhite" id="trOpsTit">' + sTab + '</tr></table>');
	//Deshabilita exportar
	disbtn('btexport');
	var cbOps = document.getElementById('tx1');
	if(cbOps.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta nombre de formato
		ValueCtr('txBOForm', GenConretField("General", "Gen_Find_Field", "SELECT Distinct Formato FROM XConf_Consecutivos WHERE Documento='" + cbOps.value + "'", false));
		//Consulta nombre de tabla para buscar operación
		ValueCtr('txBOTable', GenConretField("General", "Gen_Find_Field", "SELECT Distinct Tabla FROM XConf_Consecutivos WHERE Documento='" + cbOps.value + "'", false));
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//----------------------------------------
//Funcion cambio sucursal
function cbBOSucur_Change()
{
	//Deshabilita exportar
	disbtn('btexport');
	var cbOps = document.getElementById('tx1');
	var cbSuc = document.getElementById('tx2');
	if(cbOps.value != '' && cbSuc.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta Prefijo factura
		ValueCtr('txBOPrefix', GenConretField("General", "Gen_Find_Field", "SELECT Prefijo FROM XConf_Consecutivos WHERE Documento='" + cbOps.value + "' AND Sucursal='" + cbSuc.value + "'", false));
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//----------------------------------------
//Funcion para buscar operacion
function Find_Clic()
{
	viscap('dbloc');
	//Valida campos obligatorios
	if(fEmpty(3, 1) == true) {return 0;}
	//limpia tabla
	var sTab = document.getElementById("trOpsTit").innerHTML;
	InnerCtr('lstBOList', '<table cellpadding="0" cellspacing="0" width="284px" id="tbBOList"><tr class="bgcol_6 fwhite" id="trOpsTit">' + sTab + '</tr></table>');
	//Deshabilita exportar
	disbtn('btexport');
	//Genera string de consulta del id de la operación
	var sWhereSuc = ''; //String. para sucursal si entra desde ventanilla
	var sOrigen = document.getElementById("txBOOrigen");   
	if(sOrigen.value == 'VENTANILLA')
	{
		sWhereSuc = " AND Sucursal = '" + document.getElementById("tx2").value + "'";
	}
	var sField = '';
	var sPrefix = '';
	var sTable = document.getElementById("txBOTable");   
	var tPrefix = document.getElementById("txBOPrefix");   
	if(sTable.value == 'Operacion_Ventanilla')
	{
		sField = "Tipo_Operacion";
		sPrefix = " AND Prefijo='" + tPrefix.value + "'";
	} else {
		sField = "Tipo_Movimiento";
	}	
	var strSQL = "SELECT * FROM " + sTable.value + " WHERE " + sField + " = '" + document.getElementById("tx1").value + "' AND Consecutivo = '" + document.getElementById("tx3").value + "'" + sWhereSuc + sPrefix;
	var sOpsF = GenConretField("ajax/frFindOps", "Gen_Find_OPS", strSQL, false);
	//Valida si operacion existe
	if(sOpsF == '')
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El tipo de movimiento " + document.getElementById('tx1').value + " con consecutivo " + document.getElementById('tx3').value + " no fue encontrado. Verifique los datos de la operación.", 1);
	} else {
		//Consulta identificacion de operacion para exportar
	var strSQLE = "SELECT Identificacion FROM " + sTable.value + " WHERE " + sField + " = '" + document.getElementById("tx1").value + "' AND Consecutivo = '" + document.getElementById("tx3").value + "'" + sWhereSuc + sPrefix;
		ValueCtr('txBOId', GenConretField("General", "Gen_Find_Field", strSQLE, false));
		var sTab = document.getElementById("tbBOList");
		sTab.innerHTML = sTab.innerHTML + sOpsF;
		hidcap('dbloc');
		enabtn('btexport');	
	}
}
//-------------------------------------------------------
//Funcion para exportar operacion a formato
function Export_Clic()
{
	var htmlWin = window.open("sFormats/" + document.getElementById("txBOForm").value + ".php?var1=" + document.getElementById("txBOId").value + "&var2=1", "_blank");
}
