// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfSegmentos.PHP
//=============================================================
//Funcion digitacion nombre de segmento
function txId_Change()
{
	//Limpia controles
	ctrclen(2, 1);
	//Deshabilita botones
	disbtn('btdelete');
	enabtn('btaccept');
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Segmento = '" + txId.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 2, 'Segmentos_Mercado', swhere, 1);	
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
function lstfun(sseg)
{
	var txId = document.getElementById("tx1");
	var segm = sseg.attributes["name"].value;
	txId.value = segm;
	txId_Change();
}
//--------------------------------------
//Funcion para aceptar usuario
function Segment_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(2, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 2, 'Segmentos_Mercado');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabseg = document.getElementById("tabSegs");
		var strSQL = "Select * From Segmentos_Mercado";
		tabseg.innerHTML = GenConretField('ajax/frConfSegmentos', 'UpDate_Segs', strSQL, false);
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
//Funcion para eliminar segmento
function Del_Seg()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Seg_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar el segmento de mercado " + document.getElementById("tx1").value + "?", 1);
}
//Continuación eliminar
function Del_Seg_1()
{
	var swhere = "Segmento = '" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'Segmentos_Mercado', swhere, false);
	if(isuc == 10)
	{
		var tabseg = document.getElementById("tabSegs");
		var strSQL = "Select * From Segmentos_Mercado";
		tabseg.innerHTML = GenConretField('ajax/frConfSegmentos', 'UpDate_Segs', strSQL, false);
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

