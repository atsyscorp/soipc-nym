// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCumpParam.PHP
//=============================================================
//Función cambio tipo de operacion
function Type_Change()
{
	//Limpia controles
	ctrclen(8, 1);
	//Limpia combo de segmentos
	InnerCtr('tx3', '<option value=""></option>');
	//Limpia tabla de parametros
	var sTab = document.getElementById("trParTit").innerHTML;
	InnerCtr('tabParam', '<table cellpadding="0" cellspacing="0" width="750px"><tr class="bgcol_6 fwhite" id="trParTit">' + sTab + '</tr></table>');
	//Deshabilita aceptar y modificar
	disbtn('btmodif');
	disbtn('btaccept');
	disbtn('btdelete');
	//------------------------------------------------------------
	var cbType = document.getElementById("tx1");
	if(cbType.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta segmentos para poner en combo
		var strSQL =  "SELECT Segmento FROM Segmentos_Mercado";
		var cbSeg = document.getElementById("tx3");
		cbSeg.innerHTML = cbSeg.innerHTML + GenConretField('ajax/frCumpParam', 'UpDate_cbSeg', strSQL, false);
		//--------------------------------------------------------
		//Consulta segmentos para poner en tabla
		var strSQL = "Select * From Parametros_Segmentacion Where Operacion = '" + cbType.value + "'";
		InnerCtr('tabParam', GenConretField('ajax/frCumpParam', 'UpDate_Param', strSQL, false));
		//--------------------------------------------------------
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//----------------------------------------------------
//Funcion para buscar parametros de segmentos seleccionados
function Segmento_OnChange()
{
	//Limpia controles
	document.getElementById('tx2').value = ''; 
	for(i = 4; i <= 8; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		evtx.value = '';
	}
	//----------------------------------------------------------
	var cbType = document.getElementById("tx1");
	var cbSeg = document.getElementById("tx3");
	if(cbSeg.value != '' && cbType.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Operacion = '" + cbType.value + "' And Segmento = '" + cbSeg.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 8, 'Parametros_Segmentacion', swhere, 3);	
		if(iFindSuc == 1)
		{
			enabtn('btmodif');
			enabtn('btdelete');
			disbtn('btaccept');
			//Formato numerico
			document.getElementById("tx4").value = NumFormProp(document.getElementById("tx4").value);
			document.getElementById("tx5").value = NumFormProp(document.getElementById("tx5").value);
			document.getElementById("tx6").value = NumFormProp(document.getElementById("tx6").value);
			document.getElementById("tx7").value = NumFormProp(document.getElementById("tx7").value);
			document.getElementById("tx8").value = NumFormProp(document.getElementById("tx8").value);
		} else {
			disbtn('btmodif');
			disbtn('btdelete');
			enabtn('btaccept');
			//-----------------------------------------------------
			//Consulta grupo segmento si no existe en listado
			var strSQLS = "Select Grupo From Segmentos_Mercado Where Segmento='" + cbSeg.value + "'";
			ValueCtr('tx2', GenConretField('General', 'Gen_Find_Field', strSQLS, false));
		}
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(scur)
{
	var cbSeg = document.getElementById("tx3");
	var cur = scur.attributes["name"].value;
	cbSeg.value = cur;
	Segmento_OnChange();
}
//-------------------------------------------------------
//Funcion para aceptar parametros
function Accept_Param()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	var cbType = document.getElementById("tx1");
	var cbSeg = document.getElementById("tx3");
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 8, 'Parametros_Segmentacion');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabpar = document.getElementById("tabParam");
		var strSQL = "Select * From Parametros_Segmentacion Where Operacion = '" + cbType.value + "'";
		tabpar.innerHTML = GenConretField('ajax/frCumpParam', 'UpDate_Param', strSQL, false);
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
//Funcion para modificar sucursal
function Modi_Param()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	var cbType = document.getElementById("tx1");
	var cbSeg = document.getElementById("tx3");
	//--------------------------------------------------
	var swhere = "Operacion='" + cbType.value + "' AND Segmento='" + cbSeg.value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 8, 'Parametros_Segmentacion', swhere);
	if(isuc == 10)
	{
		var tabpar = document.getElementById("tabParam");
		var strSQL = "Select * From Parametros_Segmentacion Where Operacion = '" + cbType.value + "'";
		tabpar.innerHTML = GenConretField('ajax/frCumpParam', 'UpDate_Param', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------
//Funcion para eliminar sucursal
function Del_Param()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Param_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar los parámetros del segmento " + document.getElementById("tx3").value + "?", 1);
}
//Continuación eliminar
function Del_Param_1()
{
	var cbType = document.getElementById("tx1");
	var cbSeg = document.getElementById("tx3");
	//--------------------------------------------------
	var swhere = "Operacion='" + cbType.value + "' AND Segmento='" + cbSeg.value + "'";
	var isuc = GenConret_1('General', 'Gen_Delete', 'Parametros_Segmentacion', swhere, false);
	if(isuc == 10)
	{
		//Actualiza tabla
		var tabpar = document.getElementById("tabParam");
		var strSQL = "Select * From Parametros_Segmentacion Where Operacion = '" + cbType.value + "'";
		tabpar.innerHTML = GenConretField('ajax/frCumpParam', 'UpDate_Param', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha eliminado exitosamente en la base de datos.", 1);
		//Limpia controles
		ValueCtr('tx3', '');
		Segmento_OnChange();
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//------------------------------------------
//Funcion para exportar tabla de parametros segmentacion
function Export_Param(sCompa)
{
	viscap('dWait');
	ExportToHtml('Parametros Segmentos', sCompa, 'PARÁMETROS SEGMENTOS DE MERCADO', 'tabParam');
	hidcap('dWait');
}
