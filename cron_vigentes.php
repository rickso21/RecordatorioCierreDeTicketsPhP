<?php
date_default_timezone_set("America/Mexico_City");
setlocale(LC_TIME, "es_MX");
include './parametros.php';
include './fun_basicas.php';

$inst_basicas = new Basicas();

$hoy = date("m-d-Y H:i:s");

$consultaObtenerDatosCierre = "SELECT (DATEDIFF( now(),fecha_cierre)) as dias, correo , id_ticket,comentarios_cierre FROM ticket WHERE id_estatus_aprobado=3";
$consulta_general = $inst_basicas->consulta_generica_all($consultaObtenerDatosCierre);
while ($reg = mysqli_fetch_assoc($consulta_general)) {
	$n_dias_pvencer = $reg['dias'];
	$correo = $reg['correo'];
	$id_ticket = $reg['id_ticket'];
	$comentario_cierre = $reg['comentarios_cierre'];
	if ($n_dias_pvencer > $dias_antes_vencer) {
		$resp_mail = $inst_basicas->enviar_recordatorio_tickets($correo, $id_ticket, $comentario_cierre);
		if ($resp_mail) {
			echo 'mail enviado <br>';
		} else {
			echo 'error al enviar:' . $res_mail . '<br>';
		}
	} else {
		echo 'no ha vencido <br>';
	}
}


return $consulta_general;

?>