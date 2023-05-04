<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Gabriela Mamani Choquehuanca Fecha:21/07/2022, Codigo: GAN-MS-A1-314,
Descripcion: Se añadio la funcion add_update_facturacion();

 */
defined('BASEPATH') or exit('No direct script access allowed');

class C_control extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->library('Facturacion');
        $this->load->model('facturacion/M_control', 'control');
    }

    public function index(){
        if ($this->session->userdata('login')) {
            $log['usuario'] = $this->session->userdata('usuario');
            $log['permisos'] = $this->session->userdata('permisos');
            $usr = $this->session->userdata('id_usuario');
            $data['fecha_imp'] = date('Y-m-d H:i:s');
            // $data['eventos'] = $this->control->get_eventos();
            $data['estado_emision'] = $this->control->estado_emision();
            // $data['cod_estado'] = $this->control->cod_estado();
            $data['lib'] = 0;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['logo'] = $this->general->get_ajustes("logo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'facturacion/control';

            $data['chatUsers'] = $this->general->chat_users($usr);
            $data['getUserDetails'] = $this->general->get_user_details($usr);
            $this->load->view('templates/estructura', $data);
        } else {
            redirect('logout');
        }
    }

    function C_datos_sistema(){
        $data = $this->control->M_datos_sistema();
        echo json_encode($data);
    }

    function C_gestionar_sistema($btn){
        //Asignar el valor a la variable $id_cliente utilizando la función ternaria
        $id_facturacion = ($btn == 'add') ? 0 : $this->input->post('id_facturacion');
        //Agrupar las variables relacionadas en un arreglo
        $data = array(
            'id_facturacion'    => $id_facturacion,
            'nit'               => $this->input->post('nit'),
            'cod_sistema'       => $this->input->post('codigo'),
            'cod_ambiente'      => $this->input->post('ambiente'),
            'cod_modalidad'     => $this->input->post('modalidad'),
            'cod_emision'       => $this->input->post('estado'),
            'cod_token'         => $this->input->post('token'),
            'cod_cafc'          => $this->input->post('cafc'),
        );

        $data = $this->control->M_gestionar_sistema(json_encode($data));
 
        if ($data[0]->oboolean == 't' && $btn == 'add') {
            $data = $this->C_registrar_cuis();
            if ($data[0]->oboolean == 't') {
                $data = $this->C_generar_cufd();
            }
        }
        echo json_encode($data);
    }

    function C_registrar_cuis(){

        $facturacion    = $this->control->M_informacion_facturacion();
        $id_facturacion = $facturacion[0]->id_facturacion;
        $Codigos        = new Codigos($facturacion);
        $respons        = $Codigos->solicitud_cuis(0);
        $response       = json_decode($respons); // Convierte el JSON en un objeto PHP
        $codigo         = $response->RespuestaCuis->codigo; // Accede al valor del código
        $fechaVigencia  = $response->RespuestaCuis->fechaVigencia; // Accede al valor del código

        $array = array(
            "id_punto_venta" => 0,
            "id_facturacion" => $id_facturacion,
            "codigo"         => $codigo,
            "fechaVigencia"  => $fechaVigencia,
        );
        $data = $this->control->M_registrar_cuis(json_encode($array));
        return $data;
    }

    function C_registrar_inicio_evento(){
        $codigo = $this->input->post('codigo');
        $evento = $this->input->post('evento');
        $estado = $this->control->cambio_estado_facuracion($codigo,$evento);
        echo json_encode($estado);
    }

    public function add_update_facturacion(){

        if ($this->input->post('btn') == 'add') {

            $mensaje = $this->input->post('ambiente');
            if ($mensaje == 'PRODUCCION') {
                $codigo_ambiente = 1;
            } else {
                $codigo_ambiente = 2;
            }
            $codigo_sistema     = $this->input->post('codigo');
            $nit                = $this->input->post('descripcion');
            $codigo_modalidad   = 2;
            $cafc_fcv               = $this->input->post('codigo_cafc_ventas');
            $cafc_fcvt               = $this->input->post('codigo_cafc_tasas');
            $token              = $this->input->post('area');
            $apiestado          = $this->input->post('tipo_factura');

            if ($apiestado == 'EN LINEA') {
                $codigo_emision = 1;
            } else {
                $codigo_emision = 2;
            }
            $this->control->registrar($codigo_ambiente, $codigo_sistema, $nit, $codigo_modalidad, $cafc_fcv, $cafc_fcvt, $codigo_emision, $token, $apiestado);
        } else if ($this->input->post('btn') == 'edit') {
            $codigo_sistema = $this->input->post('codigo');
            $nit = $this->input->post('descripcion');
            $token = $this->input->post('area');
            $apiestado = $this->input->post('tipo_factura');
            print_r($apiestado);
            if ($apiestado == 'EN LINEA') {
                $codigo_emision = 1;
                print_r($codigo_emision);
            } else {
                $codigo_emision = 2;
                print_r($codigo_emision);
            }
            $cafc_fcv               = $this->input->post('codigo_cafc_ventas');
            $cafc_fcvt               = $this->input->post('codigo_cafc_tasas');
            $mensaje = $this->input->post('ambiente');
            if ($mensaje == 'PRODUCCION') {
                $codigo_ambiente = 1;
            } else {
                $codigo_ambiente = 2;
            }
            // date_default_timezone_set("America/La_Paz");
            // $año            = date("Y");
            // $mes            = date("m");
            // $dia            = date("d");
            // $hora           = date("H");
            // $minuto         = date("i");
            // $segundo        = date("s");
            // $milisegundo    = date("ms");
            // $milisegundo    = substr($milisegundo, 0, -1);
            // $fecha     = $año . "-" . $mes . "-" . $dia . "T" . $hora . ":" . $minuto . ":" . $segundo . "." . $milisegundo;
            //$this->control->estado_facuracion($apiestado, $fecha);
            $this->control->modificar($codigo_sistema, $nit, $apiestado, $cafc_fcv, $cafc_fcvt, $codigo_ambiente, $codigo_emision, $token);
        }
        redirect('control');
    }


    function cambio_estado(){
        $descripcion = $this->input->post('descripcion');
        if ($descripcion == 'EN LINEA') {
            $codigo_emision = 1;
        } else {
            $codigo_emision = 2;
        }
        $data = $this->control->cambio_estado_facuracion($codigo_emision, $descripcion);
        echo json_encode($data);
    }
    function C_registrar_evento_fin(){

        $codigoMotivoEvento = $this->input->post('codigo');

        $descripcion = $this->input->post('evento');
        $descripcion = trim($descripcion);

        $fechaHoraInicioEvento = $this->control->fechaEvento('PRE-ANULADO');
        $fechaHoraInicioEvento = $fechaHoraInicioEvento[0]->feccre;
        $fechaHoraInicioEvento = str_replace(' ', 'T', $fechaHoraInicioEvento);

        $fechaHoraFinEvento = $this->control->fechaEvento('ELABORADO');
        $fechaHoraFinEvento = $fechaHoraFinEvento[0]->feccre;
        $fechaHoraFinEvento = str_replace(' ', 'T', $fechaHoraFinEvento);

        $facturacion = $this->control->datos_facturacion();

        $this->C_generar_cufd();

        $cufdEvento = $this->control->get_cufd($facturacion[0]->cod_punto_venta, 'PRE-ACTIVO');
        $cufdActivo = $this->control->get_cufd($facturacion[0]->cod_punto_venta, 'ACTIVO');

        $arrayEvento = array(
            'descripcion'           => trim($descripcion),
            'cuis'                  => $facturacion[0]->cod_cuis,
            'codigoPuntoVenta'      => $facturacion[0]->cod_punto_venta,
            'cufdEvento'            => $cufdEvento[0]->cod_cufd,
            'codigoMotivoEvento'    => $codigoMotivoEvento,
            'cufd'                  => $cufdActivo[0]->cod_cufd,
            'fechaHoraInicioEvento' => $fechaHoraInicioEvento,
            'fechaHoraFinEvento'    => $fechaHoraFinEvento,
        );

        $informacion_facturacion    = $this->control->M_informacion_facturacion();
        $Operaciones                = new Operaciones($informacion_facturacion);
        $respons                    = $Operaciones->registroEventoSignificativo($arrayEvento);
        
        $codigoRecepcion            = $respons->RespuestaListaEventos->codigoRecepcionEventoSignificativo;
        //$codigoRecepcion            = '2607962';

        if ($codigoRecepcion) {
            $idCufdEvento = $cufdEvento->id_cufd;
            $array = array(
                "id_tipo_evento" => $codigoMotivoEvento,
                "codigo" => $codigoRecepcion,
                "fecini" => $fechaHoraInicioEvento,
                "fecfin" => $fechaHoraFinEvento,
                "tipo_contigencia" => 1,
                "id_cufd" => $idCufdEvento,
            );
            $json = json_encode($array);
            //json fin
            $idlogin = $this->session->userdata('id_usuario');
            $this->control->fn_registrar_evento($idlogin, $json);
            //$data = $this->emitirpaq($idCufdEvento, $codigoMotivoEvento, $descripcion, $fechaHoraInicioEvento,$fechaHoraFinEvento);
        }
        
        echo json_encode($respons);

        //echo json_encode(array($data,$fechaHoraInicioEvento,$fechaHoraFinEvento));
        //echo json_encode($SolicitudEvento);
    }

    function emitirpaq($idCufdEvento, $codigoMotivoEvento, $descripcion, $fechaHoraInicio, $fechaHoraFin){

        $facturacion = $this->control->datos_facturacion();

        $id_evento = $this->control->get_id_evento($idCufdEvento);
        $id_evento = $id_evento[0]->id_evento;

        $tipoFacturaDocumento = '1';
        $codigoDocumentoSector = '1';

        $codigoAmbiente         = $facturacion[0]->cod_ambiente;
        $codigoSistema          = $facturacion[0]->cod_sistema;
        $codigoSucursal         = $facturacion[0]->cod_sucursal;
        $codigoModalidad        = $facturacion[0]->cod_modalidad;
        $cuis                   = $facturacion[0]->cod_cuis;
        $codigoPuntoVenta       = $facturacion[0]->cod_punto_venta;
        $nit                    = $facturacion[0]->nit;
        $cufd                   = $facturacion[0]->cod_cufd;

        $evento = $descripcion;
        $codigo = $codigoMotivoEvento;
        

        $tabla = $this->control->get_reporte_fac($fechaHoraInicio,$fechaHoraFin,$tipoFacturaDocumento,$codigoDocumentoSector);

        $tabla = json_decode($tabla);
        $nro=0;
        $carpeta = 'facturasfirmadasxml';
        if ($codigoModalidad == '2') {
            $carpeta = 'facturasxml';
        }
        $files = glob(FCPATH.'assets/'.$carpeta.'/tar/*.xml');
        $ruta = FCPATH.'assets/'.$carpeta.'/tar/*.xml';
        
        // vaciamos la carpeta tar
        $files = glob(FCPATH.'assets/'.$carpeta.'/tar/*.xml'); //obtenemos todos los nombres de los ficheros
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //elimino el fichero
        }
        // vaciamos la carpeta tar
        $files = glob(FCPATH.'assets/'.$carpeta.'/targz/*.tar'); //obtenemos todos los nombres de los ficheros
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //elimino el fichero
        }
        // guardamos nuevos valores a la carpeta tar
        $array_facturas=array();
        foreach($tabla as $value){
            $nro++;
            $archivo = $value->archivo;
            $array_facturas[] = $value->id_factura;
            //$this->enviar_correo($value->id_factura);
            $from = FCPATH.'assets/'.$carpeta.'/'.$archivo;
            $to = FCPATH.'assets/'.$carpeta.'/tar/'.$archivo;
            copy($from, $to);

        }
        $tarfile = "assets/'.$carpeta.'/targz/comprimido.tar";
        $pd = new \PharData($tarfile);
        
        $pd->buildFromDirectory( FCPATH.'assets/'.$carpeta.'/tar');
        //$pd->compress(\Phar::GZ);
        $path= FCPATH.'assets/'.$carpeta.'/targz/comprimido.tar';
        
        $data = file_get_contents($path); 
        
        $gz= gzencode($data,9); 
        $hash = hash('sha256', $gz);

        date_default_timezone_set('America/La_Paz');
        $feccre= date('Y-m-d H:i:s.v');
        $feccre = str_replace(" ", "T", $feccre);
        
        
        $this->control->fn_facturas_empaquetadas($id_evento,json_encode($array_facturas));

    
        $SolicitudServicio = array(
            'SolicitudServicioRecepcionPaquete' => array (
                'descripcion'           => $evento,
                'codigoAmbiente'        => $codigoAmbiente,
                'codigoEmision'         => 2,
                'codigoSistema'         => $codigoSistema,
                'codigoRecepcionEvento' => $codigo,
                'hashArchivo'           => $hash,
                'archivo'               => $gz,
                'codigoSucursal'        => $codigoSucursal,
                'cantidadFacturas'      => $nro,
                'codigoModalidad'       => $codigoModalidad,
                'cuis'                  => $cuis,
                'codigoPuntoVenta'      => $codigoPuntoVenta,
                'fechaEnvio'            => $feccre,
                'tipoFacturaDocumento'  => $tipoFacturaDocumento,
                'nit'                   => $nit,
                'codigoEvento'          => $codigo,
                'codigoDocumentoSector' => $codigoDocumentoSector,
                'cufd'                  => $cufd,
                                
            ),
        );
    
        return $SolicitudServicio;
        exit();
        $respons= $this->control->emision_paquetes($SolicitudServicio,$facturacion[0]->cod_token);


        // $codigoRecepcion = $this->input->post('codigoRecepcion');

        // $SolicitudValidacionServicio = array(
        // 'SolicitudServicioValidacionRecepcionPaquete' => array (
        //     'codigoAmbiente'        => $codigoAmbiente,
        //     'codigoEmision'         => 2,
        //     'codigoSistema'         => $codigoSistema,
        //     'codigoSucursal'        => $codigoSucursal,
        //     'codigoModalidad'       => $codigoModalidad,
        //     'codigoRecepcion'       => $codigoRecepcion,
        //     'cuis'                  => $cuis,
        //     'codigoPuntoVenta'      => $codigoPuntoVenta,
        //     'tipoFacturaDocumento'  => $tipoFacturaDocumento,
        //     'nit'                   => $nit,
        //     'codigoDocumentoSector' => $codigoDocumentoSector,
        //     'cufd'                  => $cufd,

        // ),
        // );
        // $respons_validacion= $this->control->validacion_paquetes($SolicitudValidacionServicio,$facturacion[0]->cod_token);


        return $respons;
        // echo json_encode($array_facturas);

    }

    function C_generar_cufd(){
        // Valores
        date_default_timezone_set('America/La_Paz');
        $feccre         = date('Y-m-d H:i:s.v');
        $facturacion    = $this->control->M_informacion_facturacion();
        $get_cuis       = $this->control->M_get_cuis();        
        $Codigos        = new Codigos($facturacion);
        $respons        = $Codigos->solicitud_cufd($get_cuis[0]->cod_cuis,$get_cuis[0]->cod_punto_venta);
        $respons = json_decode(json_encode($respons));
        $array_cufd = array(
            'codpuntoventa' => $get_cuis[0]->cod_punto_venta,
            'cufd'          => $respons->codigo,
            'idfacturacion' => $facturacion[0]->id_facturacion,
            'codcontrol'    => $respons->codigoControl,
            'direccion'     => $respons->direccion,
            'feccre'        => $feccre,
            'fecven'        => $respons->fechaVigencia,
        );
        $json_cufd = json_encode($array_cufd);

        $data = $this->control->fn_registrar_cufd($json_cufd);

        return $data;
    }


    function registro_evento(){
        $facturacion = $this->control->datos_facturacion();
        $cufdEvento = $this->control->get_cufd($facturacion[0]->cod_punto_venta, 'PRE-ACTIVO');
        $cufdEvento = $cufdEvento->id_cufd;
        $array = array(
            "id_tipo_evento" => $this->input->post('codigo'),
            "codigo" => $this->input->post('codg'),
            "fecini" => $this->input->post('fecha_inicial'),
            "fecfin" => $this->input->post('fecha_fin'),
            "tipo_contigencia" => 1,
            "id_cufd" => $cufdEvento,
        );
        $json = json_encode($array);
        //json fin
        $idlogin = $this->session->userdata('id_usuario');
        $resp = $this->control->fn_registrar_evento($idlogin, $json);
        //$this->control->cambio_tipo_estado(0);
        echo json_encode($resp);
    }

    function cambio_tipo_estado(){
        $tipo = $this->input->post('tipo');

        $resp = $this->control->cambio_tipo_estado($tipo);
        
        echo json_encode($resp);
    }
}
