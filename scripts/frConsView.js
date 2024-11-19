// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frConsView.PHP
//=============================================================
//Procedimiento para tamaño tabla reporte
function TabSize()
{
	var dfifr = window.parent.document.getElementById('frMain');
	var sfrh = dfifr.style.height;
	var tota = document.getElementById('dTot_Tab');
	var toth = (parseInt(sfrh.replace("px", "")) - 120) + "px";
	tota.style.height = toth;
}
function TabSize_1()	//Alto de contenedor de tabla de consulta
{
	var dfifr = window.parent.document.getElementById('frTot_Tab');
	var sfrh = dfifr.clientHeight;
	var tota = document.getElementById('dRegi_Table');
	var toth = (parseInt(sfrh) - 140) + "px";
	tota.style.height = toth;
}
//----------------------------------
//Funcion seleccion de tab de consulta
function Sel_TabC(dobj)
{
	var dcri = document.getElementById('dCriConsulta');
	var dava = document.getElementById('dAvanzadas');
	var dcriT = document.getElementById('dCriConsultaT');
	var davaT = document.getElementById('dAvanzadasT');
	dcri.className = 'bttab_1 dsition_4';
	dava.className = 'bttab_1 dsition_4';
	dcriT.style.display = 'none';
	davaT.style.display = 'none';
	//---------------------------------------
	dobj.className = 'bttabsel_1 dsition_4';
	document.getElementById(dobj.id + 'T').style.display = 'block';
}
//-----------------------------------
//Funcion seleccion de fila / registr
function Sel_RowC(dobj)
{
	var dtab = document.getElementById('thRegs');
	var drow = dtab.getElementsByTagName('tr');
	//-------------------------------
	//limpia todos los controles
	for(i = 0; i <= drow.length-1; i++){
		drow[i].className = drow[i].className.replace(' trselect', ''); 
	}
	//---------------------------------
	//Estilo
	dobj.className = dobj.className + ' trselect'; 
	//----------------------------------
	//Pone nombre de seleccionado en input de registro visible y oculto
	var tselrV = window.parent.document.getElementById('txReg');
	var tselrH = window.parent.document.getElementById('txCelRow');
	if(tselrV != null){
		tselrV.value = dobj.id.replace('tr-', '');
		tselrH.value = dobj.id;	
	}
}
//--------------------------------
// Función selección campo ordenación --> Tipo obligatorio
function Sel_OrderC()
{
	var cbord = document.getElementsByName('cbOrder')[0];	
	var cbtyp = document.getElementsByName('cbOrderType')[0];	
	if(cbord.value != ''){
		cbtyp.className = 'txboxo';
	} else {
		cbtyp.className = 'txbox';
	}
}
//-------------------------------------------------------
//Funcion para exportar reporte
function cmExport_Clic()
{
	var frTot = document.getElementById('frTot_Tab');
	var htmlTot = window.open(frTot.src + "&sExp=Exportar", "_blank");
}
//--------------------------------
//Funcion de ampliar registro
function AmpRegist()
{
	var dsel = document.getElementById('txCelRow');
	var dvie = document.getElementById('dRegAmpV');
	if(dsel.value != ''){
		dvie.innerHTML = '';	
		var trsel = window.frames['frTot_Tab'].document.getElementById(dsel.value);
		var trhea = window.frames['frTot_Tab'].document.getElementById('thHead');
		var icel = trsel.getElementsByTagName('td');
		var ihea = trhea.getElementsByTagName('td');
		for(i = 0; i <= icel.length-1; i++){
			dvie.innerHTML = dvie.innerHTML + '<b>' + ihea[i].innerHTML + ':</b> ' + icel[i].innerHTML + '<br />';
		}
		viscap('dAmpReg');
	}
}
//Siguiente
function AmpRegist_Sig()
{
	var dsel = document.getElementById('txCelRow');
	var dvie = document.getElementById('dRegAmpV');
	var tabregs = window.frames['frTot_Tab'].document.getElementById('thRegs');
	var irow = tabregs.getElementsByTagName('tr');
	isel = dsel.value.replace('tr-','');
	iselm = parseInt(isel) + 1;
	if(iselm > irow.length){
		isela = 1;		
	} else {
		isela = iselm;
	}
	//--------------------------------
	//limpia todos los controles
	for(i = 0; i <= irow.length-1; i++){
		irow[i].className = irow[i].className.replace(' trselect', ''); 
	}
	//---------------------------------
	//Estilo
	irow[parseInt(isela) - 1].className = irow[parseInt(isela) - 1].className + ' trselect'; 
	//----------------------------------
	//Pone nombre de seleccionado en input de registro visible y oculto
	var tselrV = document.getElementById('txReg');
	var tselrH = document.getElementById('txCelRow');
	tselrV.value = isela;
	tselrH.value = 'tr-' + isela;	
	//---------------------------------
	AmpRegist();
}
//Anterior
function AmpRegist_Ant()
{
	var dsel = document.getElementById('txCelRow');
	var dvie = document.getElementById('dRegAmpV');
	var tabregs = window.frames['frTot_Tab'].document.getElementById('thRegs');
	var irow = tabregs.getElementsByTagName('tr');
	isel = dsel.value.replace('tr-','');
	iselm = parseInt(isel) - 1;
	if(iselm < 1){
		isela = irow.length;		
	} else {
		isela = iselm;
	}
	//--------------------------------
	//limpia todos los controles
	for(i = 0; i <= irow.length-1; i++){
		irow[i].className = irow[i].className.replace(' trselect', ''); 
	}
	//---------------------------------
	//Estilo
	irow[parseInt(isela) - 1].className = irow[parseInt(isela) - 1].className + ' trselect'; 
	//----------------------------------
	//Pone nombre de seleccionado en input de registro visible y oculto
	var tselrV = document.getElementById('txReg');
	var tselrH = document.getElementById('txCelRow');
	tselrV.value = isela;
	tselrH.value = 'tr-' + isela;	
	//---------------------------------
	AmpRegist();
}
//=========================================
//Funciones generación de Reportes
//-------------------------------------------
//Consolidado de Cliente
function RepConsCli()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(7, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sCurS = '';
	if(document.getElementById('tx5').value != 'TODAS'){sCurS = " AND Moneda ='" + document.getElementById('tx5').value + "'";}
	var swhere = "Tipo_Operacion='"+ document.getElementById('tx1').value +"' AND Documento_Beneficiario='"+ document.getElementById('tx2').value +"' AND Fecha>='"+ document.getElementById('tx3').value +"' AND Fecha<='"+ document.getElementById('tx4').value +"'" + sCurS;	
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Consolidado de Cliente&FieldsC="+ sfield +"&TableC=Operacion_Ventanilla&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx6').value +"&OrderdC="+ document.getElementById('tx7').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//-------------------------------------------
//Alertas de sistema
function RepAlertas()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sSucS = '';
	if(document.getElementById('tx2').value != 'TODAS'){sSucS = " AND Sucursal='" + document.getElementById('tx2').value + "'";}
	var sCurS = '';
	if(document.getElementById('tx3').value != 'TODAS'){sCurS = " AND Moneda='" + document.getElementById('tx3').value + "'";}
	var swhere = "Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"' AND "+ document.getElementById('tx6').value +">='"+ document.getElementById('tx7').value +"'" + sSucS + sCurS;	
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reportes de Alertas&FieldsC="+ sfield +"&TableC=Operacion_Ventanilla&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx8').value +"&OrderdC="+ document.getElementById('tx9').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//-------------------------------------------
//Comentarios a operaciones
function RepComents()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(5, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sOpsS = '';
	if(document.getElementById('tx1').value != 'COMPRAS Y VENTAS'){sOpsS = " AND Tipo_Operacion='" + document.getElementById('tx1').value + "'";}
	var swhere = "Fecha>='"+ document.getElementById('tx2').value +"' AND Fecha<='"+ document.getElementById('tx3').value +"'" + sOpsS;	
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reportes Comentarios Operaciones&FieldsC="+ sfield +"&TableC=Comentarios_Clientes&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx4').value +"&OrderdC="+ document.getElementById('tx5').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//-------------------------------------------
//Segmentacion mercado
function RepSegmentos()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(7, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sSegS = '';
	if(document.getElementById('tx2').value != 'TODOS'){sSegS = " AND Grupo_Segmento='" + document.getElementById('tx2').value + "'";}
	var sCurS = '';
	if(document.getElementById('tx3').value != 'TODAS'){sCurS = " AND Moneda='" + document.getElementById('tx3').value + "'";}
	var swhere = "Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"'" + sSegS + sCurS;	
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reportes Segmentación&FieldsC="+ sfield +"&TableC=Operacion_Ventanilla&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx6').value +"&OrderdC="+ document.getElementById('tx7').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//-----------------------------------
//Lista clinton
function RepClinton()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(4, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	if(document.getElementById('tx1').value != ''){
		var swhere = "Informacion LIKE '%25" + document.getElementById('tx1').value + "%25'";		
	} else if(document.getElementById('tx2').value != ''){
		var swhere = "Nombre LIKE '%25" + document.getElementById('tx2').value + "%25'";		
	} else {
		var swhere = "Identificacion<>''";		
	}
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reportes Lista Clinton&FieldsC="+ sfield +"&TableC=Lista_Clinton_1&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx3').value +"&OrderdC="+ document.getElementById('tx4').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//------------------------------------------
//Reporte movimientos de ventanilla
//Funcion cambio combo tipo de operación
function Sel_MovOps()
{
	var cbops = document.getElementById('tx1');
	var chfil = document.getElementById('dchfields');
	var cbfil = document.getElementById('tx8');
	//Resetea controles
	chfil.innerHTML = '';
	cbfil.innerHTML = '<option value=""></option>';
	cbfil.value = '';
	document.getElementById('tx9').value = '';
	document.getElementById('tx9').className = 'txbox';
	if(cbops.value != ''){
		//Obtiene nombre de tabla para mandar a consulta
		var sops = cbops.value;
		var vops = sops.split("|");
		//-----------------------------------------------
		//Consulta campos para chbox
		chfil.innerHTML = GenConretField('ajax/frConsView', 'Sel_MovOpsCh', vops[0], false);
		//Consulta campos para combo
		cbfil.innerHTML = cbfil.innerHTML + GenConretField('ajax/frConsView', 'Sel_MovOpsCb', vops[0], false); 			
	}
}
//-------------------------------------------
//Reporte operaciones ventanilla
function RepMovVenta()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sSucS = '';
	if(document.getElementById('tx4').value != 'TODAS'){sSucS = " AND Sucursal='" + document.getElementById('tx4').value + "'";}
	var sCurS = '';
	if(document.getElementById('tx5').value != 'TODAS'){sCurS = " AND Moneda='" + document.getElementById('tx5').value + "'";}
	var sMedS = '';
	if(document.getElementById('tx6').value != 'TODOS'){sMedS = " AND Medio_Pago='" + document.getElementById('tx6').value + "'";}
	var cbops = document.getElementById('tx1');
	var sops = cbops.value;
	var vops = sops.split("|");
	var sAnuS = '';
	if(document.getElementById('tx7').value == 'NO'){
		if(vops[0] != 'Cierres_Ventanilla'){
			if(vops[0] == 'Operacion_Ventanilla'){
				sAnuS = " AND Estado_Operacion='ACTIVO'";	
			} else {
				sAnuS = " AND Estado='ACTIVO'";	
			}
		}
	}
	//------------------------------------------------------	
	var sTypS = '';
	if(vops[1] != ''){
		sTypS = " AND " + vops[2] + "='" + vops[1] +"'";
	}
	var swhere = "Fecha>='"+ document.getElementById('tx2').value +"' AND Fecha<='"+ document.getElementById('tx3').value +"'" + sTypS + sSucS + sCurS + sMedS + sAnuS;	
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reportes Movimientos Ventanilla&FieldsC="+ sfield +"&TableC="+vops[0]+"&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx8').value +"&OrderdC="+ document.getElementById('tx9').value +"&RelC="+ document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//---------------------------------------
//Operaciones significativas
//Cambio de selección en tipo acumulado
function Sel_AcumC()
{
	var acum = document.getElementById('tx6')	
	var tipo = document.getElementById('tx7')	
	if(acum.value == 'ACUMULADO'){
		tipo.value = 'VALOR EN USD';
		tipo.disabled = true;
		tipo.className = 'txboxdis';
		
	} else {
		tipo.disabled = false;
		tipo.className = 'txboxo';
	}
}
//Genera reporte
function RepSignifica()
{
	//Valida campos obligatorios
	//Valida campos obligatorios
	if(fEmpty(10, 1) == true) {return 0;}
	//-----------------------------
	//Construye swhere
	var sSucS = '';
	if(document.getElementById('tx2').value != 'TODAS'){sSucS = " AND Sucursal='" + document.getElementById('tx2').value + "'";}
	var sSegS = '';
	if(document.getElementById('tx3').value != 'TODOS'){sSegS = " AND Grupo_Segmento='" + document.getElementById('tx3').value + "'";}
	//Tipo acumulado
	if(document.getElementById('tx7').value == 'CANTIDAD DIVISA'){
		var sDivS = " AND Cantidad>='"+ document.getElementById('tx8').value +"'";
	} else {
		var sDivS = " AND Valor_En_USD>='"+ document.getElementById('tx8').value +"'";
	}
	//----------------------------
	var dvala = '';
	var stype = '';
	if(document.getElementById('tx6').value == 'ACUMULADO'){
		//var swhere_1 ="SELECT ov.Documento_Beneficiario FROM Operacion_Ventanilla AS ov WHERE Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"' AND Estado_Operacion='ACTIVO'" + sSucS + sSegS + " GROUP BY Documento_Beneficiario HAVING SUM(ov.Valor_En_USD)>'" + document.getElementById('tx8').value + "'";	
		dvala = document.getElementById('tx8').value;
		stype = document.getElementById('tx6').value;
		//var swhere = "Documento_Beneficiario IN (" + swhere_1 + ") AND Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"' AND Estado_Operacion='ACTIVO'" + sSucS + sSegS;	
		var swhere = "Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"' AND Estado_Operacion='ACTIVO'" + sSucS + sSegS;	
} else {
		var swhere = "Tipo_Operacion='"+ document.getElementById('tx1').value + "' AND Fecha>='"+ document.getElementById('tx4').value +"' AND Fecha<='"+ document.getElementById('tx5').value +"' AND Estado_Operacion='ACTIVO'" + sSucS + sSegS + sDivS;	
	}
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var srepname = '';
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		//Consulta de listados de acuerdo a criterios
		//----------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reporte Operaciones Significativas&FieldsC="+ sfield +"&TableC=Operacion_Ventanilla&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx9').value +"&OrderdC="+ document.getElementById('tx10').value +"&RelC="+ document.getElementById('Relaciona').value+"&RType="+ stype +"&RVal="+ dvala;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}
//----------------------------------------------------------------------------------------
// Funcion reporte satisfaccion
function RepSatisfaccion(){
	// alert("Reporte");
	//Valida campos obligatorios
	if(fEmpty(8, 1) == true) {return 0;}
	//-------------------------------
	// Construye swhere
	var sOpsS = '';
	if(document.getElementById('tx1').value != 'COMPRAS Y VENTAS'){sOpsS = " AND Tipo_Operacion='" + document.getElementById('tx1').value + "'";}
	var sSucS = '';
	if(document.getElementById('tx2').value != 'TODAS'){sSucS = " AND Sucursal='" + document.getElementById('tx2').value + "'";}
	// Id cliente
	var sIdcl = '';
	if(document.getElementById('tx5').value != ''){sIdcl = "AND Id_Cliente='" + document.getElementById('tx5').value + "'";}
	// Respuesta encuesta
	var sResEnc = '';
	if(document.getElementById('tx6').value != ''){sResEnc = "AND P1='" + document.getElementById('tx6').value + "'";}
	var swhere = "Fecha>='" + document.getElementById('tx3').value + "' AND Fecha<='" + document.getElementById('tx4').value + "'" + sOpsS + sSucS + sIdcl + sResEnc;
	//-----------------------------
	//Construye campos seleccionados
	var chcon = document.getElementById('dchfields');
	var xch = chcon.getElementsByClassName('chselfild');
	var sfield = '';
	if(xch.length != 0){
		for(i = 0; i <= xch.length-1; i++){
			if(xch[i].checked == true){
				sfield = sfield + xch[i].id + ',';				
			}
		}
	}
	//-------------------------------------
	//Abre consulta
	//Limpia listados
	hidcap('dConsCri');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//limpia controles de seleccionados
	document.getElementById('txReg').value = '0';
	document.getElementById('txRegTot').value = '0';
	document.getElementById('txCelRow').value = '';
	//---------------------------------------------
	// Inicio de reporte
	viscap('dWait');
	setTimeout(function(){
		// Consulta el listado de acuerdo a criterios
		//------------------------------------------------------------------------
		//Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
		var frTot = document.getElementById('frTot_Tab');
		frTot.src = "ajax/frConsViewTab.php?RepName=Reporte Satisfacción Clientes&FieldsC="+ sfield +"&TableC=Encuestas&WhereC="+ swhere +"&OrderfC="+ document.getElementById('tx7').value +"&OrderdC="+ document.getElementById('tx8').value +"&RelC="+document.getElementById('Relaciona').value;
		//------------------------------------------------------------------------
		hidcap('dWait');
	}, 1000);
}