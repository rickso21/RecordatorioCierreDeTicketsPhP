<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';


class Basicas
{

    /*

    //FUNCION TRAE COUNT DE CONSULTA
    public function trae_n($tabla, $condicion)
    {
        $res_con = $this->conecta_bd();
        $consulta = "select count(*) as n from $tabla $condicion";
        $resultado = $res_con->query($consulta);
        $res = mysqli_fetch_assoc($resultado);
        $n = $res['n'];
        return $n;

    }
    */

    /*

    //FUNCION GENERA LISTAS DESPLEGABLES CON SELECT  
    public function drop_down_selec($tabla, $select, $condicion)
    {
        $id = explode("_", $tabla);
        $res_con = $this->conecta_bd();
        $consulta = "select * from $tabla $condicion";
        if ($resultado = $res_con->query($consulta)) {
            if (isset($id[2])) {
                while ($fila = $resultado->fetch_assoc()) { ?>
                    <option value='<?php echo $fila["id_$id[1]_$id[2]"]; ?>' <?php if ($fila["id_$id[1]_$id[2]"] == $select) {
                           echo "selected";
                       } ?>><?php echo utf8_encode($fila["rol"]); ?></option>
                <?php }
            } else {
                while ($fila = $resultado->fetch_assoc()) { ?>
                    <option value='<?php echo $fila["id_$id[1]"]; ?>' <?php if ($fila["id_$id[1]"] == $select) {
                           echo "selected";
                       } ?>><?php echo utf8_encode($fila["rol"]); ?></option>
                <?php }
                $resultado->free();
            }
            $res_con->close();
        }
    }
*/

    /*

        //FUNCION GENERA LISTAS DESPLEGABLES CON SELECT  
        public function drop_down_selec_all($consult, $select, $campo, $tabla)
        {
            $id = explode("_", $tabla);
            $res_con = $this->conecta_bd();
            $consulta = $consult;
            if ($resultado = $res_con->query($consulta)) {

                while ($fila = $resultado->fetch_assoc()) { ?>
                    <option value='<?php echo $fila["id_$id[1]"]; ?>' <?php if ($fila["id_$id[1]"] == $select) {
                           echo "selected";
                       } ?>><?php echo utf8_encode($fila[$campo]); ?></option>
    <?php }
                $resultado->free();

                $res_con->close();
            }
        }


        */

    /*
    //CONSULTA GENERICA TRAE TODOS LOS DATOS DE TABLA REQUERIDA CON CONDICION
    public function consulta_generica($tabla, $condicion)
    {
        $res_con = $this->conecta_bd();
        $consulta = "select * from $tabla $condicion";
        //echo $consulta;
        $resultado = $res_con->query($consulta);
        return $resultado;

    }

*/
    public function consulta_generica_all($cons)
    {
        $res_con = $this->conecta_bd();
        $consulta = $cons;
        //echo $consulta;
        $resultado = $res_con->query($consulta);
        return $resultado;

    }

    /*
        //UPDATE GENERICO MODIFICA REGISTRO DEPENDIENDO CONDICION
        public function update_generico($tabla, $columnas, $condicion)
        {
            $res_con = $this->conecta_bd();
            $consulta = "update $tabla set $columnas $condicion";
            $resultado = $res_con->query($consulta);
            return $resultado;

        }
    */

    /*
    
    // FUNCION ENVIA CORREO CON INFORMACIÓN DE LOS TABLEROS
    function mail_tab($id_mail_not_base, $tipo, $usua, $nombre){
        switch ($tipo) {
            case 'tablero':
                // BUSCAMOS NOMBRE DE USUARIO CREADOR
                $sql_usu=$this->consulta_generica('tbl_usuario','where idusuario='.$usua);
                $reg_usu=mysqli_fetch_assoc($sql_usu);
                $usu=utf8_encode($reg_usu['nombre'])." ".utf8_encode($reg_usu['apellidos']);
                // BUSCAMOS MAIL DE USUARIO ASIGNADO
                $sql_mail= $this->consulta_generica('tbl_usuario','where idusuario='.$id_mail_not_base);
                $reg_mail=mysqli_fetch_assoc($sql_mail);
                $mail_not_base=$reg_mail['email'];

                $se_compartio='Se compartio un tablero.';
                $mensaje='El usuario <b>'.$usu.'</b> te compartio el tablero: <b>'.$nombre.'</b>, puedes revisarlo en Tableros sección Tableros compartidos.';
                break;
            case 'proyecto':
                // BUSCAMOS NOMBRE DE USUARIO CREADOR
                $sql_usu=$this->consulta_generica('tbl_usuario','where idusuario='.$usua);
                $reg_usu=mysqli_fetch_assoc($sql_usu);
                $usu=utf8_encode($reg_usu['nombre'])." ".utf8_encode($reg_usu['apellidos']);
                // BUSCAMOS MAIL DE USUARIO ASIGNADO
                $sql_mail= $this->consulta_generica('tbl_usuario','where idusuario='.$id_mail_not_base);
                $reg_mail=mysqli_fetch_assoc($sql_mail);
                $mail_not_base=$reg_mail['email'];
                // SE CREA EL MENSAJE PARA ENVIAR
                $se_compartio='Se creo un proyecto';
                $mensaje='El usuario <b>'.$usu.'</b> creo el proyecto <b>'.$nombre.'</b> contigo, lo puedes ver en Tableros sección Mis Tableros<br>Accede a el tablero "Proyectos Compartidos" y ahi podras ver el proyecto.';
                break;
            case 'tarea':
                // BUSCAMOS NOMBRE DE USUARIO CREADOR
                $sql_usu=$this->consulta_generica('tbl_usuario','where idusuario='.$usua);
                $reg_usu=mysqli_fetch_assoc($sql_usu);
                $usu=utf8_encode($reg_usu['nombre'])." ".utf8_encode($reg_usu['apellidos']);
                // BUSCAMOS MAIL DE USUARIO ASIGNADO
                $sql_mail= $this->consulta_generica('tbl_usuario','where idusuario='.$id_mail_not_base);
                $reg_mail=mysqli_fetch_assoc($sql_mail);
                $mail_not_base=$reg_mail['email'];
                // BUSCAMOS NOMBRE DE TAREA Y DE PROYECTO
                $sql_tarea =  $this->consulta_generica('tbl_tarea AS T','INNER JOIN tbl_proyecto_plan AS PP on T.id_proyecto=PP.id_proyecto_plan WHERE id_tarea='.$nombre);
                $reg_tarea=mysqli_fetch_assoc($sql_tarea);
                $tarea = $reg_tarea['nombre_tarea'];
                $proyecto = $reg_tarea['nombre_proyecto_plan'];
                // SE CREA EL MENSAJE PARA ENVIAR
                $se_compartio='Se te asigno una tarea';
                $mensaje='El usuario <b>'.$usu.'</b> te asigno la tarea: <b>'.utf8_encode($tarea).'</b> en el proyecto: <b>'.utf8_encode($proyecto).'</b>, puedes revisarla en Tableros sección Mis Tableros.';
                break;
            default:
                $se_compartio='';
                $mensaje='';
                break;
        }
        include '../../inc/phpmailer/PHPMailer.php';
        include '../../inc/phpmailer/SMTP.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug  = 0;
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth   = true;
        //Definimos la cuenta que vamos a usar
        $mail->Username   = "viveplus2019@gmail.com";
        //Introducimos nuestra contraseña 
        $mail->Password   = "srqmbqkwsmyrgdch";
        $mail->From ='viveplus2019@gmail.com';
        $mail->FromName = 'CRM Tareas';
        $mail->AddAddress($mail_not_base, 'El Destinatario');
        $mail->Subject = 'Notificación: '.$se_compartio;
        $mail->isHTML(true);   
        $mail->MsgHTML('<h3>Notificaci&oacute;n CRM Tableros</h3><br>'.
        $mensaje.'<br>        
        <hr><h5><font color="#8c8c8c" size="1">* Este correo es generado de forma automatica por el sistema, no es necesario que lo respondas</font></h5>');
        if($mail->Send()){
                return true;
        }else{
                return $mail->ErrorInfo;
        }
    }

    */


    //FUNCION ENVIAR CORREOS A USUARIOS QUE HAN ALCANZO LOS 5 DIAS SIN CERRAR TICKET.
    function enviar_recordatorio_tickets($correo, $id_ticket, $comentario_cierre)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'email-smtp.us-east-1.amazonaws.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true;
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        //Definimos la cuenta que vamos a usar
        $mail->Username = "AKIARYFPEEA3I24XPJO2";
        //Introducimos nuestra contraseña 
        $mail->Password = "BKm+sHduZji4ujbrPq4wIl2E62qh/LDOoLLZ8If48AJ2";
        $mail->From = 'sistema@viveplus.com.mx';
        $mail->FromName = 'Recordatorio para cerrar ticket solicitado';
        $mail->AddAddress($correo, 'El Destinatario');
        $mail->Subject = 'Notificación: ';
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = 'Sistema Tickets (Recordatorio)';

        /*
                $mail->MsgHTML('Notificaci&oacute;n Sistema Tickets<br><br>Se informa que el Ticket: <b># ' . $id_ticket . '</b>  se cerro con los siguientes comentarios:<br><br>' . $comentario_cierre . '<br><br> Para cerrar correctamente el ticket solicitado, da click en el siguiente enlace: <br><br> <a href="http://localhost/crm/tickets/admin/view/pendientes/respuesta_ticket.php?id=' . $id_ticket . '&op=0">Cerrar Ticket</a> -- <a href="http://localhost/crm/tickets/admin/view/pendientes/respuesta_ticket.php?id=' . $id_ticket . '&op=1">Mantener Abierto</a>');

                    */


        $mail->MsgHTML('Notificaci&oacute;n Sistema Tickets<br><br>Se informa que el Ticket: <b># ' . $id_ticket . '</b>  se cerro con los siguientes comentarios:<br><br>' . $comentario_cierre . '<br><br> Para cerrar correctamente el ticket solicitado, da click en el siguiente enlace: <br><br> <a href="https://crm.viveplus.com.mx/tickets/admin/view/pendientes/respuesta_ticket.php?id=' . $id_ticket . '&op=0">Cerrar Ticket</a> -- <a href="https://crm.viveplus.com.mx/tickets/admin/view/pendientes/respuesta_ticket.php?id=' . $id_ticket . '&op=1">Mantener Abierto</a>');


        if ($mail->send()) {
            return true;
        } else {
            return $mail->ErrorInfo;
        }

    }


    //FUNCION CONECTA BASEDE DATOS
    public function conecta_bd()
    {
        include './parametros.php';
        $con = new mysqli($ser, $usu, $pas, $bd);
        if (!$con) {
            die("Connection failed: " . mysqli_connect_error());
        }
        //printf("Connected successfully");

        return $con;


    }

    /*
        //FUNCION FORMATEA FECHA DE CALENDATIO PARA INSERTAR A BD
        public function formatea_fecha($fecha)
        {
            $arr = explode('/', $fecha);
            $n_fecha = $arr[2] . '-' . $arr[1] . '-' . $arr[0];
            return $n_fecha;
        }
    */
    /*

    //FUNCION ENVIA CORREO AL USUARIO QUE PIDIO CAMBIAR CONTRASEÑA
    public function envia_mail($token, $correo)
    {

        $m = base64_encode($correo);
        include '../../inc/phpmailer/PHPMailer.php';
        include '../../inc/phpmailer/SMTP.php';
        include_once '../../inc/parametros.php';
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 1;
        $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'TLS';
        $mail->SMTPAuth = true;
        $mail->Username = "AKIARYFPEEA3I24XPJO2";
        $mail->Password = "BKm+sHduZji4ujbrPq4wIl2E62qh/LDOoLLZ8If48AJ2";
        $mail->From = 'sistema@viveplus.com.mx';
        $mail->FromName = 'Recuperacion contraseña';
        $mail->AddAddress($correo, 'Usuario Vive Plus');
        $mail->Subject = 'Sistema Viveplus';
        $mail->isHTML(true);
        $mail->MsgHTML('Notificaci&oacute;n Sistema Viveplus<br><br> Recuperar contrase&ntilde;a, da click en el siguiente enlace para recuperar: <br><br> <a href="https://crm.viveplus.com.mx/view/login/new_pass.php?t=' . $token . '&m=' . $m . '">Recuperar contraseña</a>');
        $mail->Send();

    }
}

*/
}