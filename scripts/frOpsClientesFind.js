// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsClientesFind.PHP
//=============================================================
//Funcion para buscar registros
function cmCOCFind_Click()
{
	//Valida selección de variable
	if(document.getElementById("cbCOCVar").value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); document.getElementById('cbCOCVar').focus()", "", "Seleccione la variable por la cual desea buscar clientes.", 1);
		return false;	
	}	
	//Valida texto
	if(document.getElementById("txCOCFind").value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); document.getElementById('txCOCFind').focus()", "", "Digite el texto o la palabra clave por la cual desea buscar clientes.", 1);
		return false;	
	}	
	//Limpia listado
	var tbList = document.getElementById("lstCOCList");
	var trList = document.getElementById("trCoCabe");
	tbList.innerHTML = '<tr id="trCoCabe" class="bgcol_6 fwhite">' + trList.innerHTML + '</tr>';
	document.getElementById("txCOCReg").value = "0";
	document.getElementById("txCOCTotal").value = "0";
	document.getElementById("IdCli").value = '';
	//---------------------------------------------
	viscap('dWait');		
	//Primero consulta cantidad de registros
	var strSQL = "SELECT * FROM Clientes WHERE " + document.getElementById("cbCOCVar").value + " RLIKE '^" + document.getElementById("txCOCFind").value + "'";
	var swhere = document.getElementById("cbCOCVar").value + " RLIKE '^" + document.getElementById("txCOCFind").value + "'"; 
	var clicon = GenConret_1('General', 'RegCount', 'Clientes', swhere, false);
	if(clicon != ''){document.getElementById("txCOCTotal").value = clicon;} 
	//Consulta tabla
	tbList.innerHTML = tbList.innerHTML + GenConretField('ajax/frOpsClientes', 'Find_Client', strSQL, false);
	hidcap('dWait');		
}
//Enter texto hace consulta
function txFind_Enter(txid, e)
{
	var code = e.keyCode;
	if (code == 13) {
		cmCOCFind_Click();	
	}
}
//----------------------------------------------------------
//Seleccion item de listado
function lstfun(sact)
{
	var sid = sact.attributes["name"].value;
	var srow = sact.attributes["id"].value;
	var snum = srow.replace("row", "");
	//------------------------------------------------
	//Cambia clase de fila seleccionada
	var k = 0;
	var itol = parseInt(document.getElementById("txCOCTotal").value);
	for(i = 1; i <= itol; i++){
		if(i == parseInt(snum))
		{
			document.getElementById(srow).className = 'bgcol_6 fwhite';		
		} else {
			if(k == 0)
			{
				document.getElementById('row' + i).className = 'fcont trwhite';
				k = 1;			
			} else {
				document.getElementById('row' + i).className = 'fcont trgray';
				k = 0;			
			}
		}
	}
	//Pone numero de fila en campo
	document.getElementById("txCOCReg").value = snum;
	//Pone id de cliente en tx oculto para enviar
	document.getElementById("IdCli").value = sid;
}
//-----------------------------------------------
//Funcion Enviar id a declarante
function cmCOCSendD_Click()
{
	if(document.getElementById("IdCli").value != '')
	{
		window.parent.document.getElementById('tx2').value = document.getElementById("IdCli").value;
		window.parent.document.getElementById('dfrBen').style.visibility = 'hidden';
		window.parent.document.getElementById('dfrFind').style.visibility = 'hidden';
		window.parent.document.getElementById('btfind').click();
	} else {
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione del listado, el cliente que desea ver en alguna de las pestañas.", 1);
	}
}
//Envar id a beneficiario
function cmCOCSendB_Click()
{
	//Valida seleccion de registro
	if(document.getElementById("IdCli").value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione del listado, el cliente que desea ver en alguna de las pestañas.", 1);
		return false
	}
	//Valida pestaña beneficiario habilitada
	if(window.parent.document.getElementById('btben').className == 'btcontdis')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La pestaña de beneficiario se encuentra deshabilitada y no puede enviar datos.", 1);
		return false
	}	
	//Envía datos
	window.parent.document.getElementById('frBen').contentWindow.document.getElementById('tx2').value = document.getElementById("IdCli").value;
	window.parent.document.getElementById('dfrBen').style.visibility = 'visible';
	window.parent.document.getElementById('dfrFind').style.visibility = 'hidden';
	window.parent.document.getElementById('frBen').contentWindow.document.getElementById('btfind').click();
}
//----------------------------------------------------
//Funcion para exportar tabla
function cmCOCExport_Click(sCompa)
{
	viscap('dWait');
	ExportToHtml('Clientes', sCompa, 'LISTADO DE CLIENTES', 'dCOCList');
	hidcap('dWait');
}