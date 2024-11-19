console.log("Archivo tasas.js cargado correctamente.");

//Funciones para pantalla de tasas
//-----------------------------------------------------
//Funcion consulta tasas sucursal
//Consulta constante de saldos
var TasasTime; //--> Variable para el timeout de consulta de Usuarios
itasasecs = 60000;
function ConsTasas(sSuc, nCur)
{
	UpDateTasas(sSuc);
	//Repite proceso
	TasasTime = setTimeout(function() { ConsTasas(sSuc) }, itasasecs);
}
function UpDateTasas(sSuc)
{
	var dtasas = document.getElementById('dTasas'); 
	dtasas.innerHTML = GenConretField('tasas', 'ShowTasas', sSuc, false);
}
//-----------------------------------------------------
//Funcion de fecha
function Lhoyday()
{
	var m_names = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	var d_names = new Array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
	var today = new Date();
	var year = today.getFullYear();
	var month = today.getMonth();
	var day = today.getDate();
	var sday = today.getDay();
	var lfdate = d_names[parseInt(sday)] + ", " + antcero(day) + " de " + m_names[parseInt(month)] + " de " + year;
	InnerCtr('dFecha', lfdate);
}
//---------------------------------------
//Funcion para crear hora hoy larga
function Lhoyhour()
{
	var today = new Date();
	var hour = today.getHours();
	var mins = today.getMinutes();
	var secs = today.getSeconds();
	var stt = '';
	var rhour = '';
	if(hour > 12){
		rhour = antcero(hour - 12);
		stt = 'p.m.'
	} else {
		rhour = antcero(hour);
		stt = 'a.m.'
	}
	var fdate = rhour + ":" + antcero(mins) + " " + stt;
	return fdate;
}
//Timer para cambio de hora
function CalcTimeL()
{
	InnerCtr('dHora', Lhoyhour());
	//Llama funcion de fecha por si no apagan pantallas
	Lhoyday();
}
var ComTimeL; //--> Variable para el timeout 
comsecsL = 1000;
function UpTimeL()
{
	CalcTimeL();
	//Repite proceso
	ComTimeL = setTimeout(function() { UpTimeL() }, comsecsL);
}


// Array de imágenes que deseas rotar
var imagenes = [
    'images/logo.png',
    'images/logoNYM.png',
    'images/imFondo.jpg'
];
var indice = 0;

function cambiarImagen() {
    indice++;
    if (indice >= imagenes.length) {
        indice = 0;
    }
    console.log("Cambiando a imagen: " + imagenes[indice]);  // Verifica si el script se ejecuta
    document.getElementById('banner').src = imagenes[indice];
}

// Inicia la rotación de imágenes inmediatamente
setInterval(cambiarImagen, 5000);

function cambiarImagen() {
    indice++;
    if (indice >= imagenes.length) {
        indice = 0;
    }
    console.log("Cambiando a imagen: " + imagenes[indice]);  // Verifica si el script se ejecuta
    document.getElementById('banner').src = imagenes[indice];
       

}

