// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCumpCalifica.PHP
//=============================================================
//Funcion cambio tipo de operacion
function cbOps_Change()
{
	//Pone controles en cero
	for(i = 2; i <= 9; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		evtx.value = '0';
	}
	var sTot = document.getElementById('txTot');
	sTot.value = '0';
	//-----------------------------------------
	//Consulta calificaciones
	var cbOps = document.getElementById('tx1');
	if(cbOps.value != '')
	{
		viscap('dbloc');
		viscap('dWait');
		//Consulta las tasas de la estacion y sucursal
		var swhere = "Operacion = '" + cbOps.value + "'";
		var iFindCal = genfind('General', 'Gen_Find', 9, 'Calificacion_Alerta', swhere, 1);	
		//------------------------------------
		//Llama función para calcular total
		if(iFindCal == 1){Cal_Tot();}
		hidcap('dbloc');
		hidcap('dWait');
	}
} 
//-------------------------------------
//Funcion para calcular total
function Cal_Tot()
{
	var sTot = document.getElementById('txTot');
	var dSum = 0;
	//Recorre controles para hacer suma
	for(i = 2; i <= 9; i++){
		var evtx = '';
		evtx = document.getElementById('tx' + i); 
		if(isNaN(evtx.value) == false){
			dSum = dSum + parseFloat(evtx.value);		
		}
	}
	//--------------------------------------------
	//Pone resultado
	sTot.value = NumFormProp(dSum);
}
//-------------------------------------
//Funcion para modificar calificaciones
function Accept_Cal()
{
	//Valida campos obligatorios
	if(fEmpty(9, 1) == true) {return 0;}
	//---------------------------------------------------
	//Valida suma total = 10
	var sTot = document.getElementById('txTot');
	if(parseInt(sTot.value) != 10)
	{
		viscap('dbloc');
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "La calificación total debe ser igual a 10. Verifique la sumatoria de calificaciones.", 1);
		return false;	
	}		
	//---------------------------------------------------
	viscap('dbloc');
	viscap('dWait');
	//---------------------------------------------------
	//Funcion generica modificart
	var cbOps = document.getElementById('tx1');
	var swhere = "Operacion = '" + cbOps.value + "'";
	var isuc = genmodif('General', 'Gen_Modif', 9, 'Calificacion_Alerta', swhere);
	//--------------------------------------------------
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
	hidcap('dWait');
}
