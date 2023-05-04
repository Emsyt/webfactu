<?php
/*
  Creador: Heidy Soliz Santos Fecha:20/04/2021, Codigo:SYSGAM-001
  Metodo: index
  Descripcion:Se crea la funcion index para mostrar la vista de pedido por codigo
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:27/04/2021, Codigo:SYSGAM-003
  Descripcion:Se modifico para crear la funcion datos_producto para obtener el nombre la cantidad y el precio
    ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:30/04/2021, Codigo:SYSGAM-005
  Descripcion:Se modifico cambiar la cantidad con la funcion cantidad_producto
  -------------------------------------------------------------------------------
   Modificado: Heidy Soliz Santos Fecha:05/05/2021, Codigo:SYSGAM-007
  Descripcion:Se modifico para mostrar la tabla producto
   ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:06/05/2021, Codigo: SYSGAM-008
  Descripcion:Se modifico para implementar la funcion que calcula el cambio y para realizar la compra  tambien se modifico la funcion index para implemetar el caculo de cambio
------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:11/05/2021, Codigo: SYSGAM-009
  Descripcion: Se modifico para corregir  el error
  -----------------------------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:26/05/2021, Codigo:  GAM -011
  Descripcion: Se modifico para hacer la busqueda de nit como en el maquetado
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:8/06/2021, Codigo: GAM-027
  Descripcion: Se modifico para crear la funcion mostrar codigo
   ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:15/06/2021, Codigo: GAM-028
  Descripcion: Se modifico para completar el nombre del producto
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:07/07/2021, Codigo: GAM-032
  Descripcion: Se modifico para que el cliente no desaparesca
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:14/09/2021, GAN-MS-A4-028
  Descripcion: Se modifico para crear la funcion generar_pdf_venta_codigo
  para obtener un pdf de tcpdf
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:23/09/2021, GAN-MS-A1-033
  Descripcion: Se realizaron la modificacion de la funcion relizar_cobro para que esta devuelva un idventa necesaria para imprimir pdf, tambien se añadio la funcion cambiar precio.
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:05/11/2021, GAN-MS-A4-063
  Descripcion: Se realizaron la creacion de la funcion verifica_cantidad que permite verificar el tamaño del stock del producto.
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:12/11/2021,  GAN-MS-A1-080
  Descripcion: Se Realizo la modificacion en pedidos por codigo para que al momento de cantidad se pueda cambiar a cantidades decimales
  ------------------------------------------------------------------------------
  Modificado: Milena Rojas Fecha:29/06/2022, facturacion
  Descripcion: Se adicionó las funciones para enviar las facturas al servicio de impuestos nacionales
     ------------------------------------------------------------------------------
  Modificado: karen quispe chavez fecha 20/07/2022 Codigo :GAN-MS-A1-312
   Descripcion :se Realizo el analisis y cambio de la razon de calculo del vuelto en el caso de la venta rapida considerando descuentos

  */
defined('BASEPATH') or exit('No direct script access allowed');

class C_venta_facturada extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('venta/M_venta_facturada', 'ventaFacturada');
    $this->load->library('Pdf');
    $this->load->library('Ciqrcode');
    $this->load->library('Facturacion');
    $this->load->helper(array('email'));
    $this->load->library(array('email'));
    $this->load->helper('url');
  }

  public function index()
  {
    if ($this->session->userdata('login')) {
      $log['permisos'] = $this->session->userdata('permisos');
      $usr = $this->session->userdata('usuario');
      $data['producto'] = $this->ventaFacturada->mostrar();
      $data['contador'] = $this->ventaFacturada->contador_pedidos($usr);
      $data['metodo_pago'] = $this->ventaFacturada->listar_tipos_venta();
      $data['docs_identidad'] = $this->ventaFacturada->lts_docs_identidad();
      $data['cod_estado'] = $this->ventaFacturada->cod_estado();
      $data['lib'] = 0;
      $data['datos_menu'] = $log;
      $data['cantidadN'] = $this->general->count_notificaciones();
      $data['lst_noti'] = $this->general->lst_notificacion();
      $data['titulo'] = $this->general->get_ajustes("titulo");
      $data['thema'] = $this->general->get_ajustes("tema");
      $data['descripcion'] = $this->general->get_ajustes("descripcion");
      $data['contenido'] = 'venta/venta_facturada';
      $usrid = $this->session->userdata('id_usuario');
      $data['chatUsers'] = $this->general->chat_users($usrid);
      $data['getUserDetails'] = $this->general->get_user_details($usrid);
      $this->load->view('templates/estructura', $data);
    } else {
      redirect('logout');
    }
  }
  public function mostrar_produc()
  {
    $data = $this->ventaFacturada->mostrar();;
    echo json_encode($data);
  }
  public function lst_tipo_venta()
  {
    $data = $this->ventaFacturada->listar_tipos_venta();
    echo json_encode($data);
  }
  public function datos_producto()
  {
    $id_producto = $this->input->post('buscar');
    $data = $this->ventaFacturada->get_datos_producto($id_producto);
    echo json_encode($data);
  }
  public function cantidad_producto()
  {
    $id_venta = $this->input->post('dato1');
    $cantidad = $this->input->post('dato2');
    $data = $this->ventaFacturada->cantidad_producto($id_venta, $cantidad);
    echo json_encode($data);
  }
  public function cambiar_precio()
  {
    $id_venta = $this->input->post('dato1');
    $monto = $this->input->post('dato2');
    $data = $this->ventaFacturada->cambiar_precio($id_venta, $monto);
    echo json_encode($data);
  }
  public function verificar_cambio_precio()
  {
    $id_venta = $this->input->post('dato1');
    $monto = $this->input->post('dato2');
    $data = $this->ventaFacturada->verificar_cambio_precio($id_venta, $monto);
    echo json_encode($data);
  }
  public function verificar_cambio_precio_total()
  {
    $id_venta = $this->input->post('dato1');
    $monto = $this->input->post('dato2');
    $data = $this->ventaFacturada->verificar_cambio_precio_total($id_venta, $monto);
    echo json_encode($data);
  }
  public function verifica_cantidad()
  {
    $id_venta = $this->input->post('dato1');
    $cantidad = $this->input->post('dato2');
    $data = $this->ventaFacturada->verifica_cantidad($id_venta, $cantidad);
    echo json_encode($data);
  }
  public function cambio_precio_uni()
  {
    $id_venta = $this->input->post('dato3');
    $monto = $this->input->post('dato4');
    $data = $this->ventaFacturada->cambio_precio_uni($id_venta, $monto);
    echo json_encode($data);
  }
  public function dlt_pedido()
  {
    $id_ped = $this->input->post("buscar");
    $ped_delete = $this->ventaFacturada->delete_pedido($id_ped);
    echo json_encode($ped_delete);
  }
  public function calcular_cambio()
  {
    $descuento = $this->input->post('descuento');
    $id_tipo = $this->input->post('id_tipo');
    $pagado = $this->input->post('pagado');
    $cambio = $this->ventaFacturada->calcular_cambio($id_tipo, $pagado, $descuento);
    echo json_encode($cambio);
  }
  public function relizar_cobro()
  {
    $id_venta = $this->ventaFacturada->mostrar();
    $id_vent = $id_venta[0]->oidventa;
    $nit = $this->input->post('valor_nit') . "";
    $tipo = $this->input->post('tipo');
    $cobro = $this->ventaFacturada->realizar_cobro($tipo, $nit);
    $array_cobro = array(
      'oestado' => $cobro[0]->oestado,
      'omensaje' => $cobro[0]->omensaje,
      'idventa' => $id_vent,
    );
    echo "[" . json_encode($array_cobro) . "]";
  }
  public function mostrar_nit()
  {
    $nit = $this->ventaFacturada->mostrar_nit();
    echo json_encode($nit);
  }
  public function verifica_cliente()
  {
    $nit = $this->input->post('valor_nit') . "";
    $valor = $this->ventaFacturada->verifica_cliente($nit);
    echo json_encode($valor);
  }
  public function registrar()
  {
    $id = $this->session->userdata('id_usuario');
    $nit = $this->input->post('valor_nit') . "";
    $razonSocial = $this->input->post('razonSocial') . "";
    $razonSocial = str_replace(array('&', '<', '>', "'", '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $razonSocial);
    $complemento = $this->input->post('complemento') . "";
    $codigoExcepcion = $this->input->post('codigoExcepcion') . "";
    $docs_identidad = $this->input->post('docs_identidad') . "";
    $data = $this->ventaFacturada->registrar($id, $nit, $razonSocial, $complemento, $codigoExcepcion, $docs_identidad);
    echo json_encode($data);
  }
  public function mostrar_lts_nombre()
  {
    $nombre = $this->ventaFacturada->mostrar_lts_nombre();
    echo json_encode($nombre);
  }
  public function mostrar_nit_usuario()
  {

    $nit = $this->input->post('buscar');

    $partes = explode("-", $nit, 2);
    $partes[0] = trim($partes[0]);
    $partes[1] = trim($partes[1]);

    $data = $this->ventaFacturada->mostrar_datos_cliente($partes[1]);

    echo json_encode($data);
  }
  public function mostrar_codigo()
  {
    $codigo = $this->ventaFacturada->mostrar_codigo();
    echo json_encode($codigo);
  }
  public function mostrar_nombre()
  {
    $nit = $this->input->post('buscar');
    // $nombre = $this->ventaFacturada->mostrar_nombre($nit);
    // $complemento = $this->ventaFacturada->mostrar_complemento($nit);
    // $cod_excepcion = $this->ventaFacturada->mostrar_cod_excepcion($nit);
    // echo json_encode(array('nombre'=>$nombre,'complemento'=>$complemento,'cod_excepcion'=>$cod_excepcion));

    $data = $this->ventaFacturada->mostrar_datos_cliente($nit);
    echo json_encode($data);
  }
  public function mostrar_producto()
  {
    $nombre = $this->ventaFacturada->mostrar_producto();
    echo json_encode($nombre);
  }
  public function datos_nombre()
  {
    $nombre = $this->input->post('buscar');
    $data = $this->ventaFacturada->get_datos_nombre($nombre);
    echo json_encode($data);
  }

  function get_parametricas_cmb()
  {
    $id_venta         = $this->ventaFacturada->get_parametricas_cmb();
    print_r($id_venta);
  }


  function obtenerPorcentaje($cantidad, $total)
  {
    $porcentaje = ((float)$cantidad * 100) / $total; // Regla de tres
    $porcentaje = round($porcentaje, 0);  // Quitar los decimales
    return $porcentaje;
  }

  // function verificar_nit()
  // {

  //   $id_usuario = $this->session->userdata('id_usuario');
  //   //$datos_punto_venta=$this->ventaFacturada->get_codigos_siat($id_usuario);
  //   $nitVerificar = $this->input->post('nit');

  //   //$codigo = new Codigos($token,$codigoAmbiente,$codigoSistema,$nit,$codigoSucursal,$codigoModalidad);
  //   $facturacion    = $this->ventaFacturada->datos_facturacion();
  //   $lib_Codigo     = new Codigos($facturacion);


  //   $codigoPuntoVenta   = $facturacion[0]->cod_punto_venta;
  //   $cuis               = $facturacion[0]->cod_cuis;

  //   echo $lib_Codigo->solicitud_verificar_nit($cuis, $codigoPuntoVenta, $nitVerificar);
  // }

  function registrar_factura()
  {
    date_default_timezone_set('America/La_Paz');
    $usuario          = $this->session->userdata('usuario');
    $id_venta         = $this->ventaFacturada->id_venta($usuario);
    $id_venta         = $id_venta[0]->id_venta;
    $descuento        = $this->input->post('descuento');
    $pago_efectivo    = $this->input->post('pago_efectivo');
    $pago_tarjeta     = $this->input->post('pago_tarjeta');
    $pago_gift        = $this->input->post('pago_gift');
    $pago_otros       = $this->input->post('pago_otros');
    $montoTasa        = $this->input->post('monto_tasa');
    $numeroTarjeta    = $this->input->post('nro_tarjeta');

    $datos = array(
      'idventa'        => $id_venta,
      'efectivo'       => $pago_efectivo,
      'tarjeta'        => $pago_tarjeta,
      'gift'           => $pago_gift,
      'otros'          => $pago_otros,
      'descuento'      => $descuento
    );

    $datos_venta        = $this->ventaFacturada->datos_venta(json_encode($datos));


    $lstas_datos_venta  = json_decode($datos_venta[0]->fn_datos_venta);

    // valores que se deberian recuperar con el documento de identidad
    $nombre_rsocial   = $this->input->post('razonSocial');
    $ci_nit_completo           = $this->input->post('valor_nit');
    $complemento      = $this->input->post('complemento');
    $codigoExcepcion  = $this->input->post('codigoExcepcion');
    $docs_identidad   = $this->input->post('docs_identidad');
    $lst_pedidos      = $lstas_datos_venta->pedidos;
    $datos            = $this->ventaFacturada->datos_cliente($ci_nit_completo);
    $correo           = $datos[0]->correo;
    $ci_nit           = $datos[0]->nit_ci;
    $cod_cliente      = $datos[0]->cod_cliente;

    // no se deberia guardar nada si no hay correo;
    if (!$correo) {
      $correo = 'No se asigno un correo electronico';
    }

    $nitEmisor            = $lstas_datos_venta->nit_emisor;
    $razonSocialEmisor    = $lstas_datos_venta->rsocial_emisor;
    $municipio            = $lstas_datos_venta->municipio;
    $telefono             = $lstas_datos_venta->telefonoSucursal;
    $direccion            = $lstas_datos_venta->direccion;
    $tipofactura              = $this->input->post('tipofactura');
    $tipofacturadocumento   = '1';
    $codigodocumentosector  = '1';
    if ($tipofactura != 1) {
      $codigodocumentosector = '41';
    }

    $nfactura           = $this->ventaFacturada->nro_factura($id_venta);
    $nfactura           = $nfactura[0]->id_lote;
    $facturacion        = $this->ventaFacturada->datos_facturacion();
    $leyenda            = $this->ventaFacturada->leyenda_factura();

    $leyenda            = $leyenda[0]->odescripcion;
    $codigoPuntoVenta   = $facturacion[0]->cod_punto_venta;
    $codigo_control     = $facturacion[0]->cod_control;
    $nit                = $facturacion[0]->nit;
    $codigoSucursal     = $facturacion[0]->cod_sucursal;
    $codigoModalidad    = $facturacion[0]->cod_modalidad;

    //$codigoEmision      = ($facturacion[0]->cod_emision != 0) ? 2 : 1;

    $DatosCuf = array(
      'Nit'                 => $nit,
      'CodigoSucursal'      => $codigoSucursal,
      'CodigoModalidad'     => $codigoModalidad,
      'CodigoEmision'       => 1,
      'TipoFactura'         => $tipofacturadocumento,
      'TipoDocumentoSector' => $codigodocumentosector,
      'CodigoPuntoVenta'    => $codigoPuntoVenta,
      'CodigoControl'       => $codigo_control,
    );

    $codigoCuf = new GeneradorCuf($DatosCuf);
    $ArrayCuf = $codigoCuf->generarCuf($nfactura);
    $success = $ArrayCuf['success'];
    if ($success) {

      $cuf                = $ArrayCuf['cuf'];
      $fechaEnvio         = $ArrayCuf['fecha'];
      $cufd               = $facturacion[0]->cod_cufd;
      $cuf                = $ArrayCuf['cuf'];
      $fechaEnvio         = $ArrayCuf['fecha'];
      $fechaHora          = str_replace(' ', 'T', date('Y-m-d H:i:s.v'));
      $nombre_rsocial     = $nombre_rsocial ?? "SIN NOMBRE";
      $ci_nit             = $ci_nit ?? 777;

      $codigoMetodoPago   = $this->input->post('tipo_documento');
      $codigoMetodoPago   = $this->ventaFacturada->MetodoPago($codigoMetodoPago);

      $codigoMetodoPago   = $codigoMetodoPago[0]->codigo;
      $Cabecera_Array = array(
        'cod_cliente'         => $cod_cliente,
        'nitEmisor'           => $nitEmisor,
        'razonSocialEmisor'   => $razonSocialEmisor,
        'municipio'           => $municipio,
        'telefono'            => $telefono,
        'direccion'           => $direccion,
        'numeroFactura'       => $nfactura,
        'cuf'                 => $cuf,
        'cufd'                => $cufd,
        'cafc'                => '',
        'codigoSucursal'      => $codigoSucursal,
        'codigoPuntoVenta'    => $codigoPuntoVenta,
        'fechaEmision'        => $fechaEnvio,
        'nombreRazonSocial'   => $nombre_rsocial,
        'numeroDocumento'     => $ci_nit,
        'complemento'         => $complemento,
        'codigoExcepcion'     => $codigoExcepcion,
        'docs_identidad'      => $docs_identidad,
        'numeroTarjeta'       => $numeroTarjeta,
        'codigoMetodoPago'    => $codigoMetodoPago,
        'montoTotal'          => number_format(($lstas_datos_venta->subTotal), 2),
        'montoTotalSujetoIva' => number_format(($lstas_datos_venta->total - $montoTasa), 2),
        'montoTotalMoneda'    => number_format(($lstas_datos_venta->subTotal), 2),
        'montoGiftCard'       => number_format($lstas_datos_venta->gift_card, 2),
        'descuentoAdicional'  => number_format($lstas_datos_venta->descuento, 2),
        'usuario'             => $usuario,
        'leyenda'             => $leyenda
      );
      
      $xml = new GeneradorXml();
      if ($codigoModalidad == 2) {
        $factura_XML = $xml->CompraVentaComputarizada($Cabecera_Array, $lst_pedidos);
      } else {
        if ($codigodocumentosector == '1') {
          $factura_XML = $xml->CompraVentaElectronica($Cabecera_Array, $lst_pedidos);
        } else {
          $factura_XML = $xml->CompraVentaElectronicaTasas($Cabecera_Array, $lst_pedidos, $montoTasa);
        }
      }
      
      $namefactura = $cuf . '.xml';
      $FacturacionCompraVenta = new FacturacionCompraVenta($facturacion);
      if ($facturacion[0]->cod_emision == 1) {
        $tipo   = $this->input->post('tipo_documento');
        $ci_nit = $ci_nit . '';
        $factura_XML = '';
        if ($codigoModalidad == 2) {
          $respons      = $FacturacionCompraVenta->recepcionFacturaComputarizada($cuf, $fechaHora);
          $respons      = json_decode($respons);
        } else {
          $llaves       = $this->ventaFacturada->M_llaves();
          $privateKey   = $llaves[0]->oprivatekey;
          $publicKey    = $llaves[0]->opublickey;
          $respons      = $FacturacionCompraVenta->recepcionFacturaElectronica($cuf, $fechaHora, $privateKey, $publicKey, $codigodocumentosector);
          $respons      = json_decode($respons);
        }
        $codigoRecepcion = $respons->RespuestaServicioFacturacion->codigoRecepcion;
        if ($codigoRecepcion) {
          $val = array(
            'id_lote'           => $nfactura,
            'cuf'               => $cuf,
            'codigoRecepcion'   => $codigoRecepcion,
            'namefactura'       => $namefactura,
            'xmlfactura'        => $factura_XML,
            'nombre_rsocial'    => str_replace("'", "''", $nombre_rsocial),
            'numero_documento'  => $ci_nit_completo,
            'correo'            => $correo,
            'total'             => str_replace(',', '', number_format(($lstas_datos_venta->subTotal), 2)),
            'tipofacturadocumento' => $tipofacturadocumento,
            'codigodocumentosector' => $codigodocumentosector,
            'fechaHora'         => $fechaHora,
            'estado'            => 'ACEPTADO',
          );
          $cobro  = $this->ventaFacturada->realizar_cobro($tipo, $ci_nit_completo);
          $val = $this->ventaFacturada->M_registrar_factura(json_encode($val));
        }
        $transaccion = false;
      } else {
        $tipo    = $this->input->post('tipo_documento');
        $ci_nit  = $ci_nit . '';
        $factura_XML = '';
        $codigoRecepcion = 'SIN LINEA';

        if ($codigoModalidad != 2) {
          $llaves = $this->ventaFacturada->M_llaves();
          $privateKey = $llaves[0]->oprivatekey;
          $publicKey = $llaves[0]->opublickey;
          $FacturacionCompraVenta->firmadorFacturaElectronica($cuf, $privateKey, $publicKey);
        };
        $val = array(
          'id_lote'         => $nfactura,
          'cuf'             => $cuf,
          'codigoRecepcion' => $codigoRecepcion,
          'namefactura'     => $namefactura,
          'xmlfactura'      => $factura_XML,
          'nombre_rsocial'  => str_replace("'", "''", $nombre_rsocial),
          'numero_documento' => $ci_nit_completo,
          'correo'          => $correo,
          'total'           => str_replace(',', '', number_format(($lstas_datos_venta->subTotal), 2)),
          'tipofacturadocumento' => $tipofacturadocumento,
          'codigodocumentosector' => $codigodocumentosector,
          'fechaHora' => $fechaHora,
          'estado' => 'PENDIENTE',
        );

        $val = $this->ventaFacturada->M_registrar_factura(json_encode($val));
        $cobro   = $this->ventaFacturada->realizar_cobro($tipo, $ci_nit_completo);
        $respons = "SIN CONEXION";
        $transaccion = true;
      }
      $data = array(
        'idventa' => $id_venta,
        'transaccion' => $transaccion,
        'cobro' => json_encode($cobro),
        'val' => json_encode($val),
        'respons'   => json_encode($respons),
        'resources' => json_encode(array('fechaEnvio' => $fechaEnvio, 'cuf' => $cuf)),
      );
    } else {
      $data = array(
        (object) array(
          'oboolean' => 'f',
          'omensaje' => $ArrayCuf['error'],
        )
      );
    }
    echo json_encode($data);
  }

  function pdf_facturacion()
  {
    //===============================================================================
    //  RECUPERAMOS VALORES DE LA VISTA
    //===============================================================================
    $cuf                    = $this->input->post('cuf');
    $xml_open               = simplexml_load_file(FCPATH . 'assets/facturasxml/' . $cuf . '.xml');

    $nitEmisor              = $xml_open->cabecera->nitEmisor . '';
    $numeroFactura          = $xml_open->cabecera->numeroFactura . '';
    $municipio              = $xml_open->cabecera->municipio . '';
    $fechaEmision           = $xml_open->cabecera->fechaEmision . '';
    $nombreRazonSocial      = $xml_open->cabecera->nombreRazonSocial . '';
    $nombreRazonSocial      = $this->xmlEscape($nombreRazonSocial);
    $numeroDocumento        = $xml_open->cabecera->numeroDocumento . '';
    $complemento            = $xml_open->cabecera->complemento . '';
    if ($complemento != '') {
      $docmuentoCompleto = $numeroDocumento.'-'.$complemento;
    }else{
      $docmuentoCompleto = $numeroDocumento;
    }
    $datos_cliente          = $this->ventaFacturada->mostrar_datos_cliente($docmuentoCompleto);
    $codigoCliente          = $xml_open->cabecera->codigoCliente . '';
    $codigoDocumentoSector  = $xml_open->cabecera->codigoDocumentoSector . '';
    $montoTotal             = $xml_open->cabecera->montoTotal . '';
    $descuentoAdicional     = $xml_open->cabecera->descuentoAdicional . '';
    $subTotal               = $montoTotal + $descuentoAdicional;
    $montoGiftCard          = $xml_open->cabecera->montoGiftCard . '';
    $leyenda                = $xml_open->cabecera->leyenda . '';
    $facturacion            = $this->ventaFacturada->datos_facturacion();
    $codigoEmision          = $facturacion[0]->cod_emision;
    $montoGiftCard          = $montoGiftCard == '' ? '0.00' : $montoGiftCard;
    $montoTasa              = $xml_open->cabecera->montoTasa !== null ? $xml_open->cabecera->montoTasa . '' : '0.00';
    $repGrafica             = $codigoEmision != '1' ? 'Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido fuera de línea, verifique su envío con su proveedor o en la página web <u>www.impuestos.gob.bo</u>' : 'Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una Modalidad de Facturación en Línea';
    $id_usuario             = $this->session->userdata('id_usuario');
    $id_papel               = $this->general->get_papel_size($id_usuario);
    $codigoAmbiente         = $facturacion[0]->cod_ambiente;
    $codigoModalidad        = $facturacion[0]->cod_modalidad;

    if ($id_papel[0]->oidpapel == 1304) {
      $numeroWidth = 100;
      $cantidadWidth = 62;
      $descripcionWidth = 190;
      $precioWidth = 70;
      $descuentowidth = 72;
      $widthtext2 = 70;
      $widthtotal = 330;
      $qrxy = 100;
      $footer = true;

      $montoTotalSujetoIva  = $xml_open->cabecera->montoTotalSujetoIva . '';
      $montoPagar    = $montoTotalSujetoIva + $montoTasa;

      $txtl = '       ' . $xml_open->cabecera->razonSocialEmisor . '
                          CASA MATRIZ' . '
                      No. Punto de Venta ' . $xml_open->cabecera->codigoPuntoVenta . '
' . $xml_open->cabecera->direccion . '
                        Telefono: ' . $xml_open->cabecera->telefono . '
                        ' . $xml_open->cabecera->municipio . '';
      if ($codigoAmbiente == 1) {
        $kodenya = 'https://siat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
      } else {
        $kodenya = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=2';
      }


      $file = 'assets/img/facturas/' . $cuf . '.png';
      QRcode::png(
        $kodenya,
        $outfile = $file,
        $level = QR_ECLEVEL_H,
        $size = 6,
        $margin = 2
      );

      $pdf = new Pdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('-');

      $titulofactura          = "FACTURA";
      $facsubtitle            = "(Con Derecho A Crédito Fiscal)";

      $pdf->SetTitle("FACTURA");
      $pdf->SetSubject("FACTURA");
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
      $pdf->setFooterData(array(0, 75, 146), array(0, 75, 146));
      $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, 'B', 8));
      $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, 'B', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      $pdf->SetMargins(15, 20, 15);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(15);

      $pdf->SetAutoPageBreak($footer, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->setFontSubsetting(true);

      $pdf->setPrintHeader(true);
      $pdf->setPrintFooter(true);

      $pdf->SetFont('helvetica', '', PDF_FONT_SIZE_DATA, '', true);
      $pdf->AddPage('P', PDF_PAGE_FORMAT);

      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter($footer);


      $ajustes            = $this->general->get_ajustes("logo");
      $ajuste             = json_decode($ajustes->fn_mostrar_ajustes);
      $logo               = $ajuste->logo;


      $image_file = 'assets/img/icoLogo/' . $logo;

      $pdf->Image($image_file, 15, 10, 35, 10, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
      $left_column = '<table>
      <tr>
        <td width="300px"></td>
        <td width="100px"><b>NIT:</b></td>
        <td width="100px" align="left">' . $nitEmisor . '</td>
      </tr>
      <tr>
        <td width="300px"></td>
        <td width="100px"><b>Factura N°:</b></td>
        <td width="100px" align="left">' . $numeroFactura . '</td>
      </tr>
      <tr>
        <td width="300px"></td>
        <td width="100px"><b>Cod. Autorización:</b></td>
        <td width="100px" align="left">' . $cuf . '</td>
      </tr>
      </table>';
      $right_column = null;
      $pdf->writeHTML($left_column . $right_column, true, false, false, false, '');
      $pdf->MultiCell($widthtext2, 5, $txtl, 0, 'L', 0, 1, '', '', true);
      $pdf->SetFont('helvetica', 'N', 8);
      $pdf->MultiCell(180, 5, "FACTURA", 0, 'C', 0, 1, '', '', true);
      $pdf->MultiCell(180, 5,  $facsubtitle, 0, 'C', 0, 0, '', '', true);


      $tbl = '
      <div>
      <br><br><br>
      <table>
      <tr>
        <td width="140px"><b>FECHA:</b></td>
        <td width="300px">' . str_replace('T', ' ', substr($fechaEmision, 0, -7)) . '</td>
        <td width="82px" align="right"><b>CI/NIT/CEX:    </b></td>
        <td width="100px">' . $numeroDocumento . ' ' . $complemento . '</td>
      </tr>
      <tr>
        <td width="140px"><b>NOMBRE/RAZON SOCIAL:&nbsp;</b></td>
        <td width="300px">' . strtoupper($nombreRazonSocial) . '</td>
        <td width="82px" align="right"><b>COD. CLIENTE:    </b></td>
        <td width="100px">' . $codigoCliente . '</td>
      </tr>
      </table>
      </div>
          ';


      $tbl1 = '
        <table cellpadding="3">
          <tr align="center" style="font-weight: bold;" bgcolor="#E5E6E8">
              <th width="' . $numeroWidth . 'px" border="1"> CÓDIGO PRODUCTO / SERVICIO </th>
              <th width="' . $cantidadWidth . 'px" border="1"> CANTIDAD</th>
              <th width="' . $precioWidth . 'px" border="1"> UNIDAD DE MEDIDA </th>
              <th width="' . $descripcionWidth . 'px" border="1"> DESCRIPCIÓN </th>
              <th width="' . $precioWidth . 'px" border="1"> PRECIO UNITARIO </th>
              <th width="' . $descuentowidth . 'px" border="1"> DESCUENTO </th>
              <th width="' . $precioWidth . 'px" border="1"> SUBTOTAL </th>
          </tr> ';
      $nro = 0;
      $tbl2 = '';
      $unidades         = $this->ventaFacturada->get_parametricas_cmb();
      foreach ($xml_open->detalle as $ped) {
        $nro++;
        $out_descripcion = '';
        foreach ($unidades as $item) {
          if ($item->out_codclas == $ped->unidadMedida) {
            $out_descripcion = $item->out_descripcion;
            break;
          }
        }
        $tbl2 = $tbl2 . '
          <tr>
              <td align="center" border="1">' . $ped->codigoProducto . '</td>
              <td align="center" border="1">' . $ped->cantidad . '</td>
              <td align="center" border="1">' . $out_descripcion . '</td>
              <td border="1">' . $ped->descripcion . '</td>
              <td align="right" border="1">' . $ped->precioUnitario . '&nbsp;&nbsp;&nbsp;</td>
              <td align="right" border="1">' . $ped->montoDescuento . '&nbsp;&nbsp;&nbsp;</td>
              <td align="right" border="1">' . $ped->subTotal . '&nbsp;&nbsp;&nbsp;</td>
          </tr>';
      }
      $convertir = new ConvertidorLetras();
      $letras = $convertir->convertir(number_format(($montoPagar), 2));
      $val = $subTotal - $descuentoAdicional - $montoGiftCard - $montoTasa;
      $cero = '';
      if ($val < 1) {
        $cero = 'CERO';
      }

      $tasas = '';
      if ($codigoDocumentoSector == '41') {
        $tasas = '<tr >
          <td width="' . $widthtotal . '">
          </td>
          <td width="210" align="left"><font><b>MONTO TASA Bs.</b></font></td>
          <td width="88" align="right"><font>' . number_format(($montoTasa), 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
          </tr>';
      }

      $tbl3 = '</table><br>
          <table>
          <tr>
              <td width="' . $widthtotal . '"></td>
              <td width="210"  align="left">SUBTOTAL Bs.</td>
              <td width="88" align="right">' . number_format($subTotal, 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</td>
          </tr>
          <tr>
              <td width="' . $widthtotal . '"></td>
              <td width="210" align="left">DESCUENTO Bs.</td>
              <td width="88" align="right">' . number_format($descuentoAdicional, 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</td>
          </tr>
          <tr>
              <td width="' . $widthtotal . '" ></td>
              <td width="210" align="left">TOTAL Bs.</td>
              <td width="88" align="right">' . number_format(($montoTotal), 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</td>
          </tr>
          <tr>
              <td width="' . $widthtotal . '">
              </td>
              <td width="210" align="left">MONTO GIFT CARD Bs.</td>
              <td width="88" align="right">' . $montoGiftCard . '&nbsp;&nbsp;&nbsp;&nbsp;</td>
          </tr>
          <tr >
              <td width="' . $widthtotal . '">
              </td>
              <td width="210" align="left"><b>MONTO A PAGAR Bs.</b></td>
              <td width="88" align="right"><font>' . number_format(($montoPagar), 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
          </tr>
          ' . $tasas . '
          <tr >
              <td width="' . $widthtotal . '">
              </td>
              <td width="210" align="left"><font><b>IMPORTE BASE CRÉDITO FISCAL Bs.</b></font></td>
              <td width="88" align="right"><font>' . number_format(($montoTotalSujetoIva), 2) . '&nbsp;&nbsp;&nbsp;&nbsp;</font></td>
          </tr>
          <tr>
            <td width="500">
            Son: ' . $cero . $letras . '
            </td>
          </tr>
        </table>
        
        ';

      $pdf->writeHTML($tbl . $tbl1 . $tbl2 . $tbl3, true, false, false, false, '');

      $footerText = '<br><table width="100%">
        <tr align="center">
            <td width="80%"><br><b>ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE
            ACUERDO A LEY</b>
            </td>         
            <td rowspan="3"  width="18%" align="right">
            <img src="' . $file . '" width="' . $qrxy . '" height="' . $qrxy . '">
            </td>
        </tr>
        <tr>
            <td align="center" width="80%"><br><br>' . $leyenda . '
            </td>
        </tr>
        <tr>
            <td align="center" width="80%"><br><br>"' . $repGrafica . '"
            </td>
        </tr>
      </table>';
      $pdf->writeHTML($footerText, true, false, false, false, '');


      ob_end_clean();
      $pdf_ruta = $pdf->Output(FCPATH . './assets/facturaspdf/factura_' . $numeroFactura . '.pdf', 'FI');
    } else {


      $dim1 = 210;
      $dim = $dim1 + (count($xml_open->detalle) * 10) + 210;


      //    tamaño carta
      $numeroWidth = 100;
      $cantidadWidth = 62;
      $descripcionWidth = 190;
      $precioWidth = 70;
      $descuentowidth = 72;
      $widthtext2 = 50;
      $widthtotal = 330;
      $qrxy = 100;
      $footer = true;

      $montoTotalSujetoIva  = $xml_open->cabecera->montoTotalSujetoIva . '';
      $montoPagar    = $montoTotalSujetoIva + $montoTasa;

      if ($codigoAmbiente == 1) {
        $kodenya = 'https://siat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=1';
      } else {
        $kodenya = 'https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=' . $nitEmisor . '&cuf=' . $cuf . '&numero=' . $numeroFactura . '&t=1';
      }
      $file = 'assets/img/facturas/' . $cuf . '.png';
      QRcode::png(
        $kodenya,
        $outfile = $file,
        $level = QR_ECLEVEL_H,
        $size = 6,
        $margin = 2
      );
      $medidas = array(80, $dim);
      $pdf = new Pdf(PDF_PAGE_ORIENTATION, 'mm', $medidas, true, 'UTF-8', false);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('-');

      $pdf->SetTitle("FACTURA");
      $pdf->SetSubject("FACTURA");
      $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
      $pdf->setFooterData(array(0, 75, 146), array(0, 75, 146));
      $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, 'B', 8));
      $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, 'B', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      $pdf->SetMargins(5, 10, 5);
      $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
      $pdf->SetFooterMargin(15);

      $pdf->SetAutoPageBreak($footer, PDF_MARGIN_BOTTOM);
      $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
      $pdf->setFontSubsetting(true);

      $pdf->setPrintHeader(true);
      $pdf->setPrintFooter(true);

      $pdf->SetFont('helvetica', '', PDF_FONT_SIZE_DATA, '', true);
      $pdf->AddPage('P', $medidas);

      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter($footer);

      $txtl = '       ' . $xml_open->cabecera->razonSocialEmisor . '
      CASA MATRIZ' . '
  No. Punto de Venta ' . $xml_open->cabecera->codigoPuntoVenta . '
' . $xml_open->cabecera->direccion . '
    Telefono: ' . $xml_open->cabecera->telefono . '
     ' . $xml_open->cabecera->municipio . '';

      // $image_file = 'assets/img/icoLogo/' . $logo;

      // $pdf->Image($image_file, 15, 10, 35, 10, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
      $left_column = '
      <table>
        <tr>
            <td width="240px" align="center"><b>FACTURA</b></td>
        </tr> 
        <tr>
            <td width="240px" align="center"><b>CON DERECHO A CRÉDITO FISCAL</b><br></td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $xml_open->cabecera->razonSocialEmisor . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center">CASA MATRIZ</td>
        </tr> 
        <tr>
            <td width="240px" align="center">No. Punto de Venta ' . $xml_open->cabecera->codigoPuntoVenta . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $xml_open->cabecera->direccion . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center">Telefono: ' . $xml_open->cabecera->telefono . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $xml_open->cabecera->municipio . '<br></td>
        </tr> 
        <tr>
            <td width="240px" align="center"><b>NIT:</b></td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $nitEmisor . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center"><b>Factura N°:</b></td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $numeroFactura . '</td>
        </tr> 
        <tr>
            <td width="240px" align="center"><b>Cod. Autorización:</b></td>
        </tr> 
        <tr>
            <td width="240px" align="center">' . $cuf . '<br></td>
        </tr> 
        <tr>
            <td width="118px" align="right"><b> NOMBRE/RAZON SOCIAL:&nbsp;</b></td>
            <td width="4px" align="right"></td>
            <td width="118px" align="left">' . strtoupper($nombreRazonSocial) . '</td>
        </tr> 
        <tr>
            <td width="118px" align="right"><b> CI/NIT/CEX:&nbsp;</b></td>
            <td width="4px" align="right"></td>
            <td width="118px" align="left">' . $numeroDocumento . ' ' . $complemento . '</td>
        </tr> 
        <tr>
            <td width="118px" align="right"><b> COD. CLIENTE:&nbsp;</b></td>
            <td width="4px" align="right"></td>
            <td width="118px" align="left">' . $codigoCliente . '</td>
        </tr> 
        <tr>
            <td width="118px" align="right"><b> LUGAR Y FECHA DE EMISION:&nbsp;</b></td>
            <td width="4px" align="right"></td>
            <td width="118px" align="left">' . $municipio . ', ' . str_replace('T', ' ', substr($fechaEmision, 0, -7)) . '<br></td>
        </tr> 
      </table>';
      $right_column = null;
      $pdf->writeHTML($left_column . $right_column, true, false, false, false, '');
      $pdf->SetFont('helvetica', 'N', 8);

      $tbl = '';
      $tbl1 = '
      <table>
        <tr align="center" style="font-weight: bold;" bgcolor="#E5E6E8">
            <th width="240px"><b>DETALLE</b></th>
        </tr> ';
      $nro = 0;
      $tbl2 = '';
      $unidades         = $this->ventaFacturada->get_parametricas_cmb();
      foreach ($xml_open->detalle as $ped) :
        $nro++;
        $out_descripcion = '';
        foreach ($unidades as $item) {
          if ($item->out_codclas == $ped->unidadMedida) {
            $out_descripcion = $item->out_descripcion;
            break;
          }
        }
        $tbl2 = $tbl2 . '
        <tr>
            <td align="left" width="240px">' . $ped->codigoProducto . ' - ' . $ped->descripcion . '</td>
        </tr>
        <tr>
            <td align="left" width="240px">  Unidad de Medida: ' . $out_descripcion . '</td>
        </tr>
        <tr>
        <td align="left" width="140px">' . $ped->cantidad . 'x' . $ped->precioUnitario . '-' . $ped->montoDescuento . '</td>
        <td align="right" width="100px">' . $ped->subTotal . '</td>
        </tr>
        ';
      endforeach;
      $convertir = new ConvertidorLetras();
      $letras = $convertir->convertir(number_format(($montoPagar), 2));
      $val = $subTotal - $descuentoAdicional - $montoGiftCard - $montoTasa;
      $cero = '';
      if ($val < 1) {
        $cero = 'CERO';
      }
      $tasas = '';
      if ($codigoDocumentoSector == '41') {
        $tasas = '
          <tr >
            <td width="140px"align="right">(-) MONTO TASA Bs.</td>
            <td width="100px" align="right"><font>' . number_format(($montoTasa), 2) . '</font></td>
          </tr>';
      }

      $tbl3 = '</table><br><br>
        <table>
        <tr>
            <td width="140px" align="right">SUBTOTAL Bs.</td>
            <td width="100px" align="right">' . number_format($subTotal, 2) . '</td>
        </tr>
        <tr>
            <td width="140px" align="right">DESCUENTO Bs.</td>
            <td width="100px" align="right">' . number_format($descuentoAdicional, 2) . '</td>
        </tr>
        <tr>
            <td width="140px" align="right">TOTAL Bs.</td>
            <td width="100px" align="right">' . number_format(($montoTotal), 2) . '</td>
        </tr>
        <tr>
            <td width="140px" align="right">MONTO GIFT CARD Bs.</td>
            <td width="100px" align="right">' . $montoGiftCard . '</td>
        </tr>
        <tr >
            <td width="140px"align="right"><b>MONTO A PAGAR Bs.</b></td>
            <td width="100px" align="right"><font>' . number_format(($montoPagar), 2) . '</font></td>
        </tr>
        ' . $tasas . '
        <tr >
            <td width="140px" align="right"><font><b>IMPORTE BASE CRÉDITO FISCAL Bs.</b></font></td>
            <td width="100px" align="right"><font>' . number_format(($montoTotalSujetoIva), 2) . '</font></td>
        </tr>
        <tr>
          <td width="240px"><br>
          <br>
          Son: ' . $cero . $letras . '
          </td>
        </tr>
      </table>
      
      ';

      $pdf->writeHTML($tbl . $tbl1 . $tbl2 . $tbl3, true, false, false, false, '');

      $footerText = '<br><table>
      <tr align="center">
          <td width="240px"><br><b>ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE
          ACUERDO A LEY</b>
          </td>         
      </tr>
      <tr>
          <td align="center" width="240px"><br><br>' . $leyenda . '
          </td>
      </tr>
      <tr>
          <td align="center" width="240px"><br><br>"' . $repGrafica . '"
          </td>
      </tr>
      <tr>
        <td align="center" width="240px"><br><br><img src="' . $file . '" width="' . $qrxy . '" height="' . $qrxy . '">
        </td>
      </tr>
    </table>';
      $pdf->writeHTML($footerText, true, false, false, false, '');


      ob_end_clean();
      $pdf_ruta = $pdf->Output(FCPATH . './assets/facturaspdf/factura_' . $numeroFactura . '.pdf', 'FI');
    }
    $correo = $datos_cliente[0]->correo;
    $cod_estado = $this->ventaFacturada->cod_estado();
    //$titulo     = $this->ventaFacturada->titulo();
    $cod_estado = $cod_estado[0]->cod_estado;
    //$titulo     = $titulo[0]->otitulo;
    if ($cod_estado == 0 || $cod_estado == 2) {
      if ($correo != '') {
        $this->enviar_correo($correo, $numeroFactura, $cuf, $codigoModalidad);
      }
    }
    exit();
  }

  public function enviar_correo($correo, $numeroFactura, $cuf, $codigoModalidad)
  {
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
    if ($codigoModalidad == 1) {
      $tituloContenido = "Facturación Electrónica";
    } else {
      $tituloContenido = "Facturación Computarizada";
    }
    
    $subtituloContenido = "Estimado cliente:";
    $textoContenido = " <p>Adjunto le hacemos llegar el documento con el detalle de la transacción de compra que realizó.</p> <p>Documentos en formatos PDF y XML.</p>";
    $empresaFooter = $xml_open->cabecera->razonSocialEmisor;
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
          <p>Agradecemos su preferencia.</p>
          <p class='empresa' style='display: flex;
            justify-content: center;
            text-align: center;
            font-size:20px;
            color:" . $color . ";'>
            " . $empresaFooter . "
          </p>
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
    $this->email->attach(FCPATH . 'assets/facturaspdf/factura_' . $numeroFactura . '.pdf');
    if ($codigoModalidad == 1) {
      $this->email->attach(FCPATH . 'assets/facturasfirmadasxml/' . $cuf . '.xml');
    } else {
      $this->email->attach(FCPATH . 'assets/facturasxml/' . $cuf . '.xml');
    }
    $this->email->send();
  }



  function xmlEscape($string)
  {
    return str_replace(array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), array('&', '<', '>', '\'', '"'), $string);
  }
}
