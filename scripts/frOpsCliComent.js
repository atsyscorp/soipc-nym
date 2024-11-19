// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frClicComent.PHP
//=============================================================
//Funcion para load carga fecha
function frOpsCliComent_Load(stops, scops)
{
	ValueCtr('tx7', hoyday());
	if(stops != '')
	{
		ValueCtr('tx2', scops);
		ValueCtr('tx3', stops);
	}
}
//------------------------------------------
//Cambio de seleccion de tipo de operacion consulta código
function TipoOps_Change()
{
	var sTipo = document.getElementById('tx3');
	if(sTipo.value != '')
	{	
		viscap('dWait');
		var strSQL = "SELECT Codigo FROM XConf_Consecutivos WHERE Documento = '" + sTipo.value + "'"; 
		ValueCtr('tx2', GenConretField('General', 'Gen_Find_Field', strSQL, false));
		hidcap('dWait');
	}
}
//------------------------------------------------
//Funcion para consultar nombre de cliente
function FindCli(sidcli, sres)
{
	var txidcli = document.getElementById(sidcli);
	if(txidcli.value != '')
	{
		viscap('dWait');
		var strSQL = "Select Nombre_Completo From Clientes Where Identificacion='" + txidcli.value + "'";
		ValueCtr(sres, GenConretField('General', 'Gen_Find_Field', strSQL, false));
		hidcap('dWait');
	}	
}
//-------------------------------------------
//Funcion aceptar comentario
function cmAccept_Clic()
{
	//Campos vacíos
	if(fEmpty(14, 1) == true) {return 0;}
	//Construye id de operacion
    document.getElementById('tx1').value = dateid() + "COMNT" + document.getElementById('tx4').value + document.getElementById('tx5').value + document.getElementById('tx6').value;
	//---------------------------------------
	//Acepta operacion
	viscap('dbloc');
	var isuc = genaccept('General', 'Gen_Accept', 14, 'Comentarios_Clientes');
	//--------------------------------------
	if(isuc == 10)
	{
		dmsshowc("drod_1 dlin_6 bgcol_1", "fcont", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", "El registro se ha guardado exitosamente en la base de datos.", 1);
	} else {
		dmsshowc("drod_1 dlin_5 bgcol_3", "falert", "hidden", "hidcap('dMsj" + 1 + "'); hidcap('dbloc')", "", isuc, 1);
	}
}