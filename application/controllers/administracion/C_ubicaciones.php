<?php 
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Gabriela Mamani Choquehuanca Fecha:24/06/2022, Codigo: GAN-MS-A5-275,
Descripcion: Se creo el controlador del ABM llamado Ubicaciones, el cual cuenta con la funcion de add_update_ubicacion() 
para insertar datos en el formulario
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-290,
Descripcion: Se creo el controlador del listado de registros de ubicaciones  denominado lista_ubicacion1()
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-291,
Descripcion: Se creo los controladores para  eliminar y modificar los registros  en la tabla de la vista
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Jose Daniel Luna Flores     Fecha: 30/08/2022    Código: GAN-SC-M5-409
Descripción: Se modifico la funcion de add_update_ubicacion()/insert_ubicacion para que reciba un parametro de entrada 'id_relacion'
Se modifico la funcion de add_update_ubicacion()/modificar_ubicacion para que reciba un parametro de entrada 'apiestado'
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Jose Daniel Luna Flores     Fecha: 07/09/2022    Código: GAN-MS-A1-435
Descripción: Se modifico la funcion de add_update_ubicacion()/insert_ubicacion para que ya no reciba el parametro de entrada 'apiestado'
ya que por defecto se crea como 'ELABORADO', eso con el objetivo de que la funcion actualizada fn_registrar_ubicacion() funcione
correctamente
*/
?>
<?php
if (!defined('BASEPATH'))
    exit('Not access system ...');

class C_ubicaciones extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('administracion/M_ubicaciones','ubicaciones');
    }

    public function index() {
        if ($this->session->userdata('login')) {
            $log['permisos'] = $this->session->userdata('permisos');
            $data['lst_ubicacion']=$this->ubicaciones->get_lst_ubicacion();

            $data['lib'] = 0;
            $data['datos_log'] = $log;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['mostrar_chat'] = $this->general->get_ajustes("mostrar_chat");
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'administracion/ubicaciones';
            $usrid = $this->session->userdata('id_usuario');
            $data['chatUsers'] = $this->general->chat_users($usrid);
            $data['getUserDetails'] = $this->general->get_user_details($usrid);
            $this->load->view('templates/estructura',$data);
        } else {
            redirect('logout');
        }
    }

    public function add_update_ubicacion(){
        if ($this->input->post('btn') == 'add') {
        // GAN-MS-A1-435 , 07/09/2022, dev_jluna 
            $id_catalogo=$this->input->post('ubi_ini');
            $codigo =$this->input->post('codigo');
            $descripcion = $this->input->post('descripcion');
            $area = $this->input->post('area');
            $usucre =  $this->session->userdata('usuario');
            $id_departamento = 2;
            $latitud = 0;
            $longitud =0;
            $id_relacion =0;
            // GAN-SC-M5-409 , 30/08/2022, dev_jluna 
            $this->ubicaciones->insert_ubicacion($id_catalogo,$codigo ,$descripcion, $area,$usucre,$id_departamento, $latitud, $longitud, $id_relacion);
            // FIN GAN-SC-M5-409 , 30/08/2022, dev_jluna  

        // FIN GAN-MS-A1-435 , 07/09/2022, dev_jluna

        } else if ($this->input->post('btn') == 'edit') {
            $id_ubicacion=$this->input->post('id_ubicacion');
            $id_catalogo=$this->input->post('ubi_ini');
            $codigo =$this->input->post('codigo');
            $descripcion = $this->input->post('descripcion');
            $area = $this->input->post('area');
            $usucre =  $this->session->userdata('usuario');
            $id_departamento = 2;
            $latitud = 0;
            $longitud =0;
            $id_relacion =0;
            $apiestado ='ELABORADO';
            // GAN-SC-M5-409 , 30/08/2022, dev_jluna 
            $this->ubicaciones-> modificar_ubicacion($id_ubicacion,$id_catalogo,$codigo ,$descripcion, $area,$usucre,$id_departamento, $latitud, $longitud, $apiestado);
            // FIN GAN-SC-M5-409 , 30/08/2022, dev_jluna 
        }
         redirect('ubicaciones');
    }

    public function get_lst_ubicacion(){
        $data = $this->ubicaciones->get_lst_ubicacion();
        echo json_encode($data);
    }

     public function lista_ubicacion1(){
        $data = $this->ubicaciones-> get_lst_ubicacion1();
        echo json_encode($data);
     }

     public function datos_ubicacion($id_ubi){
        $data = $this->ubicaciones->get_datos_ubicacion($id_ubi);
        echo json_encode($data);
    }

    
    public function dlt_ubicaciones($id_prov){
       $data= $this->ubicaciones->delete_ubicacion($id_prov);
       echo json_encode($data); 
    }
    
}

