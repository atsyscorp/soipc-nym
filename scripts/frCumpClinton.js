// JavaScript Document
//=============================================================
//FUNCIONES PARA VENTANA frCumpClinton.PHP
//=============================================================
function lstfun(suser){}
//------------------------------------------
//Funcion para exportar tabla de actualizaciones
function Export_Clinton(sCompa)
{
	viscap('dWait');
	ExportToHtml('Lista Clinton', sCompa, 'Actualizaciones Lista Clinton', 'tabClinton');
	hidcap('dWait');
}

//---- agrega juanc el 29/08/2024 arma el archivo lista clinton, elimina ultima fila y lo guarda localmente para luego accederlo
//Nueva función para descargar y procesar el archivo, y luego actualizar la lista
function actualizarYListar(suser) {
    // Primero, ejecuta el archivo PHP para descargar y procesar
    fetch('descargar_y_procesar.php')
        .then(response => response.text())
        .then(data => {
            console.log(data); // Para ver si se ejecuta correctamente

            // Luego, llama a la funcion original de actualizacion de la lista
            Update_List(suser);
        })
        .catch(error => console.error('Error al descargar y procesar:', error));
}
//------------------------------
//Funcion para ir a ventana de actualizacion
function Update_List(suser)
{
	var dfifr = window.parent.document.getElementById('frMain');
	dfifr.src = 'frCumpClinton_1.php?var1=' + suser;
}
//------------------------------
//Funcion para regresar a historia
function Back_List(suser)
{
	var dfifr = window.parent.document.getElementById('frMain');
	dfifr.src = 'frCumpClinton.php?var1=' + suser;
}