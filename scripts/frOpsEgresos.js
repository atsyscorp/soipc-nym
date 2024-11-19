// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsEgresos.PHP
//=============================================================
//Funcion para load carga fecha
function frOpsEgresos_Load()
{
	document.getElementById("tx7").value = hoyday();
}
//Funcion para deshabilitar botones
function DisBtn()
{
	disbtn('btprint');
	disbtn('btgobil');
}

//Funcion para consultar informacion de tercero
function FindTer()
{
	//Deshabilita controles imprimir y cheque
	DisBtn();
	//-------------------------------------------------
	var txterid = document.getElementById('tx9');
	var txterna = document.getElementById('tx10');
	txterna.value = '';
	if(txterid.value != '')
	{
		var strSQL = "Select Nombre From Terceros Where Identificacion='" + txterid.value + "'";
		txterna.value = GenConretField('General', 'Gen_Find_Field', strSQL, false);
	}	
}
//--------------------------------------------
//Funcion cambio de seleccion medio de pago
function cbEVMedPay_Change()
{
	DisBtn();
	var cbMedPay = document.getElementById('tx21');
	if(cbMedPay.value != '')
	{
		viscap('dWait');
		//Limpia controles	
		InnerCtr('tx22', '<option value=""></option>');
		InnerCtr('tx23', '<option value=""></option>');
		InnerCtr('tx24', '<option value=""></option>');
		document.getElementById('tx25').value = '';
		//Carga opciones de medio de pago
		var cbIns = document.getElementById("tx22");
		var strSQL = "SELECT Instrumento FROM XConf_MediosPago WHERE Medio_Pago = '" + cbMedPay.value + "'";
		cbIns.innerHTML = cbIns.innerHTML + GenConretField('ajax/frOpsEgresos', 'UpDate_MedPay', strSQL, false);
		if(cbMedPay.value == 'EFECTIVO')
		{
			document.getElementById("tx23").disabled = true;
			document.getElementById("tx23").className = 'txboxdis';
			document.getElementById("tx24").disabled = true;
			document.getElementById("tx24").className = 'txboxdis';
			document.getElementById("tx25").disabled = true;
			document.getElementById("tx25").className = 'txboxdis';
		} else {
			document.getElementById("tx23").disabled = false;
			document.getElementById("tx23").className = 'txboxo';
			document.getElementById("tx24").disabled = false;
			document.getElementById("tx24").className = 'txboxo';
			document.getElementById("tx25").disabled = false;
			document.getElementById("tx25").className = 'txboxo';
			//Carga opciones de medio de pago
			var cbBak = document.getElementById("tx23");
			var strSQLB = "SELECT DISTINCT Banco FROM Cuentas_Bancarias";
			cbBak.innerHTML = cbBak.innerHTML + GenConretField('ajax/frOpsEgresos', 'UpDate_MedPay', strSQLB, false);
		}
		hidcap('dWait');
	}
}
//--------------------------------------------
//Funcion cambio de selección banco
function cbEVBank_Change()
{
	DisBtn();
	var cbBank = document.getElementById('tx23');
	//Limpia controles cuentas
	InnerCtr('tx24', '<option value=""></option>');
	if(cbBank.value != '')
	{
		var cbCon = document.getElementById("tx24");
		var strSQL = "SELECT Numero_Cuenta FROM Cuentas_Bancarias WHERE Banco = '" + cbBank.value + "'";
		cbCon.innerHTML = cbCon.innerHTML + GenConretField('ajax/frOpsEgresos', 'UpDate_MedPay', strSQL, false);
	}
}
//-----------------------------------------
//Funcion para liquidar valores
function txCalc(stx)
{
	var txSend = document.getElementById(stx);
	var txSub = document.getElementById('tx15');
	var txIva = document.getElementById('tx16');
	var cbIva = document.getElementById('cbEVIva');
	var txFte = document.getElementById('tx17');
	var cbFte = document.getElementById('cbEVRteFte');
	var txIca = document.getElementById('tx18');
	var cbIca = document.getElementById('cbEVRteIca');
	var txRiv = document.getElementById('tx19');
	var cbRiv = document.getElementById('cbEVRteIva');
	var txTot = document.getElementById('tx20');
	//-----------------------------------------------
	DisBtn();	
	if(isNaN(DelMilsepa(txSub.value)) == false && isNaN(DelMilsepa(txIva.value)) == false && isNaN(DelMilsepa(txFte.value)) == false && isNaN(DelMilsepa(txIca.value)) == false && isNaN(DelMilsepa(txRiv.value)) == false)
	{
		if(stx == 'tx15')
		{
			if(cbIva.value != ''){txIva.value = Math.round((parseFloat(cbIva.value) / 100) * parseInt(DelMilsepa(txSub.value))); txChange_Num('tx16');}
			if(cbFte.value != ''){txFte.value = Math.round((parseFloat(cbFte.value) / 100) * parseInt(DelMilsepa(txSub.value))); txChange_Num('tx17');}
			if(cbIca.value != ''){txIca.value = Math.round((parseFloat(cbIca.value) / 100) * parseInt(DelMilsepa(txSub.value))); txChange_Num('tx18');}
			if(cbRiv.value != ''){txRiv.value = Math.round((parseFloat(cbRiv.value) / 100) * parseInt(DelMilsepa(txIva.value))); txChange_Num('tx19');}
		} else if(stx == 'tx16') {
            if(cbRiv.value != ''){txRiv.value = Math.round((parseFloat(cbRiv.value) / 100) * parseInt(DelMilsepa(txIva.value))); txChange_Num('tx19');}
		}
        txTot.value = parseFloat(DelMilsepa(txSub.value)) + parseFloat(DelMilsepa(txIva.value)) - DelMilsepa(txFte.value) - DelMilsepa(txIca.value) - DelMilsepa(txRiv.value);
		txChange_Num('tx20');
	} else {
		txSend.value = '0';
	}
}
//-------------------------------
//Funcion para liquidar impuestos en cambio de tasa
function TaxCalc(scbtax, stxtax, stxres)
{
	var cbtax = document.getElementById(scbtax);
	var txtax = document.getElementById(stxtax);
	var txres = document.getElementById(stxres);
	//--------------------------------------------------
	DisBtn();	
	if(cbtax.value != '')
	{
		txtax.value = Math.round((parseFloat(cbtax.value) / 100) * parseInt(DelMilsepa(txres.value)));
		txCalc(stxtax);
		txChange_Num(stxtax);
	}
}
//---------------------------------------
//Funcion Aceptat egreso
function Accept_Egreso()
{
	//Validaciones
	//Fecha de cierre
	var dnow = new Date(document.getElementById('tx7').value);
	var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	if(dnow <= dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha de la operación no puede ser menor o igual a la fecha del último cierre.", 1);
		return false;	
	}
	//Campos vacíos
	if(fEmpty(27, 1) == true) {return 0;}
	//Medio de pago bancos --> Que haya seleccionado las opciones de banco
	if(document.getElementById('tx21').value == "BANCOS" && (document.getElementById('tx22').value == '' || document.getElementById('tx23').value == '' || document.getElementById('tx24').value == '' || document.getElementById('tx25').value == '')){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el medio de pago es BANCOS, debe indicar el instrumento, nombre del banco, cuenta y número de cheque.", 1);
		return false;	
	}
	//----------------------------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//-----------------------------------------------------------------
	//Actualiza consecutivo de operación por si entro otra antes
	document.getElementById('tx8').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '144'", false);
	//-----------------------------------------------------------------
    //Construcción de variables pendientes
    //Id de operacion
    document.getElementById('tx1').value = dateid() + "EV" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx8').value
    //-----------------------------------------------------------------
    //Ingresa operación a base de datos y envío Web
	var isuc = genaccept('General', 'Gen_Accept', 27, 'Egresos_Ventanilla');
    //-----------------------------------------------------------------
	if(isuc == 10)
	{
		//-----------------------------------------------------------------
        //Hace registro de tercero en base de datos
		var iter = GenConretField('ajax/frOpsEgresos', 'RegTercero', document.getElementById('tx9').value + '.|.' + document.getElementById('tx10').value, false);
		//-----------------------------------------------------------------
		//Actualiza tablero y saldos en main
		//Tablero
        MainTable(document.getElementById('tx3').value, document.getElementById('tx8').value, document.getElementById('tx12').value, document.getElementById('tx13').value, document.getElementById('tx14').value, document.getElementById('tx20').value, document.getElementById('tx21').value)
		//-----------------------------------------------------------
		//Actualiza saldo en arqueo rapido	
		ActCaja(4, 5, 13, 'Salidas', 20);
		//-----------------------------------------------------------------
		//Activa botón imprimir y hace pregunta al usuario. En caso afirmativo, ejecuta procedimiento
        enabtn('btprint');
		//----------------------------------------------------------------
		//Mensaje imprimir
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Egr_Print('1')", "Egr_Print('0')", "El egreso de ventanilla número " + document.getElementById('tx8').value + " se ha registrado exitosamente. ¿Desea imprimir el Comprobante de Egreso?", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//----------------------------------------------------
//Funcion continuacion de aceptar
function Egr_Print(iPrint)
{
	//-------------------------------------------------
	//Oculta mensaje y manda impresion
	hidcap('dMsj1');
	hidcap('btcancel1');
	if(iPrint == 1)
	{
		GenPrint("EGRESO", document.getElementById('tx1').value, 1);	
	}
	//------------------------------------------------
	//Suma unidad en consecutivo
	document.getElementById('tx8').value = GenConretField('General', 'AddSerie', '144' + document.getElementById('tx4').value, false);
	//----------------------------------------------------------------------
	//Si el medio de pago es cheque muestra mensaje para imprimir cheque
	if(document.getElementById('tx22').value == "CHEQUE")
	{
        enabtn('btgobil');
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "GoToCheque('1'); hidcap('dbloc')", "GoToCheque('0'); hidcap('dMsj1'); hidcap('btcancel1'); hidcap('dbloc')", "Está realizando un pago con cheque. ¿Desea ir a la ventana de impresión de cheques?", 1);
	} else {
		hidcap('dbloc');
	}
}
//-------------------------------------------
//Funcion para abrir ventana de cheque
function GoToCheque(iGo)
{
	if(iGo == 1)
	{
		var dfifr = window.parent.document.getElementById('frMain');
		//----------------------------------------------------------
		//Cambia la direccion del frame
		dfifr.src = 'frImprCheque.php?var1=' + document.getElementById('tx1').value;
	}
}
//-------------------------------------
//Clic en boton para imprimir
function cmEVPrint_Click()
{
		GenPrint("EGRESO", document.getElementById('tx1').value, 1);	
		disbtn('btprint');
}