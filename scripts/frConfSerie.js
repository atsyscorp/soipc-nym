// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfSerie.PHP
//=============================================================
//Función cambio de selcción de sucursal
function Suc_Change()
{
	//Limpia controles
	ctrclen(9, 2);
	//Limpia tabla de consecutivos
	var sTab = document.getElementById("trSerTit").innerHTML;
	InnerCtr('tabSerie', '<table cellpadding="0" cellspacing="0" width="875px"><tr class="bgcol_6 fwhite" id="trSerTit">' + sTab + '</tr></table>');
	//------------------------------------------------------------
	var cbSucur = document.getElementById("tx2");
	if(cbSucur.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta los consecutivos de la sucursal
		var strSQL = "Select * From XConf_Consecutivos Where Sucursal = '" + cbSucur.value + "'";
		InnerCtr('tabSerie', GenConretField('ajax/frConfSerie', 'UpDate_Serie', strSQL, false));
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//------------------------------------------------------
//Funcion para buscar consecutivo dependiendo de documento seleccionado
function Doc_OnChange()
{
	//Limpia controles 
	for(i = 4; i <= 9; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		evtx.value = '';
	}
	var cbDoc = document.getElementById("tx3");
	var cbSucur = document.getElementById("tx2");
	if(cbDoc.value != '' && cbSucur.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Sucursal = '" + cbSucur.value + "' And Documento = '" + cbDoc.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 9, 'XConf_Consecutivos', swhere, 3);	
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//------------------------------------------------
//Clic en listado
function lstfun(scur)
{
	var cbDoc = document.getElementById("tx3");
	var cur = scur.attributes["name"].value;
	cbDoc.value = cur;
	Doc_OnChange();
}
//--------------------------------------
//Funcion para modificar consecutivos
function Modi_Serie()
{
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//String consulta
	var txsuc = document.getElementById("tx2");
	var cbdoc = document.getElementById("tx3");
	var swhere = "Sucursal='" + txsuc.value + "' And Documento='" + cbdoc.value + "'";
	//Funcion generica modificar
	var isuc = genmodif_1('General', 'Gen_Modif', 9, 'XConf_Consecutivos', swhere);
	if(isuc == 10)
	{
		var tabserie = document.getElementById("tabSerie");
		var strSQL = "Select * From XConf_Consecutivos Where Sucursal = '" + txsuc.value + "'";
		tabserie.innerHTML = GenConretField('ajax/frConfSerie', 'UpDate_Serie', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}

