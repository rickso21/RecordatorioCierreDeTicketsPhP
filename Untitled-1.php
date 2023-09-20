<?php
/*CRON PARA CAMBIAR DE ESTATUS A VENCIDAS DE TODAS LAS MARCAS QUE LA VIGENCIA SEA MAYOR AL DIA ACTUAL Y ENVIO DE ALERTAS POR EJECUTIVO*/
date_default_timezone_set ("America/Mexico_City");
setlocale(LC_TIME,"es_MX");
include '../../inc/parametros.php';
include '../../inc/fun_basicas.php';
$inst_basicas=new Basicas();
$hoy=date("m-d-Y H:i:s");
/*CONSULTAMOS MARCAR QUE TENGAN VIGENCIA*/
$qr_c_marcas=$inst_basicas->consulta_generica_all(" SELECT AP.*,M.* FROM tbl_asig_proyecto AS AP
													 INNER JOIN  tbl_marcas AS M ON M.id_marcas=AP.id_marca
													 where AP.vigencia is not null and M.activo=0");
													 
$n_dias_paravencer='';
while($reg= mysqli_fetch_assoc($qr_c_marcas)){
	$ssql="SELECT DATEDIFF((select vigencia from tbl_asig_proyecto where id_marca=".$reg['id_marca']." and id_asig_proyecto=".$reg['id_asig_proyecto']." ORDER BY vigencia DESC LIMIT 1),now() ) as dias ";
	$qr_c_vigencia=$inst_basicas->consulta_generica_all($ssql);
    $regd= mysqli_fetch_assoc($qr_c_vigencia);
	$n_dias_pvencer=$regd['dias'];
	// //echo 'marca:'.$reg['id_marca'].' * dias_pvencer:'.$n_dias_pvencer.' * estatus:'. $reg['id_cat_estatus'].'<br>'; 
    if($n_dias_pvencer>0 and $n_dias_pvencer<=$dias_antes_vencer){
		//echo '&nbsp;&nbsp;&nbsp;->marca:'.$reg['id_marca'].' d_pvencer:'.$n_dias_pvencer.'<br>'; 
          //INSERTAMOS DIAS QUE FALTAN PARA VENCER
          $qi="update tbl_marcas set dias_pvencer=".$n_dias_pvencer." where id_marcas=".$reg['id_marca'];
          $qr_i_log_alerts=$inst_basicas->consulta_generica_all($qi);
          //INSERTAMOS ALERTA EN EL EJECUTIVO
          $qi="insert into tbl_alertas(texto,color,icono,id_usuario,link)values('La marca: ". $reg['id_marca']." vence en: $n_dias_pvencer dia(s)','bg-orange','store_mall_directory','".$reg['id_usuario_marca']."','../../view/marcas/comp_reg_marca.php?m=".base64_encode($reg['id_marcas'])."')";
		 $qr_i_log_alerts=$inst_basicas->consulta_generica_all($qi);
    }
    if($n_dias_pvencer<=0 and $reg['id_cat_estatus']==4){
		if($reg['id_cat_estatus']!=10 or $reg['id_cat_estatus']!=2 or $reg['id_cat_estatus']!=9){
			// echo '&nbsp;&nbsp;&nbsp;->marca:'.$reg['id_marca'].' d_pvencer:<br>'.$n_dias_pvencer.''; 
		   	//SE AGREGA AL LOG DE LA MARCA 
			$qr_u_log_m=$inst_basicas->consulta_generica_all("insert into tbl_log_seg_marca(id_marca,id_estatus,comentarios,fecha_registro)values('".$reg['id_marca']."','10','Sistema baja la marca, de proyecto:".$reg['id_proyecto']."',now())");
		   	//SE ELIMINA ASIGNACION PROYECTO PARA QUE CUANDO MARCA VUELVA A ESTAR ACTIVA NO DETECTE PROMOS PASADAS
		   	$qr_u_del_ap=$inst_basicas->consulta_generica_all("DELETE FROM tbl_asig_proyecto WHERE id_asig_proyecto=".$reg['id_asig_proyecto']);
		   	//REALIZAMOS CONTEO DE MARCA CON PROYECTOS ASIGNADOS
			$sql_cont="SELECT count(*) as n FROM tbl_asig_proyecto AS AP INNER JOIN  tbl_marcas AS M ON M.id_marcas=AP.id_marca where AP.vigencia is not null and M.activo=0 and M.id_marcas=".$reg['id_marca'];
		   	$cont_m_ap=$inst_basicas->consulta_generica_all($sql_cont);
		   	$cont_ap = mysqli_fetch_assoc($cont_m_ap);
			if($cont_ap['n'] <= 0){
				//SE BAJA DE LA PUBLICACION
				$qr_u_status=$inst_basicas->consulta_generica_all("update tbl_marcas set id_cat_estatus=10 where id_marcas=".$reg['id_marca']);
		   	}
			//INSERTAMOS LOG DE PROCESO QUE SE BAJO
			$file = fopen("./log_cron_vigente.txt", "a");
			fwrite($file, $hoy.'|Sistema baja la marca:'.$reg['id_marca']. " de proyecto:". $reg['id_proyecto'] . PHP_EOL);
			fclose($file);
		}
      
    }
}
?>