// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsTasas.PHP
//=============================================================
//Función cambio de selcción de sucursal
function Suc_Change()
{
	//Limpia controles
	ctrclen(8, 1);
	//Limpia combos de estaciones y moneda
	InnerCtr('tx2', '<option value=""></option>');
	document.getElementById("chTOTipoBase").checked = false;
	document.getElementById("chTOBase").checked = false;
	//Limpia tabla de tasas
	var sTab = document.getElementById("trTasTit").innerHTML;
	InnerCtr('tabTasas', '<table cellpadding="0" cellspacing="0" width="555px"><tr class="bgcol_6 fwhite" id="trTasTit">' + sTab + '</tr></table>');
	//Deshabilita aceptar y modificar
	disbtn('btmodif');
	disbtn('btaccept');
	//------------------------------------------------------------
	var cbSucur = document.getElementById("tx1");
	if(cbSucur.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta la cantidad de estaciones de la sucursal seleccionada
		var iCajas = GenConretField("General", "Gen_Find_Field", "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal='" + cbSucur.value + "'", false);
		for(i = 1; i <= parseInt(iCajas); i++){
			var sCero = '0';
			if(i > 9){sCero = '';}
			document.getElementById("tx2").options[i] = new Option(sCero + i, sCero + i);
		}
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//----------------------------------------------------
//Función cambio de selcción de caja
function Caja_Change()
{
	//Limpia controles
	for(i = 3; i <= 8; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		evtx.value = '';
	}
	document.getElementById("chTOTipoBase").checked = false;
	document.getElementById("chTOBase").checked = false;
	//Limpia tabla de tasas
	var sTab = document.getElementById("trTasTit").innerHTML;
	InnerCtr('tabTasas', '<table cellpadding="0" cellspacing="0" width="555px"><tr class="bgcol_6 fwhite" id="trTasTit">' + sTab + '</tr></table>');
	//Deshabilita aceptar y modificar
	disbtn('btmodif');
	disbtn('btaccept');
	//------------------------------------------------------------
	var cbSucur = document.getElementById("tx1");
	var cbCaja = document.getElementById("tx2");
	if(cbSucur.value != '' && cbCaja.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta las tasas de la estacion y sucursal
		var strSQL = "Select * From Tasas Where Sucursal = '" + cbSucur.value + "' And Estacion = '" + cbCaja.value + "'";
		InnerCtr('tabTasas', GenConretField('ajax/frOpsTasas', 'UpDate_Tasas', strSQL, false));
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//Funcion para buscar precios de moneda seleccionada
function Moneda_OnChange()
{
	//Limpia controles --> OJO FALTA
	for(i = 4; i <= 8; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		evtx.value = '';
	}
	document.getElementById("chTOTipoBase").checked = false;
	document.getElementById("chTOBase").checked = false;
	var cbMoneda = document.getElementById("tx3");
	var cbSucur = document.getElementById("tx1");
	var cbCaja = document.getElementById("tx2");
	if(cbMoneda.value != '' && cbSucur.value != '' && cbCaja.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Llama función de buscar
		var swhere = "Sucursal = '" + cbSucur.value + "' And Estacion = '" + cbCaja.value + "' And Moneda = '" + cbMoneda.value + "'";
		var iFindSuc = genfind('General', 'Gen_Find', 8, 'Tasas', swhere, 3);	
		if(iFindSuc == 1)
		{
			enabtn('btmodif');
			disbtn('btaccept');
			document.getElementById("chTOTipoBase").checked = false;
			document.getElementById("chTOBase").checked = false;
			document.getElementById("tx4").disabled = true;
			document.getElementById("tx4").className = 'txboxdis';
			document.getElementById("tx5").disabled = true;
			document.getElementById("tx5").className = 'txboxdis';
			//Formato numerico
			document.getElementById("tx5").value = NumFormProp(document.getElementById("tx5").value);
			document.getElementById("tx6").value = NumFormProp(document.getElementById("tx6").value);
			document.getElementById("tx8").value = NumFormProp(document.getElementById("tx8").value);
		} else {
			disbtn('btmodif');
			enabtn('btaccept');
			document.getElementById("chTOTipoBase").checked = true;
			document.getElementById("chTOBase").checked = true;
			document.getElementById("tx4").disabled = false;
			document.getElementById("tx4").className = 'txboxo';
			document.getElementById("tx5").disabled = false;
			document.getElementById("tx5").className = 'txboxo';
		}
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//--------------------------------------
//Clic en listado
function lstfun(scur)
{
	var cbMoneda = document.getElementById("tx3");
	var cur = scur.attributes["name"].value;
	cbMoneda.value = cur;
	Moneda_OnChange();
}
//--------------------------------------
//Cambio de chbox de tipo de base iva
function chTipoBase_Changed()
{
	var chbox = document.getElementById("chTOTipoBase");
	var cbtype = document.getElementById("tx4");
	if(chbox.checked == true) {
		cbtype.disabled = false;
		cbtype.className = 'txboxo';
	} else {
		cbtype.disabled = true;
		cbtype.className = 'txboxdis';
	}
}
//--------------------------------------
//Cambio de chbox de precio base iva
function chBase_Changed()
{
	var chbox = document.getElementById("chTOBase");
	var txtype = document.getElementById("tx5");
	if(chbox.checked == true) {
		txtype.disabled = false;
		txtype.className = 'txboxo';
	} else {
		txtype.disabled = true;
		txtype.className = 'txboxdis';
	}
}
//------------------------------------------------
//Funciones para calcular promedio base IVA
function SelTypeBase()
{
	var cbtype = document.getElementById("tx4");
	var cbcurr = document.getElementById("tx3");
	var txbase = document.getElementById("tx5");
	if(cbtype.value != '' && cbcurr.value != '')
	{
		txbase.value = '0';
		viscap('dbloc');
		viscap('dWait');
		if(cbtype.value == 'SUCURSAL'){
			SelSucur();			
		} else {
			SelEmpresa()		
		}	
		hidcap('dbloc');
		hidcap('dWait');
	}
}
//Seleccion base --> Sucursal
function SelSucur()
{
	var txsuc = document.getElementById("tx1");
	var txcaja = document.getElementById("tx2");
	var cbcurr = document.getElementById("tx3");
	var txbase = document.getElementById("tx5");
	var strSQL = "Select Precio_Base From Tasas Where Sucursal='" + txsuc.value + "' And Estacion='" + txcaja.value + "' And Moneda='" + cbcurr.value + "'";
	var dresult = GenConretField('General', 'Gen_Find_Field', strSQL, false);
	if(dresult != '')
	{
		txbase.value = NumFormProp(dresult);			
	}
}
//Seleccion base Empresa
function SelEmpresa()
{
	var txbase = document.getElementById("tx5");
	var cbcurr = document.getElementById("tx3");
	var dresult = GenConretField('ajax/frOpsTasas', 'Calc_Base', cbcurr.value, false);
	if(dresult != '')
	{
		txbase.value = NumFormProp(dresult);			
	}
}
//-------------------------------------------------------
//Funcion para aceptar tasas
function Accept_Tasas()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Valores txocultos
	var txsuc = document.getElementById("tx1");
	var txcaja = document.getElementById("tx2");
	//Funcion generica aceptar
	var isuc = genaccept('General', 'Gen_Accept', 8, 'Tasas');
	//--------------------------------------------------
	if(isuc == 10)
	{
		var tabtasa = document.getElementById("tabTasas");
		var strSQL = "Select * From Tasas Where Sucursal = '" + txsuc.value + "' And Estacion = '" + txcaja.value + "'";
		tabtasa.innerHTML = GenConretField('ajax/frOpsTasas', 'UpDate_Tasas', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
		disbtn('btaccept');
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//--------------------------------------------------
//Funcion para modificar tasas
function Modi_Tasas()
{
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//String consulta
	var txsuc = document.getElementById("tx1");
	var txcaja = document.getElementById("tx2");
	var cbcur = document.getElementById("tx3");
	var swhere = "Sucursal='" + txsuc.value + "' And Estacion='" + txcaja.value + "' And Moneda='" + cbcur.value + "'";
	//Funcion generica modificar
	var isuc = genmodif('General', 'Gen_Modif', 8, 'Tasas', swhere);
	var imod = genaccept('ajax/frOpsTasas', 'Accept_Mod_Tasas', 8, 'Tasas_Mod');
	if(isuc == 10)
	{
		var tabtasa = document.getElementById("tabTasas");
		var strSQL = "Select * From Tasas Where Sucursal = '" + txsuc.value + "' And Estacion = '" + txcaja.value + "'";
		tabtasa.innerHTML = GenConretField('ajax/frOpsTasas', 'UpDate_Tasas', strSQL, false);
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}

