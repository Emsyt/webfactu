<?php
class M_eventos_sin_linea extends CI_Model {
    public $id_facturacion = 4;

    public function eventos($SolicitudEvento) {


    $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI2MDEwMDMwTHBaIiwiY29kaWdvU2lzdGVtYSI6IjcyMzZBQTYzRkIwN0FENjZCM0FDQ0E3Iiwibml0IjoiSDRzSUFBQUFBQUFBQURNek1EUXdNRFl3TURRREFJdm1EbllLQUFBQSIsImlkIjoxMDgxMDM4LCJleHAiOjE2NjYzMTA0MDAsImlhdCI6MTY2MzgxMjA3MCwibml0RGVsZWdhZG8iOjYwMTAwMzAwMTYsInN1YnNpc3RlbWEiOiJTRkUifQ.GWpVfO1ZoOR34U6m4uRG_34GIskbrtEkkT6Ph7sR9lxwyssYVvvH_OlQIAWDpGjMon1w0NdRH2mSktT9gBn-Gg';
    
    $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
    // $wsdl ="https://siatrest.impuestos.gob.bo/v2/FacturacionOperaciones?wsdl";
    $client = new \SoapClient($wsdl, [
        'stream_context' => stream_context_create([
        'http'=> [
        'header' => "apikey: TokenApi $token"
        ]
        ]),
        'cache_wsdl' => WSDL_CACHE_NONE,
        'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
    ]);
 
    $respons = $client->registroEventoSignificativo($SolicitudEvento);
    if (!$respons) {
        $respons = 'Error 001: Ha ocurrido un error. Comuníquese con el administrador de ECONOTEC.';
    }
    return json_encode($respons);
    }
    public function get_eventos() {
    
        $query = $this->db->query("SELECT * FROM cat_tipo_eventos where apiestado ilike 'FUERA DE LINEA'");
        return $query->result();
      }
    public function fn_registrar_evento($idlogin,$json){
        $query = $this->db->query("SELECT * FROM fn_registrar_evento($idlogin,'$json'::JSON)");
        return $query->result();
    }
    public function get_lst_eventos() {
        $user = $this->session->userdata('usuario');
        $query = $this->db->query("SELECT ROW_NUMBER() OVER(ORDER BY oe.fecini  desc) AS nro,
		oe.id_evento,
        cte.evento,
        oe.codigo ,
        oe.fecini,
        oe.fecfin, 
        oe.apiestado,
        oe.codigo_control,
        oe.tipo_factura 
   FROM ope_eventos oe 
   join cat_tipo_eventos cte
     on cte.id_tipo_evento = oe.id_tipo_evento and oe.usucre ilike '$user' and oe.tipo_contingencia = 1;");
        return $query->result();
    }
    public function get_reporte_fac($fechaHoraInicio,$fechaHoraFin,$tipofacturadocumento,$codigodocumentosector) {
        $query = $this->db->query("SELECT * FROM fn_reporte_factura('$fechaHoraInicio','$fechaHoraFin',$tipofacturadocumento,$codigodocumentosector)");
        return $query->result();
    }
    public function fn_datos_facturacion(){
        $query = $this->db->query("select * from cat_facturacion;");
        return $query->result();
    }

    public function generar_cufd($SolicitudCufd) {
        //*******************************************************************************
        // Pruebas de Autorización de Sistemas -COMPUTARIZADA EN LÍNEA
        // El Presente documento esta enfocado a las etapas con punto de venta 0
        //*******************************************************************************

        // Token Delegado
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI2MDEwMDMwTHBaIiwiY29kaWdvU2lzdGVtYSI6IjcyMzZBQTYzRkIwN0FENjZCM0FDQ0E3Iiwibml0IjoiSDRzSUFBQUFBQUFBQURNek1EUXdNRFl3TURRREFJdm1EbllLQUFBQSIsImlkIjoxMDgxMDM4LCJleHAiOjE2NjYzMTA0MDAsImlhdCI6MTY2MzgxMjA3MCwibml0RGVsZWdhZG8iOjYwMTAwMzAwMTYsInN1YnNpc3RlbWEiOiJTRkUifQ.GWpVfO1ZoOR34U6m4uRG_34GIskbrtEkkT6Ph7sR9lxwyssYVvvH_OlQIAWDpGjMon1w0NdRH2mSktT9gBn-Gg';

        // Servicios para consumir
        $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        // $wsdl ="https://siatrest.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";


        $client = new \SoapClient($wsdl, [
            'stream_context' => stream_context_create([
            'http'=> [
            'header' => "apikey: TokenApi $token"
            ]
            ]),
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        // =========================================================
        //	Etapa I - Obtención de CUIS - Prueba 2 - wsdl 1
        // =========================================================
        
        $respons = $client->cufd($SolicitudCufd);
        $respons =json_decode(json_encode($respons));
        
        return $respons->RespuestaCufd;
    }
    public function get_codigos_siat(){
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT cu.codigo_punto_venta,
                                          cc.cuis,  
                                          cc2.cufd,
                                          cc2.codigo_control,
                                          cc2.direccion
                                      FROM cat_ubicaciones cu 
                                      JOIN cat_cuis cc 
                                      ON cu.codigo_punto_venta = cc.codigo_punto_venta 
                                      JOIN cat_cufd cc2
                                      ON  cc2.codigo_punto_venta = cu.codigo_punto_venta
                                      WHERE cu.id_ubicacion = (
                                                            SELECT id_proyecto 
                                                              FROM seg_usuario 
                                                              WHERE id_usuario=$id_usuario
                                                            )
                                      AND cc.apiestado ILIKE  'ACTIVO'
                                      AND cc2.apiestado  ILIKE 'ACTIVO'");
        return $query->result();
      }
    public function get_cuis(){
        $idlogin = $this->session->userdata('id_usuario');
        $id_facturacion = 2;
        $query = $this->db->query("select a.cod_punto_venta, 
                                          a.cod_cuis 
                                     from cat_ubicaciones cu2 
                                     join (
                                            select * 
                                              from ope_cuis oc 
                                             where oc.apiestado ilike 'ELABORADO'
                                               and oc.id_facturacion = (select cf.id_facturacion from cat_facturacion cf where apiestado ilike 'ELABORADO')
                                          ) as a 
                                       on a.cod_punto_venta =  cu2.codigo_punto_venta 
                                      and a.id_ubicacion = cu2.id_ubicacion 
                                    where cu2.id_ubicacion in (
                                                                select su2.id_proyecto 
                                                                    from seg_usuario su2 
                                                                where su2.id_usuario = $idlogin)");
        return $query->result();
    }
    public function fn_registrar_cufd($json){
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_registrar_cufd($id_usuario,'$json'::JSON)");
        return $query->result();
    }

    public function get_cufd($punto_venta,$estado){
        $query = $this->db->query("SELECT cufd,id_cufd,feccre,fecvenc from cat_cufd where codigo_punto_venta = $punto_venta and apiestado ilike '$estado'");
        return $query->result();
    }
    public function emision_paquetes($SolicitudServicio,$token) {
        //*******************************************************************************
        // Pruebas de Autorización de Sistemas -COMPUTARIZADA EN LÍNEA
        // El Presente documento esta enfocado a las etapas con punto de venta 0
        //*******************************************************************************

        // Token Delegado
        //$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI2MDEwMDMwTHBaIiwiY29kaWdvU2lzdGVtYSI6IjcyMzZBQTYzRkIwN0FENjZCM0FDQ0E3Iiwibml0IjoiSDRzSUFBQUFBQUFBQURNek1EUXdNRFl3TURRREFJdm1EbllLQUFBQSIsImlkIjoxMDgxMDM4LCJleHAiOjE2NjYzMTA0MDAsImlhdCI6MTY2MzgxMjA3MCwibml0RGVsZWdhZG8iOjYwMTAwMzAwMTYsInN1YnNpc3RlbWEiOiJTRkUifQ.GWpVfO1ZoOR34U6m4uRG_34GIskbrtEkkT6Ph7sR9lxwyssYVvvH_OlQIAWDpGjMon1w0NdRH2mSktT9gBn-Gg';

        // Servicios para consumir
        $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";

        $client = new \SoapClient($wsdl, [
            'stream_context' => stream_context_create([
            'http'=> [
            'header' => "apikey: TokenApi $token"
            ]
            ]),
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        // =========================================================
        //	Etapa I - Obtención de CUIS - Prueba 2 - wsdl 1
        // =========================================================
        
        $respons = $client->recepcionPaqueteFactura($SolicitudServicio);
        $respons =json_decode(json_encode($respons));


        return json_encode($respons);
    }
    public function validacion_paquetes($SolicitudServicio,$token) {
        //*******************************************************************************
        // Pruebas de Autorización de Sistemas -COMPUTARIZADA EN LÍNEA
        // El Presente documento esta enfocado a las etapas con punto de venta 0
        //*******************************************************************************

        // Token Delegado
        //$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiI2MDEwMDMwTHBaIiwiY29kaWdvU2lzdGVtYSI6IjcyMzZBQTYzRkIwN0FENjZCM0FDQ0E3Iiwibml0IjoiSDRzSUFBQUFBQUFBQURNek1EUXdNRFl3TURRREFJdm1EbllLQUFBQSIsImlkIjoxMDgxMDM4LCJleHAiOjE2NjYzMTA0MDAsImlhdCI6MTY2MzgxMjA3MCwibml0RGVsZWdhZG8iOjYwMTAwMzAwMTYsInN1YnNpc3RlbWEiOiJTRkUifQ.GWpVfO1ZoOR34U6m4uRG_34GIskbrtEkkT6Ph7sR9lxwyssYVvvH_OlQIAWDpGjMon1w0NdRH2mSktT9gBn-Gg';

        // Servicios para consumir
        $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";

        $client = new \SoapClient($wsdl, [
            'stream_context' => stream_context_create([
            'http'=> [
            'header' => "apikey: TokenApi $token"
            ]
            ]),
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        // =========================================================
        //	Etapa I - Obtención de CUIS - Prueba 2 - wsdl 1
        // =========================================================
        
        $respons = $client->validacionRecepcionPaqueteFactura($SolicitudServicio);
        //$respons =json_decode(json_encode($respons));


        return json_encode($respons);
    }
    function validar_factura($id_factura){
        date_default_timezone_set('America/La_Paz');
        $fecmod= date('Y-m-d H:i:s.v');
        $user = $this->session->userdata('usuario');
        $query = $this->db->query("UPDATE ope_factura SET apiestado  = 'ACEPTADO',fecmod = '$fecmod',usumod = '$user' WHERE id_factura = $id_factura;");
        return $query->result();
    }
    function registro_envio_paquete($codigoRecepcion,$codigoDescripcion,$id_evento,$tipo){
        $query = $this->db->query("UPDATE ope_eventos SET apiestado  = '$codigoDescripcion',codigo_control  = '$codigoRecepcion', tipo_factura=$tipo WHERE id_evento = $id_evento;");
        return $query->result();
    }

    function validar_paquete($codigoDescripcion,$id_evento){
        $query = $this->db->query("UPDATE ope_eventos SET apiestado  = '$codigoDescripcion' WHERE id_evento = $id_evento;");
        return $this->db->affected_rows();
    }

    public function fn_facturas_empaquetadas($id_evento,$ids){
        $id_usuario = $this->session->userdata('id_usuario');
        $ids=str_replace('"', '', $ids);
        $query = $this->db->query("SELECT * FROM fn_facturas_empaquetadas($id_usuario,$id_evento,ARRAY$ids);");
        return $query->result();
    }

    function fn_validar_empaquetados($codigoRecepcion,$id_evento){
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_validar_empaquetados($id_usuario,$id_evento,'$codigoRecepcion');");
        return $query->result();
    }      

    public function datos_archivo_xml($id_factura){
        $query = $this->db->query("select archivo,id_lote,correo from ope_factura where id_factura = $id_factura");
        return $query->result();
    }
      
    // FACTURACION

    public function datos_facturacion(){
        $idlogin = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_datos_facturacion($idlogin,0)");
        return $query->result();
      }
    
}
