<?php
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
	    header('Content-Type: text/html; charset=utf-8');
    }
	require '../inc/params.php';
	error_reporting(E_ALL ^ E_NOTICE);
	//----------------------------------------------------
	ini_set('memory_limit', '2048M');
	set_time_limit(0);
	//----------------------------------------------------

	if (!($link=mysqli_connect($db['host'],$db['user'],$db['pass'],$db['db']))){
		echo "Error conectando a la base de datos.";
		exit();
	}

	//----------------------------------------------------
	//Formato de fecha para mostrar
	setlocale(LC_TIME, 'es_ES');
	date_default_timezone_set('America/Bogota');
	//--------------------------------------------
	
	
	// Special action: update invoice date
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
	    header('Content-Type: application/json; charset=utf-8');
	    echo json_encode([
	        'completed' => true,
	        'time' => time()
	    ]);
	    exit;
	}
	// End special action
	
	//Valida par�metros
	//$repDate = '';
	/*
	if(!isset($_GET['repdate']) || $_GET['repdate'] == ''){
		echo 'Defina la fecha para la generaci�n del reporte';
		exit();
	} else {
	*/
	$repDate = (isset($_GET['repdate'])) ? $_GET['repdate'] : date('Y-m-d');
	//}
	//--------------------------------------------
	//Datos en el reporte
	$limit = (isset($_REQUEST['replimit'])) ? $_REQUEST['replimit'] : 20;

    /*
	$strSQT0 = "SELECT COUNT(*) AS total FROM Factura_Electronica 
	LEFT JOIN Operacion_Ventanilla ON Factura_Electronica.InvoiceId = Operacion_Ventanilla.Identificacion
	WHERE Operacion_Ventanilla.Fecha='".$repDate."' AND Factura_Electronica.SendStatus='".$_REQUEST['repstatus']."'";
	$t0 = mysqli_query($link, $strSQT0) or die(mysqli_error($link));
	$countResults = mysqli_fetch_array($t0)['total'];
	
	$totalPages = ceil($countResults / $limit);
	$currentPage = isset($_GET['repage']) ? $_GET['repage'] : 1;
	*/

	// Calcular el índice inicial y final de los registros a mostrar en la página actual
	//$offset = ($currentPage - 1) * $limit;
	//$indiceFinal = $offset + $limit - 1;

	$strSQ0 = "SELECT Factura_Electronica.*, Operacion_Ventanilla.Fecha, Operacion_Ventanilla.Hora FROM Factura_Electronica ";
	$strSQ0.= "LEFT JOIN Operacion_Ventanilla ON Factura_Electronica.InvoiceId = Operacion_Ventanilla.Identificacion ";
	$strSQ0.= "WHERE 1";
	$strSQ0.= " AND Operacion_Ventanilla.Fecha='".$repDate."'";
	$strSQ0.= (!empty($_REQUEST['repstatus'])) ? " AND Factura_Electronica.SendStatus='".$_REQUEST['repstatus']."'" : NULL;
	$strSQ0.= " ORDER BY Factura_Electronica.RowId ASC";
	$p0 = mysqli_query($link, $strSQ0) or die(mysqli_error($link));
	$countResults = mysqli_num_rows($p0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte envío facturas SIIGO Api - NEW YORK MONEY</title>
<META NAME="robots" CONTENT="noindex, nofollow"></META>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="../favicon.ico">
</head>
<body class="bodygen bgcol">
	<h1 style="text-align:center;">REPORTE ENVÍO FACTURAS A SIIGO - API</h1>

	<fieldset class="filter-bar">
	    <legend>Filtrar por</legend>
		<form>
			<label>
				Fecha:<br>
				<input type="date" name="repdate" value="<?=$repDate?>" onchange="this.form.submit()" />
			</label>
			
			<label>
				Estado:<br>
				<select name="repstatus" onchange="this.form.submit()">
				    <option value="">Todos</option>
					<option value="Enviada"<?=(isset($_REQUEST['repstatus']) && $_REQUEST['repstatus'] == 'Enviada') ? ' selected' : NULL?>>Enviados</option>
					<option value="Por Enviar"<?=(isset($_REQUEST['repstatus']) && $_REQUEST['repstatus'] == 'Por Enviar') ? ' selected' : NULL?>>Por enviar</option>
					<option value="Error"<?=(isset($_REQUEST['repstatus']) && $_REQUEST['repstatus'] == 'Error') ? ' selected' : NULL?>>Error</option>
				</select>
			</label>
			<button type="submit">Buscar</button>
		</form>
	</fieldset>
	
	<?php
	    /*
	    echo '<div class="pagination_line">';
	        echo '<div>Páginas:</div>';
    		echo '<div class="pagination">';
        		for ($i = 1; $i <= $totalPages; $i++) {
        			echo '<a href="'.$_SERVER['PHP_SELF'].'?repage=' . $i . '&repdate='.$_REQUEST['repdate'].'&replimit='.$_REQUEST['replimit'].'">' . $i . '</a> ';
        		}
    		echo '</div>';
		echo '</div>';
		*/
	?>
	
	<h2 style="text-align:center">Total registros: <?=$countResults?></h2>

	<table class="tbl_result">
		<thead>
			<tr class="bgcol_6 fwhite">
				<th class="celrow" style="text-overflow:visible;">Identificación de Registro</th>
				<th class="celrow" style="text-overflow:visible;">Identificación Factura</th>
				<th class="celrow" style="text-overflow:clip;">Tipo de Proceso</th>
				<th class="celrow" style="text-overflow:visible;">Estado de Envío</th>
				<th class="celrow" style="text-overflow:visible;">Fecha de factura</th>
				<th class="celrow" style="text-overflow:visible;">Última Actualización</th>
				<th class="celrow" style="text-overflow:visible;">Respuesta Api SIIGO</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while($q0=mysqli_fetch_array($p0)){
			?>
			<tr class="api-report-data-tr"<?=($q0['SendStatus'] == 'Error') ? ' style="background:#ffc4c4;"' : NULL?>>
				<td class="celrow"><?=$q0['RowId']?></td>
				<td class="celrow"><?=$q0['InvoiceId']?></td>
				<td class="celrow"><?=$q0['SendProcess']?></td>
				<td class="celrow"><?=$q0['SendStatus']?></td>
				<td class="celrow"><?=$q0['Fecha']?></td>
				<td class="celrow"><?=$q0['LastUpdate']?></td>
				<td class="celrow"><?php 
				    if(preg_match('/invalid_date/', $q0['ApiResponse'])) {
				        echo 'Establecer nueva fecha:<br><input type="date" name="invoice_date[]" class="input_upd_date" data-invoice="'.$q0['InvoiceId'].'" value="'.date('Y-m-d').'">';
				    } else {
				        echo $q0['ApiResponse'];
				    }
				    
				?></td>
			</tr>
			<? } ?>
		</tbody>
	</table>

	<?php
	    /*
	    echo '<div class="pagination_line">';
	        echo '<div>Páginas:</div>';
    		echo '<div class="pagination">';
        		for ($i = 1; $i <= $totalPages; $i++) {
        			echo '<a href="'.$_SERVER['PHP_SELF'].'?repage=' . $i . '&repdate='.$_REQUEST['repdate'].'&replimit='.$_REQUEST['replimit'].'">' . $i . '</a> ';
        		}
    		echo '</div>';
		echo '</div>';
		*/
	?>
	
	<style>
	    .filter-bar{border:solid 1px #CCC; padding:5px; width:600px; margin:10px auto 30px;}
	    .filter-bar form{display:flex; flex-direction:row; gap:20px; align-items:center; padding:10px 0; justify-content:space-around; }
	    .filter-bar form label{ font-size:12px; }
	    .filter-bar form label input{ padding:5px; border:solid 1px #ccc; font-family:sans-serif; font-size:18px; margin-top:5px; }
	    .filter-bar form label select{ padding:5px; border:solid 1px #ccc; font-family:sans-serif; font-size:18px; margin-top:5px; }
	    .filter-bar form button{background:#32B432;color:#FFFFFF; border-radius:50px; border:0 none; cursor:pointer; padding:10px 20px; font-weight:bold;}
	    .pagination_line{ margin:10px auto; width:100%; display:flex; font-size:14px; gap:20px; align-items:center; justify-content:center; }
	    .pagination_line .pagination{ padding-top:10px; display:flex; width:600px; gap:5px;}
	    .pagination_line .pagination a{ border:solid 1px #32B432; padding:5px; color:#32B432; text-decoration:none; font-size:18px; }
	    .pagination_line .pagination a:hover{background:#32B432; color:#fff;}
		.tbl_result{ margin:auto; border:solid 1px #32B432; }
		.tbl_result thead tr th{ padding:5px;}
	</style>
	<script>
        function changeDate(e) {
            e.preventDefault();
            var element = e.target;
            var invoiceId = element.dataset.invoice;
            var newDate = element.value;
            element.disabled = true;
            
            fetch("<?=$_SERVER['PHP_SELF']?>", {
                  method: "POST",
                  body: JSON.stringify({
                    action: "setNewDateInvoice",
                    invoiceId,
                    newDate
                  }),
                  headers: {
                    "Content-type": "application/json; charset=UTF-8"
                  }
            })
            .then((response) => response.json())
            .then((data) => {
                if(data.completed) {
                    element.parentNode.parentNode.removeAttribute('style');
                    element.parentNode.innerHTML = '';
                }
            });
            
        }
        const selectElementAll = document.querySelectorAll("input.input_upd_date");
        selectElementAll.forEach((item) => {
            item.addEventListener("change", (event) => { changeDate(event); });
        });
    </script>
</body>
</html>