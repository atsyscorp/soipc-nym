// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frOpsAjustes.PHP
//=============================================================
//Funcion para load de ventana
function frOpsAjustes_Load(sCaja, sSuc)
{
	//Fecha de ajuste
	ValueCtr('tx7', hoyday());
	//-----------------------------------------------------
	//Carga de listados
	//Origen Destino
	var cbOrDes = document.getElementById("tx10")
    if(sCaja == '01')
	{
		cbOrDes.innerHTML = cbOrDes.innerHTML + '<option value="TESORERIA">TESORERIA</option>'; 	
		cbOrDes.innerHTML = cbOrDes.innerHTML + '<option value="COORDINACION">COORDINACION</option>'; 	
		cbOrDes.innerHTML = cbOrDes.innerHTML + '<option value="COORDINACION">OTROS</option>'; 	
		var strSQLN = "SELECT Codigo_Sucursal FROM Sucursales WHERE Codigo_Sucursal <> '" + sSuc + "'";
		cbOrDes.innerHTML = cbOrDes.innerHTML + GenConretField('ajax/frOpsAjustes', 'Add_OrgDest', strSQLN, false);
	}
	//-----------------------------------------------------
	//Carga cajas
	var strSQL = "SELECT Cajas FROM Sucursales WHERE Codigo_Sucursal = '" + sSuc + "'";
    var iEst = GenConretField('General', 'Gen_Find_Field', strSQL, false);
	if(iEst != '' && isNaN(iEst) == false)
	{
		for(i = 1; i <= parseInt(iEst); i++){
			if(i < 10){
				cbOrDes.innerHTML = cbOrDes.innerHTML + '<option value="Caja 0' + i + '">Caja 0' + i + '</option>';
			} else {
				cbOrDes.innerHTML = cbOrDes.innerHTML + '<option value="Caja ' + i + '">Caja ' + i + '</option>';
			}
		}	
	}	
}
//-----------------------------------------
//Funcion para liquidar valores
function txCalc()
{
	var txPr = document.getElementById('tx13');
	var txCa = document.getElementById('tx14');
	var txVa = document.getElementById('tx15');
	if(isNaN(DelMilsepa(txPr.value)) == false && isNaN(DelMilsepa(txCa.value)) == false)
	{
        txVa.value = (parseFloat(DelMilsepa(txPr.value)) * parseFloat(DelMilsepa(txCa.value))).toFixed(2);
		txChange_Num('tx15');
	} else {
		txVa.value = '0';
	}
}
//-----------------------------------
//Funcion cambio tipo de movimiento
function cbType_Change()
{
   // Limpiar el contenido de tx10
    document.getElementById('tx10').value = '';  // Esto limpia el valor de tx10

	var cbType = document.getElementById('tx3');
	if(cbType.value != '')
	{
		//Habilita o deshabilita controles
		enabtn('btaccept');
		disbtn('btprint');
		ValueCtr('tx11', '');
		if(cbType.value == 'EGRESO')
		{

			document.getElementById("tx11").disabled = true;
			document.getElementById("tx11").className = 'txboxdis';
		} else {
			document.getElementById("tx11").disabled = false;
			document.getElementById("tx11").className = 'txbox';
		}
		//--------------------------------------
		//Hace consulta de consecutivo y codigo movimiento
		viscap('dWait');
		document.getElementById('tx8').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Documento = '" + cbType.value + "'", false);
		document.getElementById('tx2').value = GenConretField('General', 'Gen_Find_Field', "Select Codigo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Documento = '" + cbType.value + "'", false);
		hidcap('dWait');
	}
}

// Funcion para pedir contraseña si selcciona tesoreria coordinacion u otros
function cbDestino_Change() {
  var cbDestino = document.getElementById('tx10');
  var btnAceptar = document.getElementById('btaccept');

 // Verifica si cbDestino es nulo o indefinido
  if (!cbDestino) {
    console.error("Elemento con id 'tx10' no encontrado.");
    return;
  }
  
  if (cbDestino.value != '') {
    // Si el destino es TESORERIA, COORDINACION u OTROS, pide clave
    if (cbDestino.value == 'TESORERIA' || cbDestino.value == 'COORDINACION' || cbDestino.value == 'OTROS') {
      var password = prompt("Ingrese la clave para " + cbDestino.value + ":");

      if (password === null) {
        alert("Operacion cancelada por el usuario.");
        // Aquí puedes realizar acciones adicionales si el usuario cancela
 	disbtn('btaccept');// Deshabilita el botón
      
      } else {
        // Validar la contraseña para cada destino
        switch (cbDestino.value) {
          case 'TESORERIA':
            if (password !== 'tesoreria2023') {
              alert("clave incorrecta para " + cbDestino.value + ". Operación cancelada.");
              // Aquí puedes realizar acciones adicionales si la contraseña es incorrecta
           disbtn('btaccept');// Deshabilita el botón
            }
            break;
          case 'COORDINACION':
            if (password !== 'coordinacion2023') {
              alert("Clave incorrecta para " + cbDestino.value + ". Operación cancelada.");
              // Aquí puedes realizar acciones adicionales si la contraseña es incorrecta
             disbtn('btaccept');// Deshabilita el botón
            }
            break;
          case 'OTROS':
            if (password !== 'otros2023') {
              alert("Clave incorrecta para " + cbDestino.value + ". Operacion cancelada.");
              // Aquí puedes realizar acciones adicionales si la contraseña es incorrecta
           disbtn('btaccept');// Deshabilita el botón
            }
            break;
          // Agrega más casos según sea necesario
          default:
            alert("Destino no reconocido. Operacion cancelada.");
            // Aquí puedes realizar acciones adicionales si el destino no es reconocido
          disbtn('btaccept');// Deshabilita el botón
            break;
        }
      }
    } else {
      // Contraseña correcta o destino que no requiere contraseña
      // Habilita el botón
      enabtn('btaccept');
    }
  }
}

//--------------------------------------------
//Funcion cambio de seleccion medio de pago
function cbMedPay_Change()
{
	var cbMedPay = document.getElementById('tx16');
	if(cbMedPay.value != '')
	{
		viscap('dWait');
		//Limpia controles	
		InnerCtr('tx17', '<option value=""></option>');
		InnerCtr('tx18', '<option value=""></option>');
		//--------------------------------------------------------------
        //Configura controles dependiendo de selección
		if(cbMedPay.value == 'EFECTIVO')
		{
			document.getElementById("tx17").disabled = true;
			document.getElementById("tx17").className = 'txboxdis';
			document.getElementById("tx18").disabled = true;
			document.getElementById("tx18").className = 'txboxdis';
		} else {
			document.getElementById("tx17").disabled = false;
			document.getElementById("tx17").className = 'txboxo';
			document.getElementById("tx18").disabled = false;
			document.getElementById("tx18").className = 'txboxo';
			//Carga opciones de medio de pago
			var cbBak = document.getElementById("tx17");
			var strSQLB = "SELECT DISTINCT Banco FROM Cuentas_Bancarias";
			cbBak.innerHTML = cbBak.innerHTML + GenConretField('ajax/frOpsAjustes', 'Add_OrgDest', strSQLB, false);
		}
		hidcap('dWait');
	}
}
//-------------------------------------------
//Funcion cambio de selccion de moneda
function cbMoneda_Change(sCop)
{
	var cbCurr = document.getElementById("tx12");
	if(cbCurr.value != '')
	{
		if(cbCurr.value == sCop)
		{
			ValueCtr('tx13', '1');
			document.getElementById("tx13").disabled = true;
		} else {
			document.getElementById("tx13").disabled = false;
			viscap('dWait');
			var strSQL = "Select Precio_Base From Tasas Where Moneda='" + cbCurr.value + "' AND Sucursal='" + document.getElementById("tx4").value + "' AND Estacion ='01'";
			document.getElementById('tx13').value = NumFormProp(GenConretField('General', 'Gen_Find_Field', strSQL, false));
			hidcap('dWait');
		}	
	} else {
		ValueCtr('tx13', '0');
	}
}
//-------------------------------------------
//Funcion cambio de selección de banco
function cbBank_Change()
{
	var cbBank = document.getElementById('tx17');
	//Limpia controles cuentas
	InnerCtr('tx18', '<option value=""></option>');
	if(cbBank.value != '')
	{
		viscap('dWait');
		var cbCon = document.getElementById("tx18");
		var strSQL = "SELECT Numero_Cuenta FROM Cuentas_Bancarias WHERE Banco = '" + cbBank.value + "'";
		cbCon.innerHTML = cbCon.innerHTML + GenConretField('ajax/frOpsAjustes', 'Add_OrgDest', strSQL, false);
		hidcap('dWait');
	}
}

// JuanC crea esta funcion el 15/02/2023, para que un campo no traiga simbolos, se creo espesificamente para que el tx19 que es el de observaciones de los ajustes de recursos, no tenga simbolos
function validarCampoSinSimbolos(campoId) {
  var campo = document.getElementById(campoId);
  var valor = campo.value;
  var regex = /^[a-zA-Z0-9\s]+$/; // Expresión regular para permitir solo letras, números y espacios en blanco

  if (!regex.test(valor)) {
    viscap('dbloc');
    dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El campo no puede contener simbolos.", 1);
    return false;
  }

  return true;
}


//----------------------------------------
//Funcion aceptar ajuste
function Accept_Ajuste()
{
	//Validaciones
	
	// validacion de precio promedio no mayor al 5% del promedio del sistema
	// Validación de precio promedio
  	var cbMoneda = document.getElementById('tx12');
  	var precioBase = 0;

  	if (cbMoneda.value !== 'COP') {
   	 viscap('dWait');
    	var strSQL = "Select Precio_Base From Tasas Where Moneda='" + cbMoneda.value + "' AND Sucursal='" + document.getElementById("tx4").value + "' AND Estacion ='01'";
    	precioBase = parseFloat(DelMilsepa(NumFormProp(GenConretField('General', 'Gen_Find_Field', strSQL, false))));
    	hidcap('dWait');
  	}

  	var txPrecio = document.getElementById('tx13').value;

  	if (precioBase > 0 && !validarPrecioPromedio(precioBase, txPrecio)) {
    	return false;
 	 }
	
	
	
	
	
	//Fecha de cierre
	var dnow = new Date(document.getElementById('tx7').value);
	var dcls = new Date(window.parent.document.getElementById('txMCCierre').value);
	if(dnow <= dcls){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La fecha del ajuste no puede ser menor o igual a la fecha del ultimo cierre.", 1);
		return false;	
	}
	
	//campo de observaciones validacion
	if (!validarCampoSinSimbolos('tx19')) {
  return false;
}
	
	
	//Campos vacíos
	if(fEmpty(19, 1) == true) {return 0;}
	//Medio de pago bancos --> Que haya seleccionado las opciones de banco
	if(document.getElementById('tx16').value == "BANCOS" && (document.getElementById('tx17').value == '' || document.getElementById('tx18').value == '')){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "Si el medio de pago es BANCOS, debe indicar el nombre del banco y numero de cuenta.", 1);
		return false;	
	}
	//Valida que valor de ajuste no sea cero
	if(DelMilsepa(parseInt(document.getElementById('tx15').value)) == '0'){
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El valor del ajuste no puede ser igual a cero (0).", 1);
		return false;
	}
	//----------------------------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//-----------------------------------------------------------------
	//Actualiza consecutivo de operación por si entro otra antes
	//document.getElementById('tx8').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '" + document.getElementById('tx2').value + "'", false);
	//Suma unidad a consecutivo y trae el actual
	var sConsM = GenConretField('General', 'AddSerieNew', document.getElementById('tx2').value + document.getElementById('tx4').value, false);
	document.getElementById('tx8').value = sConsM;
	//-----------------------------------------------------------------
    //Construcción de variables pendientes
    //Id de operacion
    document.getElementById('tx1').value = dateid() + "AR" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx8').value
    //-----------------------------------------------------------------
    //Ingresa operación a base de datos y envío Web
	var isuc = genaccept('General', 'Gen_Accept', 19, 'Traslados_Ventanilla');
	//Hace registro temporal si es egreso
	var sType = document.getElementById('tx3');
	var sDest = document.getElementById('tx10');
	if(sType.value == 'EGRESO' && sDest.value != 'TESORERIA' && sDest.value != 'COORDINACION' && sDest.value != 'OTROS')
	{
		var isucT = genaccept('General', 'Gen_Accept', 19, 'Traslados_Ventanilla_Temp');
	}
	//--------------------------------------------------------------------
	if(isuc == 10)
	{
		//-----------------------------------------------------------------
		//Actualiza tablero y saldos en main
		//Tablero
        MainTable(document.getElementById('tx3').value, document.getElementById('tx8').value, document.getElementById('tx3').value + " de recursos de/para: " + document.getElementById('tx10').value, document.getElementById('tx12').value, document.getElementById('tx13').value, document.getElementById('tx14').value, document.getElementById('tx16').value)
		//-----------------------------------------------------------
		//Actualiza saldo en arqueo rapido	
		if(sType.value == 'INGRESO')
		{
			ActCaja(4, 5, 12, 'Entradas', 14);
		} else {
			ActCaja(4, 5, 12, 'Salidas', 14);
		}
		//-----------------------------------------------------------------
		//Activa botón imprimir y hace pregunta al usuario. En caso afirmativo, ejecuta procedimiento
        enabtn('btprint');
		//----------------------------------------------------------------
		//Mensaje imprimir
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "visible", "Aju_Print('1')", "Aju_Print('0')", "El ajuste de recursos numero " + document.getElementById('tx8').value + " se ha registrado exitosamente. ¿Desea imprimir el formato del ajuste?", 1);
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
		if(document.getElementById('tx3').value == 'EGRESO')
		{
			GenPrint("TRASLADO", document.getElementById('tx1').value, 3);	
		} else {
			GenPrint("TRASLADO", document.getElementById('tx1').value, 2);	
		}
	}
	//------------------------------------------------
	//Vuelve a traer consecutivo 
	document.getElementById('tx8').value = GenConretField('General', 'Gen_Find_Field', "Select Consecutivo From XConf_Consecutivos Where Sucursal ='" + document.getElementById('tx4').value + "' And Codigo = '" + document.getElementById('tx2').value + "'", false);
	//----------------------------------------------------------------------
	disbtn('btaccept');
	hidcap('dbloc');
}
//--------------------------------
//Funcion clic en imprimir
function cmPrint_Clic()
{
	disbtn('btprint');
	if(document.getElementById('tx3').value == 'EGRESO')
	{
		GenPrint("TRASLADO", document.getElementById('tx1').value, 4);	
	} else {
		GenPrint("TRASLADO", document.getElementById('tx1').value, 2);	
	}
}

// Función para validar que el precio promedio editado manualmente no sea mayor un 5% del promedio del sistema juan camilo 15 12 2023
function validarPrecioPromedio(precioBase, precioIngresado) {
  var precioMaximo = precioBase * 1.14; // 13%

  if (parseFloat(DelMilsepa(precioIngresado)) > precioMaximo) {
    viscap('dbloc');
    dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El valor del ajuste no puede superar el 14% del precio promedio.", 1);
    return false;
  }

  return true;
}





