<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class C_punto_venta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Facturacion');
        $this->load->model('facturacion/M_punto_venta','punto_venta'); 
    }

    public function index() {
        if ($this->session->userdata('login')) {
            $log['usuario'] = $this->session->userdata('usuario');
            $log['permisos'] = $this->session->userdata('permisos');
            $usr = $this->session->userdata('id_usuario');
            $data['fecha_imp'] = date('Y-m-d H:i:s');
            
            $data['lst_tipo_venta'] = $this->punto_venta->listar_tipo_venta();
            $data['lst_ubicaciones'] = $this->punto_venta->listar_ubicaciones();
            $data['lst_puntos_venta'] = $this->punto_venta->listar_punto_venta();
            $data['lst_sucursales'] = $this->punto_venta->get_sucursales();

            $data['lib'] = 0;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['mostrar_chat'] = $this->general->get_ajustes("mostrar_chat");
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['logo'] = $this->general->get_ajustes("logo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'facturacion/punto_venta';
           
            $data['chatUsers'] = $this->general->chat_users($usr);
            $data['getUserDetails'] = $this->general->get_user_details($usr);
            $this->load->view('templates/estructura',$data);
        } else {
            redirect('logout');
        }
    }

    
    public function C_consultar_punto_venta(){

        $facturacion        = $this->punto_venta->M_informacion_facturacion();
        $Operaciones        = new Operaciones($facturacion);
        $get_cuis_inicial   = $this->punto_venta->M_get_cuis_inicial();
        $respons            = $Operaciones->consultaPuntoVenta($get_cuis_inicial[0]->cod_cuis);
        $success            = $respons['success'];
        if ($success) {
            $response       = json_decode($respons['response']); // Convierte el JSON en un objeto PHP
            if ($response->RespuestaConsultaPuntoVenta->transaccion) {
                $data = array(
                    (object) array(
                        'oboolean' => 't',
                        'omensaje' => $response,
                    )
                );
            }else {
                $data = array(
                    (object) array(
                        'oboolean' => 'f',
                        'omensaje' => $response->RespuestaConsultaPuntoVenta->mensajesList->descripcion,
                    )
                );
            }
        }else{
            $data = array(
                (object) array(
                    'oboolean' => 'f',
                    'omensaje' => $respons['error'],
                )
            );
        }
        echo json_encode($data);
    }

    public function C_gestionar_punto_venta_existente(){
        $codigoPuntoVenta = $this->input->post('codigoPuntoVenta');
        $array = array(
            "codigoPuntoVenta" => $this->input->post('codigoPuntoVenta'),
            "nombrePuntoVenta" => $this->input->post('nombrePuntoVenta'),
            "tipoPuntoVenta"   => $this->input->post('tipoPuntoVenta'),
        );
        $data = $this->punto_venta->M_gestionar_punto_venta_existente(json_encode($array));
        if ($data[0]->oboolean == 't') {
            $data = $this->C_registrar_cuis($codigoPuntoVenta);
            if ($data[0]->oboolean == 't') {
                $data = $this->C_generar_cufd($codigoPuntoVenta);
            }
        }
        echo json_encode($data);
    }


    function C_registrar_cuis($codigoPuntoVenta){

        $facturacion    = $this->punto_venta->M_informacion_facturacion();
        $id_facturacion = $facturacion[0]->id_facturacion;
        $Codigos        = new Codigos($facturacion);
        $respons        = $Codigos->solicitudCuis($codigoPuntoVenta);
        $success        = $respons['success'];
        if ($success) {
            $response       = json_decode($respons['response']); // Convierte el JSON en un objeto PHP
            $codigo         = $response->RespuestaCuis->codigo; // Accede al valor del código
            $fechaVigencia  = $response->RespuestaCuis->fechaVigencia; // Accede al valor del código
            $array = array(
                "id_punto_venta" => $codigoPuntoVenta,
                "id_facturacion" => $id_facturacion,
                "codigo"         => $codigo,
                "fechaVigencia"  => $fechaVigencia,
            );
            $data = $this->punto_venta->M_registrar_cuis(json_encode($array));
        }else{
            $data = array(
                (object) array(
                    'oboolean' => 'f',
                    'omensaje' => $respons['error'],
                )
            );
        }
        return $data;
    }

    function C_generar_cufd($codigoPuntoVenta){
        // Valores
        date_default_timezone_set('America/La_Paz');
        $feccre         = date('Y-m-d H:i:s.v');
        $facturacion    = $this->punto_venta->M_informacion_facturacion();
        $datos_cuis     = $this->punto_venta->M_get_cuis($codigoPuntoVenta);
        $Codigos        = new Codigos($facturacion);
        $respons        = $Codigos->solicitudCufd($datos_cuis[0]->cod_cuis,$codigoPuntoVenta);
        $success        = $respons['success'];
        if ($success) {
            //$respons      = json_decode(json_encode($respons));
            $response       = json_decode($respons['response']); // Convierte el JSON en un objeto PHP
            if ($response->RespuestaCufd->transaccion) {
                $array_cufd = array(
                    'codpuntoventa' => $codigoPuntoVenta,
                    'cufd'          => $response->RespuestaCufd->codigo,
                    'idfacturacion' => $facturacion[0]->id_facturacion,
                    'codcontrol'    => $response->RespuestaCufd->codigoControl,
                    'direccion'     => $response->RespuestaCufd->direccion,
                    'feccre'        => $feccre,
                    'fecven'        => $response->RespuestaCufd->fechaVigencia,
                );
                $json_cufd = json_encode($array_cufd);
                $data = $this->punto_venta->M_registrar_cufd($json_cufd);
            }else {
                $data = array(
                    (object) array(
                        'oboolean' => 'f',
                        'omensaje' => $response->RespuestaCufd->mensajesList->descripcion,
                    )
                );
            }
        }else{
            $data = array(
                (object) array(
                    'oboolean' => 'f',
                    'omensaje' => $respons['error'],
                )
            );
        }
        return $data;
    }


    public function lst_punto_venta(){
        $data= $this->punto_venta->listar_punto_venta();
        echo json_encode($data);
    }

     //----
    public function listar_punto_venta_ubicaciones(){
       $data= $this->punto_venta->listar_punto_venta_ubicaciones();
       echo json_encode($data);
    }    
    public function get_nom_ubicacion(){
       $id_ubicacion = $this->input->post('id_ubicacion');
       $data= $this->punto_venta->get_nom_ubicacion($id_ubicacion);
       echo json_encode($data);
    }

    public function cierrePuntoVenta(){
        $cod_punto_venta = $this->input->post('cod_punto_venta');
        // Libreria Facturacion - Codigos
        $facturacion    = $this->punto_venta->datos_generales_facturacion();
        $Operaciones    = new Operaciones($facturacion);

        $cuis = $this->punto_venta->get_cuis();
        $cuis = $cuis[0]->cuis;

        $respons  = $Operaciones->cierrePuntoVenta($cod_punto_venta,$cuis);
        echo  json_encode($respons); 
    }

    public function registrarCierrePuntoVenta(){

        $id_facturacion = $this->input->post('id_facturacion');
        $cod_punto_venta = $this->input->post('cod_punto_venta');
        $data = $this->punto_venta->eliminar_punto_venta($id_facturacion,$cod_punto_venta);
        echo json_encode($data);
    }

    public function eliminar_ubicacion_punto_venta(){
        $id_ubicacion = $this->input->post('id_ubicacion');
        $data= $this->punto_venta->eliminar_ubicacion_punto_venta($id_ubicacion);
       echo json_encode($data);
     }
     public function registrar_punto_venta(){
        $ubicacion = $this->input->post('ubicacion3');
        $codigoPuntoVenta = $this->input->post('punto_venta3');      
        if(trim($codigoPuntoVenta)==''){
            $ubicacion = $this->input->post('ubicacion');
            $codigoPuntoVenta = $this->input->post('punto_venta');  
        }
        $bool=$this->punto_venta->registrar_punto_venta_ubicacion($ubicacion,$codigoPuntoVenta);
            if($bool){
                $this->session->set_flashdata('success', 'Registro insertado exitosamente.');
            }else{
                $this->session->set_flashdata('error', 'Error al insertar Registro.');
            }
         redirect('punto_venta');
    }
    public function get_ubicacion(){
        $id_ubicacion = $this->input->post('id_ubicacion');    
        $data=$this->punto_venta->get_ubicacion($id_ubicacion);
        echo json_encode($data);
    }





    // public function add_update_punto_venta(){

    //     // Datos Obtenidos.
    //     $descripcion            = $this->input->post('descripcion');
    //     $nombre                 = $this->input->post('nombre');
    //     $codigoTipoPuntoVenta   = $this->input->post('tipo_venta');

    //     // Libreria Facturacion - Codigos
    //     $facturacion            = $this->punto_venta->M_informacion_facturacion();
    //     $Operaciones            = new Operaciones($facturacion);

    //     $cuis                   = $this->punto_venta->get_cuis();
    //     $cuis                   = $cuis[0]->cuis;

    //     try {
    //         $respons        = $Operaciones->registroPuntoVenta($cuis,$codigoTipoPuntoVenta,$descripcion,$nombre);
    //         $data    = $this->punto_venta->registrar_punto_venta($respons->RespuestaRegistroPuntoVenta->codigoPuntoVenta,$codigoTipoPuntoVenta,$facturacion[0]->id_facturacion,$descripcion, $nombre);
    //         if ($data[0]->oboolean == 't') {
    //             $data = $this->C_registrar_cuis($respons->RespuestaRegistroPuntoVenta->codigoPuntoVenta);
    //             if ($data[0]->oboolean == 't') {
    //                 $data = $this->C_generar_cufd($respons->RespuestaRegistroPuntoVenta->codigoPuntoVenta);
    //             }
    //         }
    //          if ($data) {
    //              $this->session->set_flashdata('success', 'Registro insertado exitosamente.');
    //          } else {
    //              $this->session->set_flashdata('error', 'Error al insertar Registro.');
    //          }
    //          redirect('punto_venta');
    //      } catch (ArithmeticError | Exception $e) {
    //         $this->session->set_flashdata('error', 'En este momento no se tiene conexión con Impuestos Nacionales, comuniquese con el administrador de sistema o intente mas tarde');
    //         redirect('punto_venta');
    //     }

    // }

    // function generar_cuis(){
    //     $id_punto_venta = $this->input->post('id_punto_venta');
    //     $facturacion    = $this->punto_venta->datos_generales_facturacion();
    //     $lib_Codigo     = new Codigos($facturacion);
    //     $respons        = $lib_Codigo->solicitud_cuis($id_punto_venta);
    //     echo $respons;
    // }

    function registrar_cuis(){
        $array = array(
            "id_punto_venta" => $this->input->post('id_punto_venta'),
            "id_facturacion" => $this->input->post('id_facturacion'),
            "codigo"         => $this->input->post('codigo'),
            "fechaVigencia"  => $this->input->post('fechaVigencia')
        );
        $data = $this->punto_venta->registrar_cuis(json_encode($array));
        echo json_encode($data);
    }
}

