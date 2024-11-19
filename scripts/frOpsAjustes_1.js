// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsAjustes_1.PHP
//=============================================================
//Funcion para load de ventana
function frOpsAjustes_Load()
{
	//Fecha de ajuste
	ValueCtr('tx7', hoyday());
}
//----------------------------------------
//Funcion aceptar ajuste
function Accept_Ajuste(sIdOrigen)
{
	//Validaciones
	//campos obligatorios
	if(fEmpty(19, 1) == true) {return 0;}
	//Fecha de cierre
	var dnow = new Date(document.getElementById('tx7').value);
	var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	if(dnow <= dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha del ajuste no puede ser menor o igual a la fecha del último cierre.", 1);
		return false;	
	}
	//----------------------------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//-----------------------------------------------------------------
	//Actualiza consecutivo de operación por si entro otra antes
	//document.getElementById('tx8').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '" + document.getElementById('tx2').value + "'", false);
	var sConsM = GenConretField('General', 'AddSerieNew', document.getElementById('tx2').value + document.getElementById('tx4').value, false);
	document.getElementById('tx8').value = sConsM;
	//-----------------------------------------------------------------
    //Construcción de variables pendientes
    //Id de operacion
    document.getElementById('tx1').value = dateid() + "AR" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx8').value
    //-----------------------------------------------------------------
    //Ingresa operación a base de datos y envío Web
	var isuc = genaccept('General', 'Gen_Accept', 19, 'Traslados_Ventanilla');
	//Modifica registro temporal a Aceptado
	var sUpEst = GenUpdateField('General', 'Gen_Update_Field', 'Traslados_Ventanilla_Temp', "Identificacion='" + sIdOrigen + "'", "Estado='ACEPTADO'", false);
	//--------------------------------------------------------------------
	if(isuc == 10)
	{
		//-----------------------------------------------------------------
		//Actualiza tablero y saldos en main
		//Tablero --> OJO VOY ACA
        MainTable(document.getElementById('tx3').value, document.getElementById('tx8').value, document.getElementById('tx3').value + " de recursos de/para: " + document.getElementById('tx10').value, document.getElementById('tx12').value, document.getElementById('tx13').value, document.getElementById('tx14').value, document.getElementById('tx16').value)
		//-----------------------------------------------------------
		ActCaja(4, 5, 12, 'Entradas', 14);
		//-----------------------------------------------------------------
		//Activa botón imprimir y hace pregunta al usuario. En caso afirmativo, ejecuta procedimiento
        enabtn('btprint');
        disbtn('btcancel');
		disbtn('btaccept');
		//----------------------------------------------------------------
		//Mensaje imprimir
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Aju_Print('1')", "Aju_Print('0')", "El ajuste de recursos número " + document.getElementById('tx8').value + " se ha registrado exitosamente. ¿Desea imprimir el formato del ajuste?", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//----------------------------------------------------
//Funcion continuacion de aceptar
function Aju_Print(iPrint)
{
	//-------------------------------------------------
	//Oculta mensaje y manda impresion
	hidcap('dMsj1');
	hidcap('btcancel1');
	if(iPrint == 1)
	{
        disbtn('btprint');
		GenPrint("TRASLADO", document.getElementById('tx1').value, 2);	
	}
	//------------------------------------------------
	//Suma unidad en consecutivo
	document.getElementById('tx8').value = GenConretField('General', 'AddSerie', document.getElementById('tx2').value + document.getElementById('tx4').value, false);
	//----------------------------------------------------------------------
	hidcap('dbloc');
}
//--------------------------------
//Funcion clic en imprimir
function cmPrint_Clic()
{
	disbtn('btprint');
	GenPrint("TRASLADO", document.getElementById('tx1').value, 2);	
}
//------------------------------------------------
//Funcion para cancelar ajuste
function Cancel_Ajuste(sIdOrigen)
{
	viscap('dbloc');
	//Confirmación para cancelar traslado
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Cancel_Ajuste_1('" + sIdOrigen + "')", "hidcap('dMsj" + 1 + "'); hidcap('btcancel1'); hidcap('dbloc')", "¿Confirma que desea cancelar el traslado desde " + document.getElementById('tx10').value + "? Haga clic en <b>Aceptar</b> para cancelar el traslado.", 1);
}
//Continuación de cancelar
function Cancel_Ajuste_1(sIdOrigen)
{
	//Modifica registro temporal a Aceptado
	var sUpEst = GenUpdateField('General', 'Gen_Update_Field', 'Traslados_Ventanilla_Temp', "Identificacion='" + sIdOrigen + "'", "Estado='CANCELADO'", false);
	if(sUpEst == 1)
	{
        disbtn('btcancel');
		disbtn('btaccept');
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El traslado se ha cancelado exitosamente.", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", sUpEst, 1);
	}
}