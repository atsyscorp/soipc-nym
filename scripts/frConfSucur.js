// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConfSucur.PHP
//=============================================================
//--------------------------------------------
//Funcion digitacion de codigo sucursal
function txId_Change()
{
	//Limpia controles
	ctrclen(15, 1);
	//Deshabilita botones
	disbtn('btmodif');
	disbtn('btdelete');
	enabtn('btaccept');
	//--------------------------------------
	var txId = document.getElementById('tx1');
	if(txId.value != ''){
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Codigo_Sucursal = '" + txId.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 15, 'Sucursales', swhere, 1);	
		if(iFindSuc == 1)
		{
			//Deshabilita botones
			enabtn('btmodif');
			enabtn('btdelete');
			disbtn('btaccept');
			//Pone fecha en controles
			var vFac = document.getElementById('tx11').value.split("-"); 	
			document.getElementById('cbYear').value = vFac[0];	
			document.getElementById('cbMonth').value = vFac[1];	
			getmdays();
			document.getElementById('cbDay').value = vFac[2];	
		}
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(ssuc)
{
	var txId = document.getElementById("tx1");
	var suc = ssuc.attributes["name"].value;
	txId.value = suc;
	txId_Change();
}
//-----------------------------------------
//Funcion para poner valores en campo ocultos de consucutivos
function Fill_Seri(scod, sdoc, sini, sform, stab)
{
	document.getElementById("txB1").value = scod + document.getElementById("tx1").value;
	document.getElementById("txB2").value = document.getElementById("tx1").value;
	document.getElementById("txB3").value = sdoc;
	document.getElementById("txB4").value = scod;
	document.getElementById("txB6").value = document.getElementById("tx10").value;
	document.getElementById("txB7").value = sini;
	document.getElementById("txB8").value = sform;
	document.getElementById("txB9").value = stab;
}
//-----------------------------------------
//Funcion para aceptar usuario
function Sucur_Accept()
{
	//Valida campos obligatorios
	if(fEmpty(15, 1) == true) {return 0;}
	//Valida fecha resolución
	var cbYear = document.getElementById("cbYear");
	var cbMonth = document.getElementById("cbMonth");
	var cbDay = document.getElementById("cbDay");
	if(cbYear.value == '' || cbMonth.value == '' || cbDay.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la resolución de facturación.", 1);
		return false;
	} 
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Construye string de fecha
	document.getElementById("tx11").value = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	//--------------------------------------------------
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 15, 'Sucursales');
	//--------------------------------------------------
	if(isuc == 10)
	{
		// Crea registro en configuración contable
		document.getElementById("txA1").value = document.getElementById("tx1").value;
		document.getElementById("txA4").value = document.getElementById("tx13").value;
		document.getElementById("txA5").value = document.getElementById("tx9").value;
		document.getElementById("txA6").value = document.getElementById("tx11").value;
		var icon = genaccept_1('General', 'Gen_Accept', 6, 'Configuracion_Contable', 'A');
		//----------------------------------------------
		//Crea registro en consecutivos
		//Compra
		Fill_Seri('140', 'COMPRA DE DIVISAS', document.getElementById("tx12").value, 'RECIBO', 'OPERACION_VENTANILLA');
		var icom = genaccept_1('General', 'Gen_Accept', 9, 'XConf_Consecutivos', 'B');
		//Venta
		Fill_Seri('141', 'VENTA DE DIVISAS', document.getElementById("tx12").value, 'FACTURA', 'OPERACION_VENTANILLA');
		var iven = genaccept_1('General', 'Gen_Accept', 9, 'XConf_Consecutivos', 'B');
		//Ingreso
		Fill_Seri('142', 'INGRESO', '1', 'TRASLADO', 'TRASLADOS_VENTANILLA');
		var iing = genaccept_1('General', 'Gen_Accept', 9, 'XConf_Consecutivos', 'B');
		//Egreso
		Fill_Seri('143', 'EGRESO', '1', 'TRASLADO', 'TRASLADOS_VENTANILLA');
		var iegr = genaccept_1('General', 'Gen_Accept', 9, 'XConf_Consecutivos', 'B');
		//Pagos
		Fill_Seri('144', 'PAGOS DESDE VENTANILLA', '1', 'EGRESO', 'EGRESOS_VENTANILLA');
		var ipag = genaccept_1('General', 'Gen_Accept', 9, 'XConf_Consecutivos', 'B');
		//----------------------------------------------
		//Actualiza tabla
		var tabuser = document.getElementById("tabSucur");
		var strSQL = "SELECT * FROM Sucursales";
		tabuser.innerHTML = GenConretField('ajax/frConfSucur', 'UpDate_Sucur', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos. Recuerde cambiar la configuración contable y consecutivos de la sucursal.", 1);
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
function Modi_Sucur()
{
	//Valida campos obligatorios
	if(fEmpty(15, 1) == true) {return 0;}
	//Valida fecha resolución
	var cbYear = document.getElementById("cbYear");
	var cbMonth = document.getElementById("cbMonth");
	var cbDay = document.getElementById("cbDay");
	if(cbYear.value == '' || cbMonth.value == '' || cbDay.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la resolución de facturación.", 1);
		return false;
	} 
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Construye string de fecha
	document.getElementById("tx11").value = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	//--------------------------------------------------
	var swhere = "Codigo_Sucursal='" + document.getElementById("tx1").value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 15, 'Sucursales', swhere);
	if(isuc == 10)
	{
		var tabuser = document.getElementById("tabSucur");
		var strSQL = "SELECT * FROM Sucursales";
		tabuser.innerHTML = GenConretField('ajax/frConfSucur', 'UpDate_Sucur', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos. Recuerde verificar la configuración contable y consecutivos de la sucursal.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//-------------------------------------
//Funcion para eliminar sucursal
function Del_Sucur()
{
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); Del_Sucur_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea eliminar la sucursal " + document.getElementById("tx1").value + "?", 1);
}
//Continuación eliminar
function Del_Sucur_1()
{
	var swhere = "Codigo_Sucursal = '" + document.getElementById("tx1").value + "'";	
	var isuc = GenConret_1('General', 'Gen_Delete', 'Sucursales', swhere, false);
	if(isuc == 10)
	{
		//Elimina registro de configuracion contable
		var swhereS = "Sucursal = '" + document.getElementById("tx1").value + "'";	
		var icon = GenConret_1('General', 'Gen_Delete', 'Configuracion_Contable', swhereS, false);
		//--------------------------------------------
		//Elimina registros de consecutivos
		var icon = GenConret_1('General', 'Gen_Delete', 'xconf_consecutivos', swhereS, false);
		//--------------------------------------------
		//Actualiza tabla
		var tabuser = document.getElementById("tabSucur");
		var strSQL = "SELECT * FROM Sucursales";
		tabuser.innerHTML = GenConretField('ajax/frConfSucur', 'UpDate_Sucur', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha eliminado exitosamente en la base de datos. También se han eliminado los registros de Configuración Contable y Consecutivos.", 1);
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