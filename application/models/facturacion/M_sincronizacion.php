<?php
class M_sincronizacion extends CI_Model {

       public function datos_facturacion(){
        $idlogin = $this->session->userdata('id_usuario');
        $query = $this->db->query("SELECT * FROM fn_datos_facturacion($idlogin,0)");
        return $query->result();
      }
}