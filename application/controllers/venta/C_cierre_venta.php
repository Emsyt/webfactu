<?php
/* A
------------------------------------------------------------------------------------------
Creador: Brayan Janco Cahuana Fecha:17/11/2021, GAN-MS-A4-092,
Creacion del Controlador C_listado_ventas para conectar con listado_ventas y M_listado_ventas con sus respectivas funciones
------------------------------------------------------------------------------
Modificado: Melvin Salvador Cussi Callisaya Fecha:22/03/2022, Codigo:GAN-MS-M6-136
Descripcion: se agrego el pdf correspondiente para la impresion de la nota de venta
------------------------------------------------------------------------------
Modificado: Saul Imanol Quiroga Castrillo Fecha:07/08/2022, Codigo:GAN-MS-A1-340
Descripcion: agregadas la funciones respectivas para el modulo de entregas

------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 04/09/2022 GAN-MS-B9-0027
Se agre la funcion cierre_ventas_datos
------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 07/10/2022 GAN-MS-M0-0036
Se agre la funcion add_gastos_cierre para la captura de datos para n o mas gastos GAN-CV-M0-0066

------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 24/10/2022 GAN-CV-M0-0066
Se agre la funcion calcular_ingreso para el calculo de los ingresos
*/
?>
<?php
if (!defined('BASEPATH'))
    exit('Not access system ...');

class C_cierre_venta extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('venta/M_cierre_venta','cierre_venta');
        $this->load->helper('url');
        $this->load->library('Pdf');
        $this->load->library('excel');
    }

    public function index() {
        if ($this->session->userdata('login')) {
            $log['usuario'] = $this->session->userdata('usuario');
            $log['permisos'] = $this->session->userdata('permisos');
            $usr=$data['codigo_usr'] = $this->session->userdata('id_usuario');
            $data['fecha_imp'] = date('Y-m-d H:i:s');
           
            $data['lib'] = 0;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['mostrar_chat'] = $this->general->get_ajustes("mostrar_chat");
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['logo'] = $this->general->get_ajustes("logo");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'venta/cierre_venta';
            $usrid = $this->session->userdata('id_usuario');
            $data['chatUsers'] = $this->general->chat_users($usrid);
            $data['getUserDetails'] = $this->general->get_user_details($usrid);
            $this->load->view('templates/estructura',$data);
        } else {
            redirect('logout');
        }
    }

    public function lst_listado_ventas_c() {

        $array_reporte_ABMventas = array(
            'fecha_inicial' => $this->input->post('selc_frep'),
            'fecha_fin' => $this->input->post('selc_finrep'),
        );
        $json_reporte_ABMventas=json_encode($array_reporte_ABMventas);
        $lst_ventas = $this->cierre_venta->get_lst_reporte_ABMventas_c($json_reporte_ABMventas);

        $data= array('responce'=>'success','posts'=>$lst_ventas);
        echo json_encode($data);
        
    }

    public function ingresos_reporte_ventas(){

        $array_ventas = array(
            'fecha_inicial' => $this->input->post('selc_frep'),
            'fecha_fin' => $this->input->post('selc_finrep'),
        );
        $json_ventas=json_encode($array_ventas);
        $ingresos_ventas = $this->listado_ventas->get_ingresos_ventas($json_ventas);
        $data= array('responce'=>'success','posts'=>$ingresos_ventas);
        echo json_encode($ingresos_ventas);
    }
    


/* GAN-MS-M0-003 Denilson Santander Rios, 07/10/2022*/
    public function add_gastos_cierre(){
        $cont = count(json_decode($_POST['des']));
        $des = json_decode($_POST['des']);
        $mon = json_decode($_POST['mon']);
        $cant = json_decode($_POST['cant']);
        if($des !="" && $mon != "" && $cant != ""){
            $array_datos = array();
            for($i = 0; $i < $cont; $i++){
                $array_datos[] = array('detalle' => 0, 'monto_total' => 0, 'cantidad' => 0);
            }
            $array = array(
                'gastos' => $array_datos
            );
            $json = json_encode($array);
            $idlogin = $this->session->userdata('id_usuario');
            $insertar = $this->cierre_venta->gasto_cierre($idlogin,$json);
            echo json_encode($insertar);      
        }else{
            $array_datos = array();
            for($i = 0; $i < $cont; $i++){
                $array_datos[] = array('detalle' => $des[$i], 'monto_total' => $mon[$i], 'cantidad' => $cant[$i]);
            }
            $array = array(
                'gastos' => $array_datos
            );
            $json = json_encode($array);
            $idlogin = $this->session->userdata('id_usuario');
            $insertar = $this->cierre_venta->gasto_cierre($idlogin,$json);
            echo json_encode($insertar);
        }
        
    }
/* FIN GAN-MS-M0-003 Denilson Santander Rios, 07/10/2022*/

/* GAN-MS-B9-0027 Denilson Santander Rios, 04/10/2022*/
    public function cierre_ventas_datos(){   
        $idubicacion = $this->input->post('dato1');
        $f1 = $this->input->post('selc_frep');
        $f2 =  $this->input->post('selc_finrep');
        
        $ingresos_ventas = $this->cierre_venta->get_datos($idubicacion,$f1, $f2);
        $data= array('responce'=>'success','posts'=>$ingresos_ventas);
        echo json_encode($ingresos_ventas);
    }

/* FIN GAN-MS-B9-0027 Denilson Santander Rios, 04/10/2022*/
    public function cierre_ventas_datos_seleccionado(){
        
        $idubicacion = $this->input->post('dato1');
        $f1 = $this->input->post('selc_frep');
        $f2 =  $this->input->post('selc_finrep');
        $array = $this->input->post('array');     
        //$idlote = $this->input->post('dato2');      
        $datos_seleccionados = $this->cierre_venta->get_datos_seleccionado($idubicacion,$f1, $f2,$array,false);
        //$data= array('responce'=>'success','posts'=>$datos_seleccionados);
        echo json_encode($datos_seleccionados);      
    }


/*  GAN-CV-M0-0066 Denilson Santander Rios, 24/10/2022 */
    public function calcular_ingreso(){
        $var = $this->input->post('TotalVenta');
        $var1 = $this->input->post('MontoSelccionado');
        $var2 = $this->input->post('Suma');

        $ingresos = $this->cierre_venta->mostrar_ingreso($var, $var1, $var2);
        $data= array('responce'=>'success','posts'=>$ingresos);
        echo json_encode($ingresos);
    }
/*  FIN GAN-CV-M0-0066 Denilson Santander Rios, 24/10/2022 */
    public function subirImagen(){
        move_uploaded_file($_FILES["file"]["tmp_name"], "./assets/img/facturas/".$_FILES['file']['name']);
    }
    public function cerrar_caja(){
        $montoTotal = $this->input->post('montoTotal');
        $montoGasto = $this->input->post('montoGasto');
        $montoEntregar = $this->input->post('montoEntregar');
        $lotes = $this->input->post('lotes');
        $f1 = $this->input->post('selc_frep');
        $f2 = $this->input->post('selc_finrep');
        $imagen = $this->input->post('imagen');


       

        $insertarCierre = $this->cierre_venta->registro_cierre_caja($montoTotal, $montoGasto, $montoEntregar,$lotes,$f1,$f2,$imagen);
        $data= array('responce'=>'success','posts'=>$insertarCierre);
        echo json_encode($insertarCierre);
    }

    public function datos_opecierre(){
        $datoscierre = $this->cierre_venta->get_opecierre();
        $data= array('responce'=>'success','posts'=>$datoscierre);
        echo json_encode($datoscierre);
    }
}