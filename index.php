<?php
	error_reporting(E_ALL ^ E_NOTICE);
	header('Content-Type: text/html; charset=utf-8');
	include("General.php");
	//Hace registro de IP de acceso
	$getipl = Regip();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New York Money</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script src="scripts/General.js" type="text/javascript"></script>
<script src="scripts/login.js" type="text/javascript"></script>
</head>
<body class="bodygen bgcol">
<?=$msbloc=dBloc()?>
<div align="center" class="dgen">
	<div align="center" style="width:100%; margin-top:15px;">
		<div align="center" style="width:440px; position:relative">
			<div id="dpup1" style="position:relative; top:0px; left:0px">
				<?=$dwt=MsWait(5, 170)?>
			</div>
			<div id="dpup2" style="position:relative; top:0px; left:0px">
				<?=$msj=MsSucss('drod_1 dlin_5 bgcol_3', 'falert', 5, 350, 45, '', "hidcap('dMsj')", 0, '', '', 'hidden')?>
			</div>
			<form id="freguser" name="freguser" action="frMain.php" method="POST">
			<div id="dpup3" style="position:relative; top:0px; left:0px; visibility:hidden">
				<div class="drod_1 bgcol_1 dtrans_2" style="width:442px; height:273px; position:absolute"></div>
				<div style="position:absolute; top:100px; left:65px;">
					<div style="width:300px; text-align:center; padding:10px; vertical-align:middle" class="drod_1 dlin_1 bgcol_2">
						<span class="fgreen"><b>Seleccione estación:</b></span>
						<select name="tx3" id="tx3" style="width:100px; margin-left:10px" class="txbox">
							<option value=""></option>
						</select>
						<div style="margin-top:7px; text-align:left; margin-left:35px"><input name="btaccepts" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="loginc()" /></div>				
					</div>
				</div>
			</div>
			<div class="drod_1 dlin_1 dsom_3 bgcol_2" align="left" style="width:100%"> 
				<div style="margin:10px"> 
					<div align="center" style="margin-bottom:5px"><img src="images/logo-home.png" /></div>
					<div align="left" class="fgreen" style="margin-bottom:5px"><b>Versión: 2.1.172 (2024-10-29)</b></div>
					<div align="justify" class="fcont" style="margin-bottom:5px">Copyright &copy; New York Money. Reservados todos los derechos.</div>
					<div style="margin-bottom:10px; margin-top:10px" class="dlin_4"></div>
					<div>
						<table style="width:100%" cellpadding="0" cellspacing="0">
							<tr class="trtxco">
								<td style="width:140px; text-align:left" class="fgreen"><b>Clave de acceso:</b></td>
								<td align="left"><input name="tx1" id="tx1" maxlength="10" class="txbox" style="width:180px;" type="password" value="" /></td>
							</tr>						
							<tr class="trtxco">
								<td style="width:140px; text-align:left" class="fgreen"><b>Permiso de usuario:</b></td>
								<td align="left"><input name="tx2" id="tx2" maxlength="20" class="txbox" style="width:180px;" type="password" value="" onkeydown="return entsend(this, event)"/></td>
							</tr>						
							<tr class="trtxco">
								<td colspan="2" align="2">
									<div style="height:7px"></div>	
									<input name="btaccept" type="button" value="Aceptar" class="btcont" style="width:88px;" onclick="login()" />
								</td>
							</tr>					
						</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
