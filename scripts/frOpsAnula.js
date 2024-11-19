// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsAnula.PHP
//=============================================================
//Funcion cambio de selccion tipo de operacion
function cbAOType_Change()
{
	//Limpia controles
	ValueCtr('txAOTable', '');
	InnerCtr('lstAOList', '');
	document.getElementById('frAnula').style.height = '300px';
	//-----------------------------------------------------------	
	var cbType = document.getElementById('tx1');
	if(cbType.value != '' && cbType.value.indexOf('CIERRE') == -1)
	{
		document.getElementById('tx4').disabled = false;
		document.getElementById('tx4').className = 'txboxo';
		//---------------------------------------------------------------
		//Consulta la tabla y formato asociados al tipo de operación
		viscap('dWait');
		var strSQL = "SELECT Distinct Tabla FROM XConf_Consecutivos WHERE Documento = '" + cbType.value + "'";
		ValueCtr('txAOTable', GenConretField('General', 'Gen_Find_Field', strSQL, false));
		hidcap('dWait');
	} else if(cbType.value.indexOf('CIERRE') != -1){
		ValueCtr('tx4', '');
		document.getElementById('tx4').disabled = true;
		document.getElementById('tx4').className = 'txboxdis';
	}
}
//--------------------------------------------------------
//Funcion para anular operacion dependiendo de tipo de movimiento
function cmAnular(sOrigin)
{
	//Valida campos obligatorios
	var cbType = document.getElementById('tx1');
	if(cbType.value.indexOf('CIERRE') == -1)
	{
		if(fEmpty(5, 1) == true) {return 0;}
	}
	//Valida fecha
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	if(cbYear.value == '' || cbMonth.value == '' || cbDay.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la operación que desea anular.", 1);
		return false;
	} 
	//----------------------------------------------
	viscap('dbloc');
	if(sOrigin == 'VENTANILLA')
	{
		switch(cbType.value)
		{
        	case "COMPRA DE DIVISAS":
				AnuOpsVenta('Estado_Operacion', 19, 19, 33, 35, 36, 48, 46, 'Compras');	
				break;
        	case "VENTA DE DIVISAS":
				AnuOpsVenta('Estado_Operacion', 19, 19, 33, 35, 36, 48, 46, 'Ventas');					
				break;
			case "INGRESO":
                AnuOpsVenta('Estado', 18, 18, 11, 12, 13, 15, 14, 'Entradas');
				break;
			case "EGRESO":
                AnuOpsVenta('Estado', 18, 18, 11, 12, 13, 15, 14, 'Salidas');
				break;
			case "PAGOS DESDE VENTANILLA":
                AnuOpsVenta('Estado', 11, 11, 12, 13, 19, 20, 19, 'Salidas');
				break;
			case "CIERRE VENTANILLA":
				//LLama procedimiento para eliminar cierres
                AnuCloseV();
				break;
			default:
		}
	} else if(sOrigin == 'COORDINACION') {
	
	
	
	} else if(sOrigin == 'TESORERIA') {
	
		
	}	
}
//--------------------------------------------------
//Funcion para anular operaciones de ventanilla
function AnuOpsVenta(iAnu, iMsg, iDes, iCur, iPri, iCant, iMed, iValu, sType1)
{
	//Valida que la fecha sea mayor a la del cierre
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	var dnow = new Date(cbYear.value + "-" + cbMonth.value + "-" + cbDay.value);
	//Captura fecha de ultimo cierre desde base de datos por que superusuario puede tambien anular
	var swhere1 = "Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "'";
	var strSQLV = "SELECT MAX(Fecha) FROM Cierres_Ventanilla WHERE " + swhere1;
	var scls = GenConretField('General', 'Gen_Find_Field', strSQLV, false);
	if(scls == '')
	{
		var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	} else {
		var dcls = new Date(scls);
	}
	if(dnow <= dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha de la operación que desea anular, no puede ser menor a la fecha del último cierre.", 1);
		return false;	
	}
	//---------------------------------------------------------------
    //Define string de consulta
    var sField = '';
	if(document.getElementById('txAOTable').value == 'Operacion_Ventanilla')
	{
		sField = "Tipo_Operacion";
	} else {
		sField = "Tipo_Movimiento";
	}	
	var strSQL = "SELECT * FROM " + document.getElementById('txAOTable').value + " WHERE " + sField + " = '" + document.getElementById('tx1').value + "' AND Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "' AND Fecha = '" + cbYear.value + "-" + cbMonth.value + "-" + cbDay.value + "' AND Consecutivo = '" + document.getElementById('tx4').value + "'";
	var sConsO = GenConretField('ajax/frOpsAnula', 'Gen_Find_Anula', strSQL, false);
	if(sConsO == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La operación " + document.getElementById('tx1').value + " con consecutivo " + document.getElementById('tx4').value + " no ha sido encontrada. Verifique los datos de la operación.", 1);
		return false;
	} else {
		//Abre vector
		var resvec = sConsO.split(".|.");
		//Confirmacion de anulacion
		viscap('dbloc');
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); AnuOpsVenta_1('" + iAnu + "', '" + sConsO + "', '" + iDes + "', '" + iCur + "', '" + iPri + "', '" + iCant + "', '" + iMed + "', '" + iValu + "', '" + sType1 + "');", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "Confirma que desea anular la operación de " + document.getElementById('tx1').value + " con consecutivo " + document.getElementById('tx4').value + " con el cliente o por el concepto: " + resvec[iMsg], 1);
	}
}
//-------------------------------------------------
//Continuacion de anular operación
function AnuOpsVenta_1(iAnu, sVec, iDes, iCur, iPri, iCant, iMed, iValu, sType1)
{
	//Cambia estado de operacion
	var resvec = sVec.split(".|.");
    var sField = '';
	var sAnula = GenUpdateField('General', 'Gen_Update_Field', document.getElementById('txAOTable').value, "Identificacion='" + resvec[0] + "'",  iAnu + "='ANULADO" + "'", false);
	//--------------------------------------------
	//Hace registro de observacion de operacion
	var sObs = Obsaccept('General', 'Gen_Accept', 14, 'Comentarios_Clientes', resvec[1], resvec[2], resvec[6])
	//Actualiza tablero de operaciones
	MainTable('ANULACIÓN ' + document.getElementById('tx1').value, document.getElementById('tx4').value, resvec[iDes], resvec[iCur], resvec[iPri], resvec[iCant], resvec[iMed])
	//---------------------------------------------
	//LLama procedimiento para actualizar saldos de caja
	//Define campo de arqueo
	document.getElementById('txAOCur').value = resvec[iCur];
	document.getElementById('txAOCant').value = parseFloat(resvec[iCant]) * (-1);
	ActCaja(2, 3, 'AOCur', sType1, 'AOCant');
	//Si es compra o venta modifica saldod e pesos
	if(sType1 == 'Compras' || sType1 == 'Ventas')
	{
		var sType2 = '';
		if(sType1 == 'Compras'){
			sType2 = 'Salidas';
		} else {
			sType2 = 'Entradas';
		}
		document.getElementById('txAOValu').value = parseFloat(resvec[iValu]) * (-1);
		ActCaja(2, 3, 'AOCOP', sType2, 'AOValu');
	}
	//----------------------------------------------
	//Mensaje anulacion exitosa
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La operación " + document.getElementById('tx1').value + " con consecutivo " + document.getElementById('tx4').value + " ha sido anulada exitosamente.", 1);
}
//---------------------------------------------------
//Funcion para registrar observacion en table
function Obsaccept(sfile, sfun, iparam, stable, sOpsC, sOpsT, sFec)
{
	//-----------------------------------------------
	var funval = '';
	//Construye string de parametros
	var sparam = '';
	//String id de registro
	var sObId = dateid() + "COMNT" + document.getElementById('tx2').value + document.getElementById('tx3').value;
	sparam = "&var1=" + sObId;
	sparam = sparam + "&var2=" + sOpsC + "&var3=" + sOpsT + "&var4=" + document.getElementById('tx2').value + "&var5=" + document.getElementById('tx3').value + "&var6=" + document.getElementById('txAOEmp').value + "&var7=" + sFec + "&var8=" + document.getElementById('tx4').value + "&var9=&var10=&var11=&var12=&var13=Anulación de Operación: " + document.getElementById('tx5').value + "&var14=1";
	sparam = "&var0=" + iparam + sparam;
	//-----------------------------------------------
	var request = null;
	if(window.XMLHttpRequest){
		request = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (request) {
		request.open("GET", sfile + ".php?sFun=" + sfun + sparam + "&stable=" + stable, false);
		request.onreadystatechange = function(){
			if (request.readyState == 4 && request.status == 200) {
				var txms = request.responseText;
				funval = txms;			
			}
		 }
	request.send(null); //esta es la linea para que termine de ejecutar ajax
	} else {
		alert("¡Debe actualizar su navegador para ejecutar algunas funciones!");	
	}
	return funval;	
}
//---------------------------------------------------
//Función para anular cierre de ventanilla
function AnuCloseV()
{
	//Valida que la fecha sea mayor a la del cierre
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	var sfnow = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var dnow = new Date(sfnow);
	//Consulta ultima fecha de cierre por que esto se hace desde coordinacion
	//Valida si existe fecha maxima de cierre
	var swhere1 = "Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "'";
	var strSQLV = "SELECT MAX(Fecha) FROM Cierres_Ventanilla WHERE " + swhere1;
	var scls = GenConretField('General', 'Gen_Find_Field', strSQLV, false);
	if(scls == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No se encontraron cierres de la sucursal " + document.getElementById('tx2').value + " estación " + document.getElementById('tx3').value, 1);
		return false;	
	}
	//Valida que la fecha a anular no sea mayor al ultimo cierre
	var dcls = new Date(scls);
	if(dnow > dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha desde la cual desea anular los cierres, no puede ser mayor a la fecha del último cierre: " + scls, 1);
		return false;	
	}
	//-------------------------------------------------
	//Limpia listview de fechas
    InnerCtr('lstAOList', '');
	//---------------------------------------------------------------
	//Valida que cierre exista. En caso que exista, pregunta confirmación
	var swhere = "Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "' AND Fecha >= '" + sfnow + "'";
	var sValC = GenConret_1('General', 'RegCount', 'Cierres_Ventanilla', swhere, false)
	if(sValC == '0')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No se encontraron cierres de ventanilla desde la fecha " + sfnow + ". Verifique los datos para anular los cierres de ventanilla.", 1);
		return false;
	} else {
		//Confirmacion de anulacion
		viscap('dbloc');
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); AnuCloseV_1('" + sfnow + "');", "hidcap('btcancel" + 1 + "'); hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "Confirma que desea eliminar los cierres de ventanilla desde la fecha " + sfnow, 1);
	}
}
//Continuación eliminación de cierres
function AnuCloseV_1(sfecha)
{
	//Consulta días que se van a abrir y los pone en tabla
	var strSQL = "SELECT Distinct Fecha FROM Cierres_Ventanilla WHERE Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "' AND Fecha >= '" + sfecha + "'";
	InnerCtr('lstAOList', GenConretField('ajax/frOpsAnula', 'List_Cierres', strSQL, false));
	//------------------------------------------------
	//Llama procedimiento para anular cierres
	var strSQLA = "DELETE FROM Cierres_Ventanilla WHERE Sucursal = '" + document.getElementById('tx2').value + "' AND Estacion = '" + document.getElementById('tx3').value + "' AND Fecha >= '" + sfecha + "'";
	var sAnula = GenConretField('ajax/frOpsAnula', 'Anula_Cierres', strSQLA, false);
	//--------------------------------------------
	//Hace registro de observacion de operacion
	var sObs = Obsaccept('General', 'Gen_Accept', 14, 'Comentarios_Clientes', '158', 'CIERRES DE VENTANILLA', sfecha)
	//------------------------------------------------
	//Amplia ventana para ver fechas abiertas
	document.getElementById('frAnula').style.height = '583px';
	//--------------------------------------------------------------------
	//No actualiza fecha ultimo cierre en main por que solo coordinacion puede
	//anular los cierres
	//---------------------------------------------------------------------
	//Mensaje
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Anulación exitosa de cierres desde la fecha " + sfecha, 1);
}
//--------------------------------------
//Funcion para exportar tabla de cierres eliminados
function cmExport_Click(sCompa)
{
	viscap('dWait');
	ExportToHtml('Días Abiertos', sCompa, 'LISTADO FECHAS ABIERTAS', 'lstAOList');
	hidcap('dWait');
}
//----------------------------------------------------
//Funcion para que no entre en error al clic sobre la tabla
function lstfun(sfec){}
