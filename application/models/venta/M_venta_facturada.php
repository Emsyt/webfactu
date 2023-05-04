<?php
/*
  Creador: Heidy Soliz Santos Fecha:20/04/2021, Codigo:SYSGAM-001
  Metodo: contador pedidos 
  Descripcion:Se crea la funcion contador_pedidos para contar los pedidos realizados 
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:27/04/2021, Codigo:SYSGAM-003
  Descripcion:Se modifico para crear la funcion get_datos_producto 
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:30/04/2021, Codigo:SYSGAM-005
  Descripcion:Se modifico cambiar la cantidad con la funcion cantidad_producto  
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:5/05/2021, Codigo:SYSGAM-007
  Descripcion:Se modifico para implementar la  fn_mostrar_tabla en la funcion mostrar
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:06/05/2021, Codigo: SYSGAM-008
  Descripcion:Se modifico para implementar la funcion que calcula el cambio y para realizar la compra
  ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:8/06/2021, Codigo: GAM-027
  Descripcion: Se modifico para crear la funcion mostrar codigo que devuelve la lista de codigos
   ------------------------------------------------------------------------------
  Modificado: Heidy Soliz Santos Fecha:15/06/2021, Codigo: GAM-028
  Descripcion: Se modifico para completar el nombre del producto
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:14/09/2021, Codigo: GAN-MS-A4-028
  Descripcion: Se modifico para crear la funcion  get_lst_nota_venta que devuelve una lista en formato JSON
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:23/09/2021, GAN-MS-A1-033
  Descripcion: Se modifico para crear la funcion  cambiar_precio, tambien se modifico la funcion fn_realizar_cobro donde se modificaron los parámetros de entrada, se agregó el CI
  ------------------------------------------------------------------------------
  Modificado: Brayan Janco Cahuana Fecha:05/11/2021, GAN-MS-A4-063
  Descripcion: Se modifico para crear la funcion verifica_cantidad que permite verificar el tamaño del stock del producto.
   ------------------------------------------------------------------------------
  Modificado: Milena Roojas Fecha:30/02/2022, facturacion
  Descripcion: Se adicionó las funciones para obtener los datos para la factura.
     ------------------------------------------------------------------------------
  Modificado: karen quispe chavez fecha 20/07/2022 Codigo :GAN-MS-A1-312
   Descripcion :se Realizo el analisis y cambio de la razon de calculo del vuelto en el caso de la venta rapida considerando descuentos
  
*/
class M_venta_facturada extends CI_Model {

  public function datos_facturacion(){
    $idlogin = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_datos_facturacion($idlogin,0)");
    return $query->result();
  }

  public function M_registrar_factura($json){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_registrar_factura($id_usuario,'$json'::JSON)");
    return $query->result();
  }

  public function get_parametricas_cmb()
  {
      $query = $this->db->query("SELECT * FROM fn_get_parametricas()");
      return $query->result();
  }

  public function titulo(){
    $query = $this->db->query("SELECT cc.descripcion as otitulo from cat_catalogo cc where cc.catalogo ilike 'cat_sistema' and cc.codigo ilike 'titulo';");
    return $query->result();
  }

  public $id_facturacion = 4;

  public function contador_pedidos($usr){
    $query = $this->db->query("SELECT COUNT(id_venta) contador_pedido
      FROM mov_venta
      WHERE apiestado = 'RESERVA'
      AND usucre = '$usr' ");
    return $query->row('contador_pedido');
  }
  public function get_datos_producto($id_producto){
    $id_usuario = $this->session->userdata('id_usuario');
    //GAN-MS-M5-0034 06/10/2022 GValverde.
    $query = $this->db->query("SELECT * FROM fn_mostrar_producto_v3($id_usuario,'$id_producto')");
    //fin GAN-MS-M5-0034 06/10/2022 GValverde.
    return $query->result();
  }
  public function cantidad_producto($id_venta, $cantidad)
  {
    $id_usuario = $this->session->userdata('id_usuario');
    //GAN-MS-M5-0034 06/10/2022 GValverde.
    $query = $this->db->query("SELECT * FROM fn_cambiar_cantidad_v2($id_venta,$id_usuario,'$cantidad')");
    //fin GAN-MS-M5-0034 06/10/2022 GValverde.
    return $query->result();
  }
  public function verifica_cliente($nit){
    $query = $this->db->query("SELECT * FROM fn_verifica_cliente('$nit'); ");
    return $query->result();
  }
  public function registrar($id,$nit,$razonSocial,$complemento,$codigoExcepcion,$docs_identidad){
    $query = $this->db->query("SELECT * FROM fn_registrar_cliente_venta($id,'$razonSocial','$nit', '$complemento', $codigoExcepcion, $docs_identidad)");
    return $query->result();
  }
  public function delete_pedido($id_venta){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_eliminar_venta($id_venta,$id_usuario)");  
    return $this->db->affected_rows();
  }
  public function mostrar(){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_mostrar_tabla($id_usuario)");
    return $query->result();
  }
  public function calcular_cambio($id_tipo,$monto,$descuento){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_calcular_cambio($id_tipo,$id_usuario, $monto,$descuento)");
    return $query->result();
  }
  public function realizar_cobro($tipo,$nit){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_realizar_cobro ($tipo,$id_usuario,'$nit')");
    return $query->result();
  }
  public function mostrar_nit(){
    $query = $this->db->query("SELECT nit_ci 
                                 FROM cat_personas cp 
                                WHERE id_catalogo IN
                                      (
                                      SELECT id_catalogo FROM cat_catalogo cc WHERE catalogo = 'cat_personas' AND  codigo='CLI'
                                      )
                                  AND apiestado LIKE 'ELABORADO'
                                  ");

    return $query->result();
  }
  public function mostrar_nit_usuario($nit){
    $query = $this->db->query("SELECT * FROM fn_recuperar_cliente_ci($nit)");
    return $query->result();
  }
  public function mostrar_lts_nombre(){
    $query = $this->db->query("SELECT * FROM vw_clientes");
    return $query->result();
  }
  public function mostrar_codigo(){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT cp.codigo from cat_producto cp
    join (select distinct id_producto from mov_provision where apiestado<>'ANULADO'
    and id_ubicacion in (select id_ubicacion
                                      from cat_ubicaciones cu
                                     where id_relacion = (select case when id_relacion=0 then id_ubicacion  else id_relacion end
                                                            from cat_ubicaciones
                                                           where id_ubicacion =(select id_proyecto from seg_usuario where id_usuario=$id_usuario ))
                                      or id_ubicacion =(select case when id_relacion=0 then id_ubicacion  else id_relacion end
                                                          from cat_ubicaciones
                                                         where id_ubicacion =(select id_proyecto from seg_usuario where id_usuario=$id_usuario))
                                                       )
                           ) mp on cp.id_producto =mp.id_producto
    join mov_inventario mi on cp.id_producto = mi.id_producto
    where cp.apiestado <>'ELIMINADO'
    and mi.id_ubicacion = (SELECT id_proyecto FROM seg_usuario WHERE id_usuario = $id_usuario)
    and mi.cantidad >0;");
    return $query->result();
  }
  public function mostrar_nombre($nit){
    $query = $this->db->query("SELECT * FROM fn_recuperar_cliente('$nit');");
    return $query->result();
  }

  public function mostrar_complemento($nit){
    $query = $this->db->query("SELECT * FROM fn_recuperar_complemento('$nit');");
    return $query->result();
  }

  public function mostrar_producto(){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query(
    "SELECT cp.descripcion from cat_producto cp
    join (select distinct id_producto from mov_provision where apiestado<>'ANULADO'
    and id_ubicacion in (select id_ubicacion
                                      from cat_ubicaciones cu
                                     where id_relacion = (select case when id_relacion=0 then id_ubicacion  else id_relacion end
                                                            from cat_ubicaciones
                                                           where id_ubicacion =(select id_proyecto from seg_usuario where id_usuario=$id_usuario ))
                                      or id_ubicacion =(select case when id_relacion=0 then id_ubicacion  else id_relacion end
                                                          from cat_ubicaciones
                                                         where id_ubicacion =(select id_proyecto from seg_usuario where id_usuario=$id_usuario))
                                                       )
                           ) mp on cp.id_producto =mp.id_producto
    join mov_inventario mi on cp.id_producto = mi.id_producto
    where cp.apiestado <>'ELIMINADO'
    and mi.id_ubicacion = (SELECT id_proyecto FROM seg_usuario WHERE id_usuario = $id_usuario)
    and mi.cantidad >0;");
    return $query->result();
  }
  public function get_datos_nombre($nombre)
  {
    $id_usuario = $this->session->userdata('id_usuario');
    //GAN-MS-M5-0034 06/10/2022 GValverde.
    $query = $this->db->query("SELECT * FROM fn_mostrar_por_nombre_v3($id_usuario,'$nombre')");
    //fin GAN-MS-M5-0034 06/10/2022 GValverde.
    return $query->result();
  }
  public function get_lst_nota_venta($id_venta,$pagado) {
    $usr= $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_nota_venta($usr,$id_venta,$pagado)");
    return $query->result();
  }
  public function cambiar_precio($id_venta,$monto){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_cambiar_precio_factu($id_usuario,$id_venta,$monto)");
    return $query->result();
  }
  public function verificar_cambio_precio($id_venta,$monto){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_verificar_cambio_precio($id_venta,$id_usuario,$monto)");
    return $query->result();
  }
  public function cambio_precio_uni($id_venta,$monto){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_cambiar_precio_unitario_factu($id_venta,$id_usuario,$monto)");
    return $query->result();
  }

  public function verificar_cambio_precio_total($id_venta,$monto){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_verificar_cambio_precio_total($id_venta,$id_usuario,$monto)");
    return $query->result();
  }
  public function verifica_cantidad($id_venta,$cantidad){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_verifica_cantidad($id_venta,$id_usuario,$cantidad)");
    return $query->result();
  }

  public function datos_factura($id_facturacion){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM cat_facturacion WHERE id_facturacion = $id_facturacion");
    return $query->result();
  }

  public function save_factura($json){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_registrar_factura($id_usuario,'$json'::JSON)");
    return $query->result();
  }


  public function count_facturas(){
    $query = $this->db->query("SELECT count(*) FROM cat_factura");
    return $query->result();
  }


  public function lts_metodo_pago(){
    $query = $this->db->query("select codigo, descripcion from cat_catalogo cc where catalogo ilike 'cat_tipos_ventas' and apiestado ilike 
    'elaborado'");
    return $query->result();
  }


  
  public function id_venta($login){
    $query = $this->db->query("SELECT id_venta FROM mov_venta
    WHERE usucre ILIKE '$login'
    AND apiestado ILIKE 'PENDIENTE'
    ORDER BY feccre DESC LIMIT 1;");
    return $query->result();
  }

  public function datos_venta($json){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_datos_venta($id_usuario,'$json'::JSON)");
    return $query->result();
  }

  public function M_llaves(){
    $query = $this->db->query("SELECT (select descripcion from cat_catalogo cc where codigo ilike 'privateKey' and catalogo ilike 'cat_firmador') as oprivatekey,
		(select descripcion from cat_catalogo cc where codigo ilike 'PublicKey' and catalogo ilike 'cat_firmador') as opublickey");
    return $query->result();
  }


  public function MetodoPago($MetodoPago){
    $query = $this->db->query("SELECT codigo FROM cat_catalogo cc where cc.id_catalogo = $MetodoPago");
    return $query->result();
  }



  public function leyenda_activa(){
    $query = $this->db->query("SELECT descripcion  
    from cat_catalogo cc 
   where catalogo ilike 'cat_leyendas_factura'
     and apiestado ilike 'elaborado'");
    return $query->result();
  }
  


  public function cod_cliente($ci_nit){
    $query = $this->db->query("SELECT cod_cliente from cat_personas cp where nit_ci ilike '$ci_nit'");
    return $query->result();
  }



  public function correo($id_lote){
    $query = $this->db->query("SELECT correo from ope_factura where id_lote = $id_lote");
    return $query->result();
  }

  public function nombre_archivo_xml($id_lote){
    $query = $this->db->query("SELECT archivo,id_lote from ope_factura where id_lote = $id_lote");
    return $query->result();
  }

  public function id_lote($id_venta){
    $query = $this->db->query("SELECT id_lote from mov_venta mv where id_venta = $id_venta");
    return $query->result();
  }

  // FACTURACION

  public function listar_tipos_venta(){
    $query = $this->db->query("SELECT * FROM fn_listar_tipos_venta_factu()");
    return $query->result();
  }

  public function lts_docs_identidad(){
    $query = $this->db->query("SELECT codigo, descripcion  FROM cat_catalogo cc where catalogo ilike 'cat_docs_identidad' and apiestado ilike 
    'elaborado'");
    return $query->result();
  }


  public function mostrar_cod_excepcion($nit){
    $query = $this->db->query("SELECT * FROM fn_recuperar_codigo_excepcion('$nit');");
    return $query->result();
  }

  
  // POR CORREGIR
  
  public function cod_estado(){
    $query = $this->db->query("SELECT descripcion:: integer as cod_estado FROM cat_catalogo cc where catalogo ilike 'cat_facturacion' and codigo ilike 'cod_estado'");
    return $query->result();
  }
  
  public function send_factura($SolicitudServicio, $tipofacturadocumento,$token){
    if ($tipofacturadocumento == '3') {
      $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl";
      // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl";
    } else {
      $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
      // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
    }

    $wsdl2 ="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
    // $wsdl2 ="https://siatrest.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";

    $client = new \SoapClient($wsdl, [
      'stream_context' => stream_context_create([
      'http'=> [
      'header' => "apikey: TokenApi $token"
      ]
      ]),

      'cache_wsdl' => WSDL_CACHE_NONE,
      'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
    ]);
    if ($tipofacturadocumento == '3') {
      $respons = $client->recepcionDocumentoAjuste($SolicitudServicio);
    } else {
      $respons = $client->recepcionFactura($SolicitudServicio);
    }
      return  $respons;
  }


  public function get_codigos_siat($id_usuario){
    $query = $this->db->query("SELECT cu.codigo_punto_venta,
                                      cc.cod_cuis,  
                                      cc2.cod_cufd,
                                      cc2.cod_control,
                                      cc2.direccion
                                  FROM cat_ubicaciones cu 
                                  JOIN ope_cuis cc 
                                  ON cu.codigo_punto_venta = cc.cod_punto_venta 
                                  JOIN ope_cufd cc2
                                  ON  cc2.cod_punto_venta = cu.codigo_punto_venta
                                  WHERE cu.id_ubicacion = (
                                                        SELECT id_proyecto 
                                                          FROM seg_usuario 
                                                          WHERE id_usuario=$id_usuario
                                                        )
                                  AND cc.apiestado ILIKE  'ELABORADO'
                                  AND cc2.apiestado  ILIKE 'ACTIVO'
                                  and cc.id_facturacion = $this->id_facturacion
                                  and cc2.id_facturacion = $this->id_facturacion");
    return $query->result();
  }

  public function nro_factura($id_venta){
    $query = $this->db->query("SELECT id_lote FROM mov_venta mv WHERE id_venta = $id_venta;");
    return $query->result();
  }

  public function datos_cliente($ci_nit){
    $query = $this->db->query("SELECT (COALESCE(cp.nombre_rsocial,'')||
                                      CASE WHEN cp.apellidos_sigla IS NULL THEN '' ELSE ' '||cp.apellidos_sigla END) nombre_rsocial, 
                                      cp.correo::TEXT,
                                      split_part(nit_ci, '-', 1) as nit_ci,
                                      CASE WHEN nit_ci LIKE '%-%' 
                                           THEN split_part(nit_ci, '-', 2) 
                                           ELSE ''
                                           END as complemento,
                                           LPAD(id_personas::text, 8, '0') as cod_cliente
                                      FROM cat_personas cp where nit_ci ilike '$ci_nit';");
    return $query->result();
  }

  // FACTURACION



  public function leyenda_factura(){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_leyenda_factura($id_usuario)");
    return $query->result();
  }

  public function rsocial_emisor(){
    $query = $this->db->query("SELECT descripcion FROM cat_catalogo cc WHERE catalogo ilike 'cat_sistema' AND codigo ilike 'razon_social'");
    return $query->result();
  }

  public function mostrar_datos_cliente($nit){
    $query = $this->db->query("SELECT * FROM fn_datos_cliente('$nit');");
    return $query->result();
  }




}
?>
