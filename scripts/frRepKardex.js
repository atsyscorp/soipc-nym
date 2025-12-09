// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA FRREPKARDEX.PHP
//=============================================================
//Procedimiento para tamaño tabla reporte
function TabSize()
{
	var dfifr = window.parent.document.getElementById('frMain');
	var sfrh = dfifr.style.height;
	var tota = document.getElementById('dTot_Tab');
	var toth = (parseInt(sfrh.replace("px", "")) - 215) + "px";
	tota.style.height = toth;
}
//------------------------------------------
//Funcion cambio de sucursal
function cbSuc_Change()
{
	//Limpia combo caja
	InnerCtr('tx2', '<option value=""></option><option value="TODAS">TODAS</option>');
	//Limpia combo de agrupacion
	InnerCtr('tx4', '<option value=""></option>');
	//---------------------------------------------
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	if(cbSuc.value == "TODAS")
	{
		cbEst.value = 'TODAS';
	} else if(cbSuc.value != '') {
		viscap('dWait');
		//Consulta la cantidad de estaciones de la sucursal seleccionada
		var iCajas = GenConretField("General", "Gen_Find_Field", "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal='" + cbSuc.value + "'", false);
		for(i = 1; i <= parseInt(iCajas); i++){
			var sCero = '0';
			if(i > 9){sCero = '';}
			document.getElementById("tx2").options[i + 1] = new Option(sCero + i, sCero + i);
		}
		hidcap('dWait');
	}
	//Llama procedimiento cambio de caja
	cbCaja_Change();
}
//----------------------------------------------
//Cambio de seleccion de caja
function cbCaja_Change()
{
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	var cbGru = document.getElementById('tx4');
	//Limpia combo de agrupacion
	InnerCtr('tx4', '<option value=""></option>');
	if(cbSuc.value == "TODAS")	
	{
		InnerCtr('tx4', '<option value=""></option><option value="POR ESTACIONES">POR ESTACIONES</option><option value="POR SUCURSALES">POR SUCURSALES</option><option value="TOTAL EMPRESA">TOTAL EMPRESA</option>');
	} else if(cbSuc.value != '') {
		if(cbEst.value == "TODAS")
		{
			InnerCtr('tx4', '<option value=""></option><option value="POR ESTACIONES">POR ESTACIONES</option><option value="POR SUCURSALES">POR SUCURSALES</option>');
		} else if(cbEst.value != '') {
			InnerCtr('tx4', '<option value=""></option><option value="POR ESTACIONES">POR ESTACIONES</option>');
			cbGru.value = 'POR ESTACIONES';
		}
	}
}
//-----------------------------------------------
//Funcion para generar reporte
function cmAccept_Clic()
{
	//Valida campos obligatorios
	if(fEmpty(5, 1) == true) {return 0;}
	//Valida seleccion de fechas
	var cbYear = document.getElementById('cbYear');
	var cbMonth = document.getElementById('cbMonth');
	var cbDay = document.getElementById('cbDay');
	if(cbYear.value == '' || cbMonth.value == '' || cbDay.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha inicial.", 1);
		return false;
	} 
	var cbYear1 = document.getElementById('cbYear1');
	var cbMonth1 = document.getElementById('cbMonth1');
	var cbDay1 = document.getElementById('cbDay1');
	if(cbYear1.value == '' || cbMonth1.value == '' || cbDay1.value == '')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Seleccione el Año, Mes y Día de la fecha final.", 1);
		return false;
	} 
	//Valida fecha inicial menor a fecha final
	var dini = new Date(cbYear.value + "-" + cbMonth.value + "-" + cbDay.value);
	var dfin = new Date(cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value);
	if(dfin < dini){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha inicial del reporte debe ser menor o igual a la fecha final.", 1);
		return false;	
	}
	//-------------------------------------------
	//Limpia listados
	//InnerCtr('dTot_Tab', '');
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = '';
	//-------------------------------------------
	//Inicio de reporte
	viscap('dbloc');
	viscap('dWait');
	setTimeout(function(){
	//Consulta de listados de acuerdo a criterios
	var cbSuc = document.getElementById('tx1');
	var cbEst = document.getElementById('tx2');
	var cbCur = document.getElementById('tx3');
	var cbGru = document.getElementById('tx4');
	var cbInc = document.getElementById('tx5');
	var sIni = cbYear.value + "-" + cbMonth.value + "-" + cbDay.value;
	var sFin = cbYear1.value + "-" + cbMonth1.value + "-" + cbDay1.value;
	var sSucS = '';
	if(cbSuc.value != 'TODAS'){sSucS = " AND Sucursal ='" + cbSuc.value + "'";}
	var sCajS = '';
	if(cbEst.value != 'TODAS'){sCajS = " AND Estacion ='" + cbEst.value + "'";}
	var sCurS = '';
	if(cbCur.value != 'TODAS'){sCurS = " AND Moneda ='" + cbCur.value + "'";}
	//----------------------------------------------------------------------
    //Llama procedimiento para generar reporte, dependiendo de seleccion de criterios y agrupacion
	var sagru = document.getElementsByName('rbAgrupa'); 
	if(cbSuc.value == 'TODAS')
	{
		if(cbGru.value == 'TOTAL EMPRESA') {	//Total empresa
			//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, '', '', 'TOTAL', 'TODAS', sIni, sFin, cbInc.value, sCurS));
			GenRepKardex(sSucS, sCajS, '', '', 'TOTAL', 'TODAS', sIni, sFin, cbInc.value, sCurS);
		} else if(cbGru.value == 'POR SUCURSALES') {	//Por sucursales
			//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, '', '', 'SUCURSAL', 'TODAS', sIni, sFin, cbInc.value, sCurS));
			GenRepKardex(sSucS, sCajS, '', '', 'SUCURSAL', 'TODAS', sIni, sFin, cbInc.value, sCurS);
		} else if(cbGru.value == 'POR ESTACIONES') {	//Por estaciones
			//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, '', '', 'CAJA', 'TODAS', sIni, sFin, cbInc.value, sCurS));
			GenRepKardex(sSucS, sCajS, '', '', 'CAJA', 'TODAS', sIni, sFin, cbInc.value, sCurS);
		}
	} else if(cbSuc.value != '') {
		if(cbEst.value == 'TODAS')
		{
			if(cbGru.value == 'POR SUCURSALES') {	//Total sucursal
				//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, cbSuc.value, 'TODAS', 'SUCURSAL', cbSuc.value, sIni, sFin, cbInc.value, sCurS));
				GenRepKardex(sSucS, sCajS, cbSuc.value, 'TODAS', 'SUCURSAL', cbSuc.value, sIni, sFin, cbInc.value, sCurS);
			} else if(cbGru.value == 'POR ESTACIONES') {	//Por sucursales
				//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, cbSuc.value, 'TODAS', 'CAJA', cbSuc.value, sIni, sFin, cbInc.value, sCurS));
				GenRepKardex(sSucS, sCajS, cbSuc.value, 'TODAS', 'CAJA', cbSuc.value, sIni, sFin, cbInc.value, sCurS);
			}
		} else if(cbEst.value != '') {
			//InnerCtr('dTot_Tab', GenRepKardex(sSucS, sCajS, cbSuc.value, cbEst.value, 'CAJA', cbSuc.value, sIni, sFin, cbInc.value, sCurS));
			GenRepKardex(sSucS, sCajS, cbSuc.value, cbEst.value, 'CAJA', cbSuc.value, sIni, sFin, cbInc.value, sCurS);
		}
	}
	//------------------------------------------------------------------------
	hidcap('dbloc');
	hidcap('dWait');
	}, 1000);
}
//---------------------------------------------------------
//Funcion para generar reporte
function GenRepKardex(stSuc, stCaj, sSucN, sCajN, sType, SucVal, sIni, sFin, sInc, sCur)
{
	var frTot = document.getElementById('frTot_Tab');
	frTot.src = "ajax/frRepKardexTab.php?sFun=GenRepKardex" + "&stSuc=" + stSuc + "&stCaj=" + stCaj + "&sSucN=" + sSucN + "&sCajN=" + sCajN + "&sType=" + sType + "&SucVal=" + SucVal + "&sIni=" + sIni + "&sFin=" + sFin + "&sInc=" + sInc + "&sCur=" + sCur;
}
//-------------------------------------------------------
//Funcion para exportar reporte
function cmExport_Clic(sCompa)
{
	var frTot = document.getElementById('frTot_Tab');
	var htmlTot = window.open(frTot.src + "&sExp=Exportar", "_blank");
}


