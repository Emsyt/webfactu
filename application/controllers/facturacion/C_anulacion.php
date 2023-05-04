<?php

defined('BASEPATH') or exit('No direct script access allowed');

class C_anulacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('facturacion/M_anulacion', 'anulacion');
        $this->load->helper(array('email'));
        $this->load->library(array('email'));
    }

    public function index() {
        if ($this->session->userdata('login')) {
            $log['usuario'] = $this->session->userdata('usuario');
            $log['permisos'] = $this->session->userdata('permisos');
            $usr = $this->session->userdata('id_usuario');
            $data['fecha_imp'] = date('Y-m-d H:i:s');

            $data['lib'] = 0;
            $data['datos_menu'] = $log;
            $data['cantidadN'] = $this->general->count_notificaciones();
            $data['lst_noti'] = $this->general->lst_notificacion();
            $data['mostrar_chat'] = $this->general->get_ajustes("mostrar_chat");
            $data['titulo'] = $this->general->get_ajustes("titulo");
            $data['logo'] = $this->general->get_ajustes("logo");
            $data['thema'] = $this->general->get_ajustes("tema");
            $data['descripcion'] = $this->general->get_ajustes("descripcion");
            $data['contenido'] = 'facturacion/anulacion';

            $data['chatUsers'] = $this->general->chat_users($usr);
            $data['getUserDetails'] = $this->general->get_user_details($usr);
            $this->load->view('templates/estructura', $data);
        } else {
            redirect('logout');
        }
    }
    public function lst_listado_facturas() {


        $fecha_inicial = $this->input->post('fecha_inicial');
        $fecha_fin = $this->input->post('fecha_fin');
        $tipofactura = $this->input->post('tipofactura');

        $lst_ventas = $this->anulacion->get_facturas_recepcionadas($fecha_inicial, $fecha_fin, $tipofactura);

        echo json_encode($lst_ventas);
    }

    public function eliminar_factura(){
        $id_usuario         = $this->session->userdata('id_usuario');

        $facturacion = $this->anulacion->datos_facturacion();


        // $datos_punto_venta  = $this->anulacion->get_codigos_siat($id_usuario);
        // $datos_faturacion   = $this->anulacion->fn_datos_facturacion();


        $cuis               = $facturacion[0]->cod_cuis;
        // $datosFactura       = $this->anulacion->datos_factura(2);
        $cuf                = $this->input->post('dato1');
        $tipofactura        = $this->input->post('dato2');
        $tipoanulacion      = $this->input->post('tipoanulacion');

        $codigoAmbiente     = $facturacion[0]->cod_ambiente;
        $codigoPuntoVenta   = $facturacion[0]->cod_punto_venta;
        $codigoSistema      = $facturacion[0]->cod_sistema;
        $codigoSucursal     = $facturacion[0]->cod_sucursal;
        $codigoModalidad    = $facturacion[0]->cod_modalidad;
        $codigoEmision      = $facturacion[0]->cod_emision;
        $nit = $facturacion[0]->nit;
        $cufd = $facturacion[0]->cod_cufd;
        
        if ($tipofactura != 24) {


            $tipoFacturaDocumento = 1;
            if ($tipofactura == 1) {
                $codigoDocumentoSector = 1;
            } else {
                $codigoDocumentoSector = 41;
            }

            
            $SolicitudSincronizacion = array(
                'SolicitudServicioAnulacionFactura' => array(
                    'cuis'             => $cuis,
                    'codigoAmbiente'   => $codigoAmbiente,
                    'codigoEmision'    => 1,
                    'codigoPuntoVenta' => $codigoPuntoVenta,
                    'codigoSistema'    => $codigoSistema,
                    'nit'              => $nit,
                    'codigoSucursal'   => $codigoSucursal,
                    'codigoMotivo'     => $tipoanulacion,
                    'codigoModalidad'  => $codigoModalidad,
                    'tipoFacturaDocumento' => $tipoFacturaDocumento,
                    'codigoDocumentoSector' => $codigoDocumentoSector,
                    'cuf'              => $cuf,
                    'cufd'             => $cufd
                ),
            );
            // echo json_encode($SolicitudSincronizacion);
            $respons = $this->anulacion->anularFactura($SolicitudSincronizacion, $cuf, $facturacion[0]->cod_token, $tipoFacturaDocumento);
        } else {
            $tipoFacturaDocumento = 3;
            $codigoDocumentoSector = 24;
            
            $SolicitudSincronizacion = array(
                'SolicitudServicioAnulacionDocumentoAjuste' => array(
                    'cuis'             => $cuis,
                    'codigoAmbiente'   => $codigoAmbiente,
                    'codigoEmision'    => 1,
                    'codigoPuntoVenta' => $codigoPuntoVenta,
                    'codigoSistema'    => $codigoSistema,
                    'nit'              => $nit,
                    'codigoSucursal'   => $codigoSucursal,
                    'codigoMotivo'     => $tipoanulacion,
                    'codigoModalidad'  => $codigoModalidad,
                    'tipoFacturaDocumento' => $tipoFacturaDocumento,
                    'codigoDocumentoSector' => $codigoDocumentoSector,
                    'cuf'              => $cuf,
                    'cufd'             => $cufd
                ),
            );
            // echo json_encode($SolicitudSincronizacion);
            $respons = $this->anulacion->anularFactura($SolicitudSincronizacion, $cuf, $facturacion[0]->cod_token, $tipoFacturaDocumento);
        }
        //    if ($respons) {
        //     $codigoDescripcion = $respons->RespuestaServicioFacturacion->codigoDescripcion;
        //     if ($codigoDescripcion == 'ANULACION CONFIRMADA') {
        //         $this->enviar_correo($correo,$nrofactura,$nitEmisor,$cuf);
        //     }
        //    }

        //$respons = $client->anulacionFactura($SolicitudSincronizacion);
        echo $respons;
    }

    public function enviar_correo(){
        $correo             = $this->input->post('correo');
        $numeroFactura      = $this->input->post('nrofactura');
        $cuf                = $this->input->post('cuf');
        $rsocial            = $this->input->post('rsocial');
        $rsocial            = $this->anulacion->recuperar_nombre($rsocial);
        $rsocial            = $rsocial[0]->nombre_rsocial;
        // $titulo             = $this->anulacion->titulo();
        // $titulo             = $titulo[0]->otitulo;
        $nitEmisor          = $this->anulacion->nit_emisor();
        $nitEmisor          = $nitEmisor[0]->nit;

        $qr = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';

        $this->load->library('email');
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'mail.gandi.net',
            'smtp_port' => 587,
            'smtp_user' => 'factura@webfactu.com',
            'smtp_pass' => '10Co20re30oS',
            'smtp_crypto' => 'tls',
            'send_multipart' => FALSE,
            'wordwrap' => TRUE,
            'smtp_timeout' => '400',
            'validate' => true,
            // 'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $xml_open               = simplexml_load_file(FCPATH . 'assets/facturasxml/' . $cuf . '.xml');

        //Datos del contenido
        $imagenRuta = "https://images.freeimages.com/vhq/images/previews/214/generic-logo-140952.png";
        $tituloContenido = "Anulación de Factura";
        $subtituloContenido = "Estimado cliente: " . $rsocial;
        $textoContenido = " <p>La factura Nº " . $numeroFactura . " con el código de Autorización: '" . $cuf . "' fue anulada, Como medida de seguridad se le envia el enlace de la Administración Tributaria para su propia verificación:</p>";
        $tituloRuta = "CONSULTAR FACTURA";
        $sedeFooter = "CASA MATRIZ";
        $puntoVentaFooter = $xml_open->cabecera->codigoPuntoVenta;
        $ubicacionFooter = $xml_open->cabecera->direccion;
        $telefonoFooter = $xml_open->cabecera->telefono;
        $municipioFooter = $xml_open->cabecera->municipio;

        //Datos de estilo
        $color = "rgb(0,121,58)";

        // Formato HTML correo

        $body = "
    <div class='card' style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);overflow: hidden; margin-left:25px;margin-right:50px;'>
      <div class='header' style='box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);overflow: hidden;'>
          <div class='title' style=' padding:1px;
          margin-bottom:20px;
          border-bottom: 6px solid " . $color . "; padding: 1px;'>
            <h1 style='text-align: center;font-family:Verdana;'>
            " . $tituloContenido . "
            </h1>
          </div>
      </div>
      <div class='subtitle' style='padding: 10px;
          font-weight: bold;
          font-family:Calibri;
          font-size:20px;'>
          " . $subtituloContenido . "
      </div>
      <div class='content' style='padding-left: 10px;
          font-family:Calibri;
          font-size:17px;'>
          " . $textoContenido . "
      </div>
      <div class='contentcenter' style=' width: max-content;
          margin: 0 auto;
          font-family:Calibri;
          font-size:17px;'>
          <a class='empresa' style='display: flex;
            justify-content: center;
            text-align: center;
            font-size:20px;
            color:" . $color . ";'
            href='" . $qr . "'>
            " . $tituloRuta . "
          </a>
      </div>
      <div class='fotter' style='
          margin-top:50px;
          justify-content: center;
          text-align: center;
          background-color: rgb(217, 219, 218);
          padding: 10px;
          font-family:Calibri;
          font-size:12px;'>
          <p style='padding: 0;margin:1px;'>" . $sedeFooter . "</p>
          <p style='padding: 0;margin:1px;'>No. Punto de Venta: " . $puntoVentaFooter . "</p>
          <p style='padding: 0;margin:1px;'>" . $ubicacionFooter . "</p>
          <p style='padding: 0;margin:1px;'>Telefono: " . $telefonoFooter . "</p>
          <p style='padding: 0;margin:1px;'>" . $municipioFooter . "</p>
      </div>
    </div>
  ';
    ";

        $this->email->message($body);


        // Configurar los encabezados del correo electrónico
        $this->email->set_mailtype('html');
        //email content
        //$htmlContent = '<h1>Enviando correo de prueba</h1>';
        //$htmlContent .= '<p>Prueba.</p>';
        $this->email->from('factura@webfactu.com', 'WebFactu');
        // $this->email->from('work.soporte.oso@gmail.com', 'DAO SYSTEMS');
        $this->email->to($correo);
        $this->email->subject('Factura');
        if ($this->email->send()) {
            echo json_encode('enviado');
        } else {
            echo json_encode('no enviado');
            // print_r($this->email->print_debugger());
        }
    }
}
