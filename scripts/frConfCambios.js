// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfCambios.PHP
//=============================================================
//Carga de ventana
function frConfCambios_Load(sCur)
{
	//Selecciona moneda de referencia
	var cbcur = document.getElementById('tx4');
	cbcur.value = sCur;
}
//------------------------------------------------
function txCurrC_Change()
{
	//Deshabilita botones
	disbtn('btdelete4');
	disbtn('btaccept4');
	//-------------------------------
	var curr = document.getElementById('txA1');
	var ccode = document.getElementById('txA2');
	var cname = document.getElementById('txA3');
	ValueCtr('txA2', '');
	ValueCtr('txA3', '');
	//----------------------------------------
	if(curr.value != ''){
		
		var strSQL = "SELECT Codigo FROM XConf_Monedas WHERE Moneda='" + curr.value + "'";
		ValueCtr('txA2', GenConretField('General', 'Gen_Find_Field', strSQL, false));
		var strSQ1 = "SELECT Nombre FROM XConf_Monedas WHERE Moneda='" + curr.value + "'";
		ValueCtr('txA3', GenConretField('General', 'Gen_Find_Field', strSQ1, false));
		if(ccode.value != ''){
			enabtn('btdelete4');
			disbtn('btaccept4');
		} else {
			disbtn('btdelete4');
			enabtn('btaccept4');
		}
	}
}
//------------------------------------------------
//Funcion para consultar campo de monedas y numerales
function FindConf(txf, txl, ibta, ibtd, strField)
{
	//Deshabilita botones
	disbtn('btaccept' + ibta);
	disbtn('btdelete' + ibtd);	
	//-------------------------------------------------
	var txfield = document.getElementById(txf);
	var txfound = document.getElementById(txl);
	txfound.value = '';
	if(txfield.value != '')
	{
		var strSQL = strField + "'" + txfield.value + "'";
		txfound.value = GenConretField('General', 'Gen_Find_Field', strSQL, false);
		//-----------------------------------
		//Habilita o deshabilita botones
		if(txfound.value != ''){
			disbtn('btaccept' + ibta);
			enabtn('btdelete' + ibtd);
		} else {
			enabtn('btaccept' + ibta);
			disbtn('btdelete' + ibtd);
		}
	}	
}
//-------------------------------------------------
//Funcion guardar cambios configuracion cambiaria
function Save_Clic()
{
	//Valida campos obligatorios
	if(fEmpty(7, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); Save_Clic_1();", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "¿Desea guardar los cambios en la información general de la Configuración Cambiaria?", 1);
}
function Save_Clic_1()
{
	viscap('dWait');
	//----------------------------------------------------
	var swhere = "Codigo_UIAF='" + document.getElementById("CodUiaf").value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 7, 'Configuracion_Cambiaria', swhere);
	if(isuc == 10)
	{
		//Cambia codigo uiaf oculto
		document.getElementById("CodUiaf").value = document.getElementById("tx2").value;
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------------------------
//Funcion para eliminar registro
function Del_Reg(txf, sfield, stable, txctr, ibtd)
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Reg_1('" + txf + "', '" + sfield + "', '" + stable + "', '" + txctr + "', '" + ibtd + "')", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar permanentemente el registro " + document.getElementById(txf).value + "?", 1);
}
//Continuación eliminar
function Del_Reg_1(txf, sfield, stable, txctr, ibtd)
{
	viscap('dWait');
	var swhere = sfield + " = '" + document.getElementById(txf).value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', stable, swhere, false);
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha eliminado exitosamente en la base de datos.", 1);
		//Limpia controles
		ValueCtr(txf, '');
		ValueCtr(txctr, '');
		//Deshabilita eliminar
		disbtn('btdelete' + ibtd);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//----------------------------------------------
//Funcion para aceptar registros
function Reg_Accept(txctr, stable, smar, ibta, iregs)
{
	//Valida campos obligatorios
	viscap('dbloc');
	var ctr =  document.getElementById(txctr);
	if(ctr.value == ''){
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", 'Los campos con fondo gris son obligatorios.', 1);
		ctr.focus();
		return false;
	}
	//---------------------------------------------------
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept_1('General', 'Gen_Accept', parseInt(iregs), stable, smar);
	//--------------------------------------------------
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
		disbtn('btaccept' + ibta);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
