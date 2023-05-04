<?php
class M_punto_venta extends CI_Model {

    public function listar_punto_venta() {
        $query = $this->db->query("SELECT * FROM fn_listar_punto_venta();");
        return $query->result();
    }

    public function M_informacion_facturacion() {
        $query = $this->db->query("SELECT * FROM fn_informacion_facturacion();");
        return $query->result();
    }
    
    public function M_get_cuis($codigoPuntoVenta){
        $query = $this->db->query("SELECT oc.cod_cuis
                                    from ope_cuis oc 
                                    where oc.apiestado ilike 'ELABORADO'
                                    and oc.id_facturacion = (select cf.id_facturacion from cat_facturacion cf where apiestado ilike 'ELABORADO')
                                    and oc.cod_punto_venta = $codigoPuntoVenta");
        return $query->result();
    }

    public function M_get_cuis_inicial(){
        $query = $this->db->query("SELECT oc.cod_punto_venta,
                                          oc.cod_cuis
                                     from ope_cuis oc 
                                    where oc.apiestado ilike 'ELABORADO'
                                    and oc.id_facturacion = (select cf.id_facturacion from cat_facturacion cf where apiestado ilike 'ELABORADO')
                                    and oc.cod_punto_venta = 0");
        return $query->result();
    }

    public function M_gestionar_punto_venta_existente($json) {
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_gestionar_punto_venta_existente($id_usuario,'$json'::JSON);");
        return $query->result();
    }  


    public function M_registrar_cuis($json) {
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_registrar_cuis($id_usuario,'$json'::JSON);");
        return $query->result();
    }


    public function M_registrar_cufd($json){
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_registrar_cufd($id_usuario,'$json'::JSON)");
        return $query->result();
      }

//------------------------------------------------------------------
    public $id_facturacion = 4;

    public function set_punto_venta($id_venta,$cod_sistema,$cod_sucursal,$codigoTipoPuntoVenta,$descripcion,$nombre) {
        $query = $this->db->query("SELECT * FROM fn_registrar_punto_venta($id_venta,'$cod_sistema','$cod_sucursal',$codigoTipoPuntoVenta,'$descripcion','$nombre')");
        return $query->result();
    }



    public function get_nom_ubicacion($id_ubicacion) {
        $query = $this->db->query("SELECT * FROM fn_get_nom_ubicacion($id_ubicacion);");
        return $query->result();
    }

    public function get_datos_factura() {
        $query = $this->db->query("SELECT * FROM fn_datos_facturacion();");
        return $query->result();
    }

    public function eliminar_ubicacion_punto_venta($id_ubicacion) {
        $query = $this->db->query("SELECT * FROM fn_eliminar_ubicacion_punto_venta($id_ubicacion);");
        return $query->result();
    }
    public function get_nom_desc($id_venta) {
        $query = $this->db->query("SELECT * FROM fn_get_desc_nom($id_venta);");
        return $query->result();
    }      

    public function registrar_punto_venta_ubicacion($id_ubi,$codigo) {
        $query = $this->db->query("SELECT * FROM fn_registrar_punto_venta_ubicacion($id_ubi,$codigo);");
        return $query->result();
    }
    public function get_sucursales() {
        $query = $this->db->query("SELECT * FROM fn_listar_sucursales();");
        return $query->result();
    }
    public function get_ubicacion($id_ubicacion) {
        $query = $this->db->query("SELECT * FROM fn_get_ubicacion($id_ubicacion);");
        return $query->result();
    }

  
    // Funciones Facturacion



    public function registrar_cuis($json) {
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_registrar_cuis($id_usuario,'$json'::JSON);");
        return $query->result();
    }
  
    public function get_cuis() {
        $query = $this->db->query("SELECT * FROM fn_get_cuis(0);");
        return $query->result();
    }

    public function registrar_punto_venta($cod_punto,$cod_tipo,$id_facturacion,$descripcion,$nombre) {
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_registrar_punto_venta($id_usuario,$cod_punto,$cod_tipo,$id_facturacion,'$descripcion','$nombre')");
        return $query->result();
    }



    public function listar_punto_venta_ubicaciones() {
        $query = $this->db->query("SELECT * FROM fn_listar_punto_venta_ubicaciones();");
        return $query->result();
    }

    public function eliminar_punto_venta($id_facturacion,$cod_punto_venta) {
        $id_usuario = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_eliminar_punto_venta($id_usuario,$id_facturacion,$cod_punto_venta);");
        return $query->result();
    }      

    public function listar_tipo_venta() {
        $query = $this->db->query("SELECT * FROM fn_listar_tipo_venta();");
        return $query->result();
    }

    public function listar_ubicaciones() {
        $query = $this->db->query("SELECT * FROM fn_listar_ubicaciones_libres();");
        return $query->result();
    }        
}
