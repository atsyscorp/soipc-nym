// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsClientes.PHP
//=============================================================
//Funcion para load carga fecha
function frOpsClientes_Load(sDec, sBen)
{
	document.getElementById("tx23").value = hoyday();
	document.getElementById("tx2").focus();
	//---------------------------------------
	//Selecciona por default depto y ciudad bogota
	document.getElementById("tx15").value = "BOGOTA D.C.";
	cbDep_Change();
	if(sDec != '' && sBen != '')
	{
		//Consulta informacion del declarante		
		document.getElementById('tx2').value = sDec;
		document.getElementById('btfind').click();
		//Si declarante es diferenet a benficiario
		if(sDec != sBen)
		{
			//Oculta mesanje consulta declarante 
			hidcap('dMsj1');
			hidcap('dbloc');
			var rbDec = document.getElementsByName("rbDecben");
			rbDec[1].checked = true;
			rbDecBen_Clic();	
			document.getElementById('btben').click();
			document.getElementById('frBen').contentWindow.document.getElementById('tx2').value = sBen;
			document.getElementById('frBen').contentWindow.document.getElementById('btfind').click();
		}
	}
}
//---------------------------------------------------
//Funcion cambio de texto identificacion deshabilita controles
function txCODDoc_TextChanged()
{
	disbtn('btben');
	disbtn('btaccept');
	disbtn('btmodif');
	disbtn('btgocom');
	disbtn('btgoven');
	disbtn('btphoto');
}
//Beneficiario
function txCOBDoc_TextChanged()
{
	disbtn('btaccept');
	disbtn('btmodif');
	disbtn('btgocom');
	disbtn('btgoven');
	disbtn('btphoto');
	//Deshabilita controles de ir a compra o venta de declarante
	var cmDGoCom = window.parent.document.getElementById('btgocom');
	var cmDGoVen = window.parent.document.getElementById('btgoven');
	cmDGoCom.disabled = true;
	cmDGoCom.className = 'btcontdis';
	cmDGoVen.disabled = true;
	cmDGoVen.className = 'btcontdis';
}
//Pasar a beneficirio --> Pestaña
function Go_Ben()
{
	//Pasa valor de id de delcarante y formulario de beneficiario
	document.getElementById('frBen').contentWindow.document.getElementById('txDifBCh').value = document.getElementById("tx1").value;
}
//Pasa a declarante
function Go_Dec()
{
	//Pasa valor de id de beneficiario a formulario de declarante	
	window.document.getElementById('txDifBCh').value = document.getElementById('frBen').contentWindow.document.getElementById('tx1').value;
	//Habilita controles de ir a compra y venta
	if(document.getElementById("btmodif").className == 'btcont' && document.getElementById('frBen').contentWindow.document.getElementById('btmodif').className == 'btcont')
	{
		enabtn('btgocom');
		enabtn('btgoven');
	}
}
//------------------------------------------------
//Construccion nombre completo
function txName_Change()
{
	var sNom2 = '';
	var sApe1 = '';
	//Nombre 2
	if(document.getElementById("tx6").value == '')
	{
		sNom2 = '';
	} else {
		sNom2 = document.getElementById("tx6").value + ' ';
	}
	//Apellido 1
	if(document.getElementById("tx8").value == '')
	{
		sApe1 = document.getElementById("tx7").value;
	} else {
		sApe1 = document.getElementById("tx7").value + ' ';
	}
	//--------------------------------------------------------------
	document.getElementById("tx9").value = document.getElementById("tx5").value + ' ' + sNom2 + sApe1 + document.getElementById("tx8").value;
}
//-------------------------------------------
//Delete en combo no obligatorio pone value = ''
function cbCliDel(cbvar, e)
{
	var code = e.keyCode;
	if ((code == 8) || (code == 37)) {
		cbvar.value = '';
		return false;	
	}
}
//-------------------------------------------
//Cambio de selección en departamento busca ciudades
function cbDep_Change()
{
	var cbdep = document.getElementById("tx15");
	var cbcit = document.getElementById("Ciudad_1");
	//Limpia ciudad
	InnerCtr('Ciudad_1', '<option value=""></option>');
	if(cbdep.value != '')
	{
		viscap('dWait');
		var strSQL =  "SELECT Ciudad FROM XConf_Ciudades WHERE Departamento = '" + cbdep.value + "'";
		cbcit.innerHTML = cbcit.innerHTML + GenConretField('ajax/frOpsClientes', 'UpDate_City', strSQL, false);
		hidcap('dWait');
		if(cbdep.value.indexOf("BOGOT") != -1){
			cbcit.value = 'BOGOTA';
		}
	}
}
//--------------------------------------------
//Funcion de valores predeterminados en caso de clean de controles
function PredVals()
{
	//Estado cliente
	if(document.getElementById("tx25").value == ''){document.getElementById("tx25").value = 'NORMAL';}
	//Contador
	document.getElementById("tx26").value = '1';
	//Fecha nacimiento
	var cbyear = document.getElementById('cbYear');
	var cbmonth = document.getElementById('cbMonth');
	var cbday = document.getElementById('cbDay');
	cbyear.value = 'Año';
	cbmonth.value = 'Mes';
	cbday.value = 'Día';
	//Depto y ciudad bogota
	document.getElementById("tx15").value = "BOGOTA D.C.";
	cbDep_Change();
	document.getElementById("Ciudad_1").value = "BOGOTA";
	//Persona politica
	ValueCtr('tx27', 'NO')
}
//-------------------------------------------
//Cambio seleccion grupo segmneto
function cbGroup_Change()
{
	var cbgru = document.getElementById("tx19");
	var cbseg = document.getElementById("Segmento");
	var cbact = document.getElementById("tx17");
	//Limpia segmento
	InnerCtr('Segmento', '<option value=""></option>');
	if(cbgru.value != '')
	{
		viscap('dWait');
		var strSQL =  "SELECT Segmento FROM Segmentos_Mercado WHERE Grupo = '" + cbgru.value + "'";
		cbseg.innerHTML = cbseg.innerHTML + GenConretField('ajax/frOpsClientes', 'UpDate_City', strSQL, false);
		hidcap('dWait');
		if(cbgru.value == 'EXTRANJERO'){
			cbact.value = 'NO RESIDENTE COLOMBIANO';
		}
	}
}
//-------------------------------------------
//Clic para mostrar buscar actividad
function GoFindAct()
{
	viscap('dbloc');
	viscap('frActividad');
	document.getElementById("txFAct").focus();
}
//=====================================================
//Funciones buscar actividad
function txAct_Find()
{
	var txact = document.getElementById("txFAct");
	var tbact = document.getElementById("lstOAList");
	tbact.innerHTML = '<tr class="bgcol_6 fwhite">' + 
							'<td style="width:66px; text-align:left" class="celrow">Código</td>' +
							'<td style="width:544px; text-align:left" class="celrow">Ocupación o actividad</td>' +
						'</tr>';
	//-------------------------------------------------
	if(txact.value.length >= 3)
	{
		viscap('dWait');		
        var strSQLC = "SELECT Codigo_Actividad, Actividad FROM XConf_Actividades WHERE (Codigo_Actividad LIKE '%" + txact.value + "%' OR Actividad LIKE '%" + txact.value + "%')";
		tbact.innerHTML = tbact.innerHTML + GenConretField('ajax/frOpsClientes', 'Find_Act', encodeURI(strSQLC), false);
		hidcap('dWait');		
	}
}
//Clic en listado
function lstfun(sact)
{
	var cbActi = document.getElementById("tx17");
	var act = sact.attributes["name"].value;
	cbActi.value = act;
	//------------------------------------------------
	//Oculta ventana
	var tbact = document.getElementById("lstOAList");
	tbact.innerHTML = '<tr class="bgcol_6 fwhite">' + 
							'<td style="width:66px; text-align:left" class="celrow">Código</td>' +
							'<td style="width:544px; text-align:left" class="celrow">Ocupación o actividad</td>' +
						'</tr>';
	document.getElementById("txFAct").value = '';
	hidcap('frActividad');
	hidcap('dbloc');
}
//=====================================================
//Cambio del maxlenght de adress 2
function Adress_Len()
{
	var txad1 = document.getElementById("txAdd1");
	var txad2 = document.getElementById("txAdd2");
	txad2.maxLength = txad1.maxLength - txad1.value.length + 1;
}
//---------------------------------------------
//Validacion caracteres campo id
function txID_Press(txid, e, doPic, cliUp)
{
	var code = e.keyCode;
	if ((code >= 48 && code <= 57) || (code >= 93 && code <= 122) || (code == 8) || (code == 9) || (code == 37) || (code == 39) || (code >= 65 && code <= 90)) {
		return true;
	} else if(code == 13) {
		cmCODFind_Click(doPic, cliUp);
		return false;		
	} else {
		return false;	
	}
}
//Campo digito de verificacion solo numericos
function txDV_Press(txid, e, doPic, cliUp)
{
	var code = e.keyCode;
	if ((code >= 48 && code <= 57) || (code >= 93 && code <= 105) || (code == 8) || (code == 9) || (code == 37) || (code == 39)) {
		return true;
	} else if(code == 13) {
		cmCODFind_Click(doPic, cliUp);
		return false;		
	} else {
		return false;	
	}
}
//-------------------------------------------
//En cambio de nacionalidad si es colombia, trae ciudades y campo obligatorio
function Nacio_Chamge()
{
	var cbnac = document.getElementById("Nacionalidad");
	var cbcit = document.getElementById("Ciudad");
	//Limpia ciudad
	InnerCtr('Ciudad', '<option value=""></option>');
	cbcit.className = 'txbox';
	if(cbnac.value == 'COLOMBIA')
	{
		viscap('dWait');
		var strSQL =  "SELECT Ciudad FROM XConf_Ciudades";
		cbcit.innerHTML = cbcit.innerHTML + GenConretField('ajax/frOpsClientes', 'UpDate_City', strSQL, false);
		hidcap('dWait');
		//Control obligatorio
		cbcit.className = 'txboxo';
	}
}
//-------------------------------------------
//Cambio de selección en tipo de documento
function cbDoc_Change()
{
	var cbDoc = document.getElementById("tx4");
	var txNom1 = document.getElementById("tx5");
	var txNom2 = document.getElementById("tx6");
	var txApl1 = document.getElementById("tx7");
	var txApl2 = document.getElementById("tx8");
	var txNomC = document.getElementById("tx9");
	document.getElementById("tx17").value = '';
	if(cbDoc.value == 'NIT')
	{	
		txNom1.value = '';
		txNom1.disabled = true;
		txNom1.className = 'txboxdis';
		txNom2.value = '';
		txNom2.disabled = true;
		txNom2.className = 'txboxdis';
		txApl1.value = '';
		txApl1.disabled = true;
		txApl1.className = 'txboxdis';
		txApl2.value = '';
		txApl2.disabled = true;
		txApl2.className = 'txboxdis';
		txNomC.disabled = false;
		txNomC.className = 'txboxo';
		document.getElementById("tx19").value = 'PERSONA JURÍDICA';
	} else {
		txNom1.disabled = false;
		txNom1.className = 'txboxo';
		txNom2.disabled = false;
		txNom2.className = 'txbox';
		txApl1.disabled = false;
		txApl1.className = 'txboxo';
		txApl2.disabled = false;
		txApl2.className = 'txbox';
		txNomC.disabled = true;
		txNomC.className = 'txboxdis';
		if(cbDoc.value == 'CC' || cbDoc.value == 'CE' || cbDoc.value == 'TI')
		{
			document.getElementById("tx19").value = 'PERSONA NATURAL';
		} else if(cbDoc.value == 'PS'){
			document.getElementById("tx19").value = 'EXTRANJERO';
		}
	}
	//-----------------------------------
	//Llama funcion de cambio de grupo segmento
	cbGroup_Change();	
}
//-------------------------------------------
//Clic en listado de imagenes
function lstfun_1(spic)
{
	var pbCODImg = document.getElementById("results");
	var pic = spic.attributes["name"].value;
	pbCODImg.innerHTML = '<img id="imPics" src="Fotos/' + pic + '.jpg" style="height:114px; width:auto" onclick="pbCODImg_Clic(this)" />';
}
//-------------------------------------------
//Funcion para buscar cliente
function cmCODFind_Click(doPic, cliUp)	//Parametros --> Tomar foto y días mensaje actualizacion de datos
{
	//Valida escritura de documento
	var sID = document.getElementById("tx2");
	if(sID.value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Digite la identificación que desea buscar.", 1);
		return false;	
	}


// Mostrar popup para confirmar identificación prueba del 22012024 por juan camilo
    // Mostrar popup para confirmar identificación
    var userInput = prompt("Confirme la identificación (sin copiar y pegar):", "");

    // Verificar si se ha utilizado la combinación Ctrl+V
    if (userInput !== null && (event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'v') {
        viscap('dbloc');
        dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Por favor, ingrese manualmente la identificación sin utilizar copiar y pegar.", 1);
        return false;
    }

    // Comparar con el valor original
    var originalID = sID.value.trim();
    var confirmedID = (userInput !== null) ? userInput.trim() : null;

    if (originalID !== confirmedID) {
        viscap('dbloc');
        dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La identificación no coincide. Por favor, inténtelo nuevamente.", 1);
        return false;
    }

// hasta aqui va la prueba.
	//----------------------------------------------------
	//Limpia controles de ventana
	//Captura DV para hacer limpieza y documento completo
	var sDV = document.getElementById("tx3");
	var sDVv = document.getElementById("tx3").value;
	var sDoc = sID.value.toString() + sDV.value;
	//Principales
	ctrclen(30, 2);
	document.getElementById("tx1").value = sDoc;
	//Pone DV
	sDV.value = sDVv;
	//Camara
	document.getElementById("lstCODList").innerHTML = '';
	document.getElementById("results").innerHTML = '';
	//Reinicia valores default
	PredVals();
	//-----------------------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------------------
	//Consulta lista clinton
	var isCli = GenConretField('General', 'Gen_Find_Field_Clinton', sDoc, false);
	if(isCli != '')
	{
		hidcap('dWait');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "')", "", "El documento " + sDoc + " se encuentra en la Lista Clinton. No podrá realizar operaciones con esta persona u organización. La ventana de conocimiento de cliente deberá cerrarse.", 1);
		return false;	
	}
	//--------------------------------------------
	//Consulta información de cliente
	//Llama procedimiento para poner info en controles
	var swhere = "Identificacion = '" + sDoc + "'";
	var iCFound = genfind('General', 'Gen_Find', 30, 'Clientes', swhere, 1);
	//Tipo de documebnto y nombre
	//cbDoc_Change();	

	if(iCFound == 1){
		// Estos campos se limpiarán, forzando la verificación/reingreso por el usuario.
		
		// Grupo Segmento (tx19)
		document.getElementById("tx19").value = '';
		
		// Ocupación (tx17)
		document.getElementById("tx17").value = '';
		
		// Origen de Fondos (tx21)
		document.getElementById("tx21").value = '';
		
		// Correo Electrónico (tx18)
		document.getElementById("tx18").value = '';

		// Segmento (tx20) - Se limpia el campo oculto donde se guarda el valor (tx20)
		//document.getElementById("tx20").value = ''; 
		// Y también el control visible (Segmento)
		//document.getElementById("Segmento").value = '';
		
	}
	//Pone la dirección en el primer campo
	document.getElementById("txAdd1").value = document.getElementById("tx14").value;
	//Ciudad - Departamento
	cbDep_Change();
	if(iCFound == 1){document.getElementById("Ciudad_1").value = document.getElementById("tx16").value;}
	//Grupo Segmento
	//cbGroup_Change();
	//document.getElementById("Segmento").value = document.getElementById("tx20").value;
	//Fecha de nacimiento
	var cbyear = document.getElementById('cbYear');
	var cbmonth = document.getElementById('cbMonth');
	var cbday = document.getElementById('cbDay');
	if(iCFound == 1){
		var sdate1 = document.getElementById('tx23').value.split("-");
		cbyear.value = sdate1[0];
		cbmonth.value = sdate1[1];
		getmdays();	//Obtiene dias
		cbday.value = sdate1[2];
	} else { 
		cbyear.selectedIndex = 0;
		cbmonth.selectedIndex = 0;
		cbday.selectedIndex = 0;
	}
	//Abre campo de nacionalidad y ciudad de nacimiento
	if(document.getElementById("tx10").value.indexOf("-") != -1){
		var anac = document.getElementById("tx10").value.split("-");
		document.getElementById("Nacionalidad").value = anac[0];
		//Carga ciudades primero
		//Nacio_Chamge();
		document.getElementById("Ciudad").value = anac[1];
	} else {
		document.getElementById("Nacionalidad").value = document.getElementById("tx10").value
	}
	//----------------------------------------------------------------
	//Valida que el tipo de documento se encuentre en el listado
	if(iCFound == 1){
		if(document.getElementById("tx4").value == ''){
			ctrclen(30, 2);
			hidcap('dWait');
			dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La información del cliente buscado no puede mostrarse en la pestaña de Declarante. Intente realizar la consulta desde la pestaña de Beneficiario.", 1);
			return false;
		}
	}
	//----------------------------------------------------------------
	//Consulta fecha ultima operación
	if(iCFound == 1){
		var strSQLO = "SELECT MAX(Fecha) FROM Operacion_Ventanilla WHERE Documento_Declarante = '" + sDoc + "' AND Estado_Operacion = 'ACTIVO'";
		var maxDay = GenConretField('General', 'Gen_Find_Field', strSQLO, false);
		var sValDUp = '';
		if(maxDay != ''){
			document.getElementById("tx24").value = maxDay;
			//Validacion de actualizacion de datos
			var today = new Date();
			var dalt = new Date(maxDay);
			var difday = (today - dalt) / (1000*60*60*24);
			if(difday > parseInt(cliUp))
			{
				alert("La última operación con el cliente se registró hace " + parseInt(difday) + " días. Por favor solicite actualización de datos.");
				//dmsshowc("drod_1 dlin_6 bgcol_1", "falert", "hidden", "hidcap('dMsj" + 1 + "')", "", "La última operación con el cliente se registró hace " + parseInt(difday) + " días. Por favor solicite actualización de datos.", 1);
			}
		} else {
			document.getElementById("tx24").value = hoyday();
		}
	} else { 
		document.getElementById("tx24").value = hoyday();
	}
	//----------------------------------------------------------------
	if(iCFound == 1)
	{
		document.getElementById("txCODNew").value = 0;
		enabtn('btmodif');
		disbtn('btaccept');
		//-------------------------------------------------------
		//Imagenes si esta activado
		if(doPic == 'SI')
		{
			//Busca imagenes
	        var strSQLPic = "SELECT Identificacion FROM Fotos WHERE Documento = '" + sDoc + "' Order By Identificacion";
			var lstPics = GenConretField('ajax/frOpsClientes', 'Find_Pict', strSQLPic, false);
			document.getElementById("lstCODList").innerHTML = lstPics;
			//-----------------------------------------------
			//Activa camara
			Webcam.set({
				width: 270,
				height: 114,
			   	dest_width: 540,
		        dest_height: 228,
				image_format: 'webp',
				jpeg_quality: 70
			});
			Webcam.attach( '#my_camera' );
			enabtn('btphoto');
			//-----------------------------------------------
			//Selecciona primera imagen de cliente
			if(lstPics != '')
			{
		        var strSQLPic1 = "SELECT Identificacion FROM Fotos WHERE Documento = '" + sDoc + "' Order By Identificacion limit 0,1";
				var pic1 = GenConretField('General', 'Gen_Find_Field', strSQLPic1, false);
				var pbCODImg = document.getElementById("results");
				pbCODImg.innerHTML = '<img src="Fotos/' + pic1 + '.jpg" style="height:114px; width:auto" onclick="pbCODImg_Clic(this)" />';
			}
		}
		//-----------------------------------------------
		//Procedimientos dependiendo si es mismo o diferente beneficiario y si ya esta buscado
		var rbDec = document.getElementsByName("rbDecben");
		if(rbDec[1].checked){
			enabtn('btben');
		}
		/*
		if(rbDec[0].checked){   //Mismo beneficiario
			enabtn('btgocom');
			enabtn('btgoven');
		} else if(rbDec[1].checked && document.getElementById("txDifBCh").value == ''){ //Diferente y beneficiario sin buscar
			disbtn('btgocom');
			disbtn('btgoven');
		} else {
			enabtn('btgocom');
			enabtn('btgoven');
		}
		*/
		//------------------------------------------------------
		//Mensaje de exitos y estado de cliente
		var sState = document.getElementById("tx25");
		if(sState.value == "BLOQUEADO"){
			hidcap('dWait');
			dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "')", "", "El cliente " + document.getElementById("tx9").value + " con documento " + sDoc + " se encuentra en estado: " + sState.value + ". No puede realizar operaciones con este cliente. OBSERVACIONES: " + document.getElementById("tx22").value, 1);
			return false;	
		} if(sState.value == "OBSERVACION"){
			//hidcap('dWait');
			hidcap('dbloc');
			/*dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('btmodif')", "", "El cliente " + document.getElementById("tx9").value + " con documento " + sDoc + " se encuentra en estado: <b>" + sState.value + "</b>. OBSERVACIONES3: " + document.getElementById("tx22").value, 1);*/

			// Mostrar mensaje con temporizador de 5 segundos
			var message = "cliente con OBSERVACIONES: " + document.getElementById("tx22").value;
			var dialog = document.createElement("div");
			dialog.textContent = message;
			dialog.style.position = "fixed";
			dialog.style.top = "5%";
			dialog.style.left = "50%";
			dialog.style.transform = "translate(-50%, -50%)";
			dialog.style.backgroundColor = "yellow";
			dialog.style.padding = "10px";
			dialog.style.border = "1px solid black";
			dialog.style.zIndex = "9999";
			document.body.appendChild(dialog);

			setTimeout(function() {
				dialog.remove(); // Ocultar el cuadro de dialogo después de 5 segundos
			}, 20000); // 5000 milisegundos = 5 segundos
			//


		}else {
			hidcap('dWait');
			dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('btmodif')", "", "El cliente " + document.getElementById("tx9").value + " con documento " + sDoc + " se encuentra en estado: <b>" + sState.value + "</b>. OBSERVACIONES: " + document.getElementById("tx22").value, 1);
		}
	} else {
		hidcap('dWait');
		hidcap('dbloc');
		document.getElementById("txCODNew").value = 1;
		disbtn('btmodif');
		enabtn('btaccept');
		disbtn('btgocom');
		disbtn('btgoven');
		//Muestra mensaje
		hidcap('dWait');
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('tx4')", "", "El cliente con documento " + sDoc + " no se encuentra registrado en la base de datos. Por favor ingrese todos los datos del cliente.", 1);
	}
}
//---------------------------------------------
//Cambio de seleccion radio mimos diferente
function rbDecBen_Clic()
{
	var rbDec = document.getElementsByName("rbDecben");
	if(rbDec[0].checked)
	{
		disbtn('btben');
		if(document.getElementById("btmodif").className == 'btcont'){
			enabtn('btgocom');
			enabtn('btgoven');
		} else {
			disbtn('btgocom');
			disbtn('btgoven');
		}
	} else {
		disbtn('btgocom');
		disbtn('btgoven');
		if(document.getElementById("btmodif").className == 'btcont'){
			enabtn('btben');
		} else {
			disbtn('btben');
		}
	}
}
//---------------------------------------------
//Clic para subir image
function cmCODCap_Click()
{
	var sDoc = 	document.getElementById("tx1").value;
	if(sDoc != '')
	{
		//Muestra esperando
		waitim('results');
		var data_uri_1 = ''; 
		Webcam.snap(function(data_uri) {
			data_uri_1 = data_uri;	
		});
    	Webcam.upload(data_uri_1, 'ajax/frOpsClientes_UploadPic.php?Client=' + sDoc, function(code, text) {
			if(code == 200){
				var stext = '';
				var pbCODImg = document.getElementById("results");
				if(text.indexOf(".|.") != -1)
				{
					stext = text.split(".|.");
				}
				if(stext[0] == '1'){
					var rbDec = document.getElementsByName("rbDecben");
					if(rbDec[0].checked){   //Mismo beneficiario
						enabtn('btgocom');
						enabtn('btgoven');
					} else if(rbDec[1].checked && document.getElementById("txDifBCh").value == ''){ //Diferente y beneficiario sin buscar
						disbtn('btgocom');
						disbtn('btgoven');
					} else {
						enabtn('btgocom');
						enabtn('btgoven');
					}

					pbCODImg.innerHTML = '<img id="imPics" src="'+data_uri_1+'" style="height:114px; width:auto" onclick="pbCODImg_Clic(this)" />';
					//Registra imagen en tabla
					document.getElementById("lstCODList").innerHTML = document.getElementById("lstCODList").innerHTML + '<tr valign="middle" style="cursor:pointer" id="rowa" name="' +  stext[1] + '" class="fcont trwhite" onclick="lstfun_1(this)">' + '<td class="celrow" id="cel">' +  stext[1] + '</td></tr>';
				} else {
					pbCODImg.innerHTML = '<div class="falert" style="margin:20px">' + text + '</div>';					
				}
			}
    	} );
	}
}
//-------------------------------------------
//Funcion para ampliar imagen
function pbCODImg_Clic(sImg)
{
	var dImgB = document.getElementById("frPics");
	var pbImgB = document.getElementById("imPics1");
	var pic = sImg.attributes["src"].value;
	pbImgB.src = pic;
	viscap('dbloc');
	viscap('frPics');
}

// funcion para validar el email 17/02/2023 juan camilo
function validarEmail(email) {
  var txmail = document.getElementById('tx18');
  var dominiosFrecuentes = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com']; // lista de dominios frecuentes conocidos
  var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // expresión regular para validar el formato del correo electrónico

  if (!email) { // si el campo está vacío, muestra un mensaje de error y devuelve false
    viscap('dbloc');
    dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Por favor ingrese un correo electrónico.", 1);
    txmail.focus();
    return false;
  }

  if (email.toLowerCase() === 'ns') { // si el usuario escribió 'NS', se permite como una entrada válida y se devuelve true
    return true;
  }

  if (!emailRegex.test(email)) { // valida que el correo electrónico tenga un formato válido
    viscap('dbloc');
    dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El correo electrónico no es válido. Por favor verifique la información.", 1);
    txmail.focus();
    return false;
  }

  var dominio = email.split('@')[1]; // extrae el dominio del correo electrónico

  var dominioCorrecto = false;

  if (dominiosFrecuentes.indexOf(dominio) !== -1) { // si es un dominio frecuente, valida que está bien escrito
    dominioCorrecto = true;
  } else {
    // si el dominio no es frecuente, verifica si es parecido a alguno de los dominios frecuentes conocidos
    for (var i = 0; i < dominiosFrecuentes.length; i++) {
      var dominioFrecuente = dominiosFrecuentes[i];
      var partesDominioFrecuente = dominioFrecuente.split('.');
      var partesDominio = dominio.split('.');
      if (partesDominioFrecuente[0] === partesDominio[0] && partesDominioFrecuente[1] === partesDominio[1]) {
        // si las dos primeras partes del dominio coinciden, sugerir corrección y salir del bucle
        viscap('dbloc');
        dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "¿Quizás quisiste escribir " + dominioFrecuente + "? Por favor verifique la información.", 1);
        txmail.focus();
        return false;
      }
    }
  }

  return true; // devuelve true si el correo electrónico

}





//----------------------------------------
//Validacion caracteres campo id
function txNameAdd_Press(txid, e)
{
	var code = e.keyCode;
	//alert(code);
	if ((code >= 48 && code <= 57) || code == 209 || code == 241 || (code >= 97 && code <= 122) || (code == 8) || (code == 9) || (code == 37) || (code == 32) || (code == 39) || (code >= 65 && code <= 90)) {
		return true;
	} else {
		return false;	
	}
}
//-----------------------------------
//Funcion para seleccion de hotel
function selHotel(sName, sTel, sDir)
{
	//Selecciona predeterminados
	document.getElementById("tx11").value = "1";
	document.getElementById("tx12").value = sTel;
	document.getElementById("txAdd1").value = sName;
	document.getElementById("tx15").value = "BOGOTA D.C.";
	cbDep_Change();
	hidcap('dHotels');
	//-------------------------------
	//Foco grupo segmento
	document.getElementById("tx19").focus();
}
//--------------------------------------------
//Funcion para buscar hotel
function txHotel_Find()
{
	var txhot = document.getElementById("lookHotel");
	var tbhot = document.getElementById("lstHotel");
	//-------------------------------------------------
	//Define string de consulta
	var strSQLC = '';
	if(txhot.value.length >= 3)
	{
		strSQLC = "SELECT * FROM XConf_Hoteles WHERE Nombre_Hotel LIKE'%" + txhot.value + "%' Order By Nombre_Hotel";
	} else {
		strSQLC = "SELECT * FROM XConf_Hoteles Order By Nombre_Hotel";
	}
	//-----------------------------------
	//Hace consulta
	viscap('dWait');		
	tbhot.innerHTML = GenConretField('ajax/frOpsClientes', 'Find_Hotel', strSQLC, false);
	hidcap('dWait');		
}

//---------------------------------------------
//Aceptar Cliente
function cmAccept_Clic(doPic)
{
	//Construye variables
	//Identificacion
     document.getElementById("tx1").value = document.getElementById("tx2").value.toString() + document.getElementById("tx3").value;
	//Nacionalidad Ciudad
	if(document.getElementById("Ciudad").value != '')
	{
		document.getElementById("tx10").value = document.getElementById("Nacionalidad").value + "-" + document.getElementById("Ciudad").value;
	} else {
		document.getElementById("tx10").value = document.getElementById("Nacionalidad").value;
	}
	//Direccion
	var sAb1 = '';
	if(document.getElementById("cbAdd1").value != ''){sAb1 = document.getElementById("cbAdd1").value + ' ';}
	var sAb2 = '';
	if(document.getElementById("cbAdd2").value != ''){sAb2 = document.getElementById("cbAdd2").value + ' ';}
	var sAd1 = '';
	if(document.getElementById("txAdd1").value != ''){sAd1 = document.getElementById("txAdd1").value + ' ';}
	document.getElementById("tx14").value = sAb1 + sAd1 + sAb2 + document.getElementById("txAdd2").value;
	//Ciudad
	document.getElementById("tx16").value = document.getElementById("Ciudad_1").value;
	//Segmento
	document.getElementById("tx20").value = document.getElementById("Segmento").value;
	//Fecha nacimiento
	document.getElementById("tx23").value = document.getElementById("cbYear").value.toString() + "-" + document.getElementById("cbMonth").value + "-" + document.getElementById("cbDay").value;
	//Fecha ultima operacion
	document.getElementById("tx24").value = hoyday();
	//---------------------------------------------
	
	//Validaciones

	//Campos obligatorios
	if(fEmpty(30, 1) == true) {return 0;}
	//Valida si es nit o rut que haya digito de verificacion
	if(document.getElementById("tx3").value == '' && (document.getElementById("tx4").value == 'NIT' || document.getElementById("tx4").value == 'RUT'))
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el tipo de documento es NIT o RUT, debe escribir el dígito de verificación.", 1);
		return false;
	}
	//Valida caso contrario
	if(document.getElementById("tx3").value != '' && (document.getElementById("tx4").value != 'NIT' && document.getElementById("tx4").value != 'RUT'))
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el tipo de documento no es NIT o RUT, no debe escribir el dígito de verificación.", 1);
		return false;
	}
	//Direccion
	if(document.getElementById("tx14").value.length > 60){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La dirección del cliente no puede contener más de 60 caracteres. Por favor verifique la información de la dirección.", 1);
		return false;
	}
	//Telefono
	if(document.getElementById("tx12").value != '' && document.getElementById("tx12").value.length < 7){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El teléfono fijo no puede contener menos de 6 caracteres. Por favor verifique la información del número telefónico.", 1);
		return false;
	}
	
	if(document.getElementById("tx12").value == '' && document.getElementById("tx13").value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Debe registrar por lo menos el teléfono fijo o el teléfono celular OJO.", 1);
		return false;
	}
	//Si nac colombia, seleccion de ciudad
	if(document.getElementById("Nacionalidad").value == 'COLOMBIA' && document.getElementById("Ciudad").value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si la nacionalidad del cliente es Colombia, debe seleccionar una ciudad.", 1);
		return false;
	}
	//--------------------------------------------------------
	//Ingresa información a base de datos
	viscap('dbloc');
	viscap('dWait');
	var isuc = genaccept('General', 'Gen_Accept', 30, 'Clientes');
	//--------------------------------------------------------
	//Activación de controles
	enabtn('btmodif');
	disbtn('btaccept');
	//Imagenes si esta activado
	if(doPic == 'SI')
	{
		//Activa camara
		Webcam.set({
			width: 270,
			height: 114,
			dest_width: 540,
			dest_height: 228,
			image_format: 'jpeg',
			jpeg_quality: 70
		});
		Webcam.attach( '#my_camera' );
		enabtn('btphoto');
		console.log(Webcam);
	}
	//-----------------------------------------------------
	//Habilita diferente beneficiario
	var rbDec = document.getElementsByName("rbDecben");
	if(rbDec[1].checked){
		enabtn('btben');
	}
	if(rbDec[0].checked){   //Mismo beneficiario
		enabtn('btgocom');
		enabtn('btgoven');
	} else if(rbDec[1].checked && document.getElementById("txDifBCh").value == ''){ //Diferente y beneficiario sin buscar
		disbtn('btgocom');
		disbtn('btgoven');
	} else {
		enabtn('btgocom');
		enabtn('btgoven');
	}
	//-----------------------------------------------------
	//Mensaje exitoso
	hidcap('dWait');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc'); ctrfocus('btmodif')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
}
//---------------------------------------------
//Modificar Cliente
function cmModif_Clic()
{
	//Nacionalidad Ciudad
	if(document.getElementById("Ciudad").value != '')
	{
		document.getElementById("tx10").value = document.getElementById("Nacionalidad").value + "-" + document.getElementById("Ciudad").value;
	} else {
		document.getElementById("tx10").value = document.getElementById("Nacionalidad").value;
	}
	//Direccion
	var sAb1 = '';
	if(document.getElementById("cbAdd1").value != ''){sAb1 = document.getElementById("cbAdd1").value + ' ';}
	var sAb2 = '';
	if(document.getElementById("cbAdd2").value != ''){sAb2 = document.getElementById("cbAdd2").value + ' ';}
	var sAd1 = '';
	if(document.getElementById("txAdd1").value != ''){sAd1 = document.getElementById("txAdd1").value + ' ';}
	document.getElementById("tx14").value = sAb1 + sAd1 + sAb2 + document.getElementById("txAdd2").value;
	//Ciudad
	document.getElementById("tx16").value = document.getElementById("Ciudad_1").value;
	//Segmento
	document.getElementById("tx20").value = document.getElementById("Segmento").value;
	//Fecha nacimiento
	document.getElementById("tx23").value = document.getElementById("cbYear").value.toString() + "-" + document.getElementById("cbMonth").value + "-" + document.getElementById("cbDay").value;
	//Fecha ultima operacion
	if(document.getElementById("tx24").value == '')
	{
		document.getElementById("tx24").value = hoyday();
	}
	//---------------------------------------------
	//Validaciones
	
	//validacion del correo electronico 17/02/2023 juan camilo
	var txmail = document.getElementById('tx18');
    if (!validarEmail(txmail.value)) {
    return false;
    }
	
	
	//Campos obligatorios
	if(fEmpty(30, 1) == true) {return 0;}
	//Valida si es nit o rut que haya digito de verificacion
	if(document.getElementById("tx3").value == '' && (document.getElementById("tx4").value == 'NIT' || document.getElementById("tx4").value == 'RUT'))
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el tipo de documento es NIT o RUT, debe escribir el dígito de verificación.", 1);
		return false;
	}
	//Valida caso contrario
	if(document.getElementById("tx3").value != '' && (document.getElementById("tx4").value != 'NIT' && document.getElementById("tx4").value != 'RUT'))
	{
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el tipo de documento no es NIT o RUT, no debe escribir el dígito de verificación.", 1);
		return false;
	}
	//Direccion
	if(document.getElementById("tx14").value.length > 60){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La dirección del cliente no puede contener más de 60 caracteres. Por favor verifique la información de la dirección.", 1);
		return false;
	}
	//Telefono
	if(document.getElementById("tx12").value != '' && document.getElementById("tx12").value.length < 7){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El teléfono fijo no puede contener menos de 6 caracteres. Por favor verifique la información del número telefónico.", 1);
		return false;
	}

	if(document.getElementById("tx12").value == '' && document.getElementById("tx13").value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Ojo Debe registrar por lo menos el teléfono fijo o celular.", 1);
		return false;
	}

	//Si nac colombia, seleccion de ciudad
	if(document.getElementById("Nacionalidad").value == 'COLOMBIA' && document.getElementById("Ciudad").value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si la nacionalidad del cliente es Colombia, debe seleccionar una ciudad.", 1);
		return false;
	}
	//----------------------------------------------------
	//Pregunta confirmación de modificacion de cliente
	viscap('dbloc');
	dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); viscap('dWait'); cmModif_Clic_1()", "hidcap('dMsj" + 1 + "'); hidcap('btcancel" + 1 + "'); hidcap('dbloc')", "¿Confirma que desea modificar los datos del cliente con documento " + document.getElementById("tx1").value +"?", 1);
}
//Continuación modificar cliente
function cmModif_Clic_1()
{
    var swhere = "Identificacion = '" + document.getElementById("tx1").value + "'";
	var isuc = genmodif('General', 'Gen_Modif', 30, 'Clientes', swhere);
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha modificado exitosamente en la base de datos.", 1);
		//Selecciona boton de mensaje y desabilita aceptar de formulario
		document.getElementById("btaccept1").focus;
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
//--------------------------------------------------------------
//Funcion para ir a compra o a venta
function cmGo_Ops(sOps, sPer, sSuc, sCaja, sUser, sDopic)
{
	//Valida si tiene acceso para ir a compra o venta
	if(sPer == '0')
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "No tiene acceso a la herramienta que está solicitando.", 1);
		return false;		
	}
	
	//validacion del correo electronico 17/02/2023 juan camilo
	var txmail = document.getElementById('tx18');
    if (!validarEmail(txmail.value)) {
    return false;
    }
	//-----------------------------------
	//Valida ciudad nacionalidad y correo electrónico
	var txncity = document.getElementById('Ciudad');
	var txmail = document.getElementById('tx18');
	if(txncity.value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Por favor ingrese la ciudad de nacimiento del cliente.", 1);
		txncity.focus();
		return false;		
	}
	if(txmail.value == ''){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Por favor ingrese el correo electrónico del cliente.", 1);
		txmail.focus();
		return false;		
	}
		//17/02/2023 juan camilo, no permite campo vacio
	if(document.getElementById("tx21").value.trim() == ''){
        viscap('dbloc');
         dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El campo de origen de fondos. no puede estar vacío. Por favor verifique la información.", 1);
        return false;
    }
    
    
	
	//-----------------------------------
	//Valida hay fotos y la conf cambiaria para pasar a la operación
	var iR = parseInt(document.getElementById("lstCODList").rows.length);	//Obtiene la cantidad de filas en tabla de captura de imagenes		
	if(sDopic == 'SI' && iR == 0){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Debe capturar por lo menos 1 imágen del documento de identificación del cliente.", 1);
		return false;		
	}
	//-------------------------------------
	//Define variables
	var sDecId = '';
	var sCliId = '';
	var sNew = '';
	sDecId = document.getElementById("tx1").value;
	var rbDec = document.getElementsByName("rbDecben");
	if(rbDec[0].checked){   //Mismo beneficiario
		sCliId = document.getElementById("tx1").value;
		sNew = document.getElementById("txCODNew").value;
	} else {
		sCliId = document.getElementById('frBen').contentWindow.document.getElementById('tx1').value;
		sNew = document.getElementById('frBen').contentWindow.document.getElementById('txCODNew').value;
	}
	//Abre ventana
	var dfifr = window.parent.document.getElementById('frMain');
	dfifr.src = sOps + '.php?var1=' + sDecId + '&var2=' + sCliId + '&var3=' + sNew + '&var4=' + sSuc + '&var5=' + sCaja + '&var6=' + sUser;
}