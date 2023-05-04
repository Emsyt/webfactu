<?php
class M_anulacion extends CI_Model {
    public $id_facturacion = 4;

    public function get_facturas_recepcionadas($fecha_inicial,$fecha_fin,$tipofactura){
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * 
                                    FROM ope_factura 
                                    WHERE codigo_recepcion notnull 
                                    and apiestado = 'ACEPTADO'
                                    and feccre::date BETWEEN '$fecha_inicial' AND '$fecha_fin'
                                    and codigo_documento_sector = $tipofactura");
        return $query->result();
    }

    public function anularFactura($SolicitudAnulacion, $cuf, $token,$tipo){
        if ($tipo == '3') {
            $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl";
            // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionDocumentoAjuste?wsdl";
        } else {
            $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
            // $wsdl ="https://siatrest.impuestos.gob.bo/v2/ServicioFacturacionCompraVenta?wsdl";
        }

        
        $client = new \SoapClient($wsdl, [
            'stream_context' => stream_context_create([
             'http'=> [
              'header' => "apikey: TokenApi $token"
             ]
             ]),
            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);
        if ($tipo == '3') {
            $respons = $client->anulacionDocumentoAjuste($SolicitudAnulacion);
        } else {
            $respons = $client->anulacionFactura($SolicitudAnulacion);
        }

        
        $this->db->query("UPDATE ope_factura SET apiestado = 'ANULADO' WHERE cuf = '$cuf'");

        return json_encode($respons);
    }

    
    public function fn_datos_facturacion(){
        $query = $this->db->query("select * from cat_facturacion;");
        return $query->result();
    }

    public function nit_emisor(){
        $query = $this->db->query("SELECT cf.nit from cat_facturacion cf where apiestado ilike 'ELABORADO'");
        return $query->result();
    }

    public function titulo(){
        $query = $this->db->query("SELECT cc.descripcion as otitulo from cat_catalogo cc where cc.catalogo ilike 'cat_sistema' and cc.codigo ilike 'titulo';");
        return $query->result();
      }
    //FACTURACION

    public function datos_facturacion(){
        $idlogin = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_datos_facturacion($idlogin,0)");
        return $query->result();
        }

    public function recuperar_nombre($nit){
        $query = $this->db->query("select (COALESCE(cp.nombre_rsocial,'')||
        CASE WHEN cp.apellidos_sigla IS NULL THEN '' ELSE ' '||cp.apellidos_sigla END) nombre_rsocial from cat_personas cp where cp.nit_ci ilike '$nit'");
        return $query->result();
    }
}