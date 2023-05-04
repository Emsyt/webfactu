
<?php
/*
  ------------------------------------------------------------------------------
  Creado: Luis Fabricio Pari Wayar   Fecha:19/10/2022, Codigo:GAN-DPR-M6-0060
  Descripcion: Se creo el Modelo de "envio de productos" 
  ------------------------------------------------------------------------------
  Modificado: Oscar Laura Agurire Fecha:10/02/2023, Codigo: GAN-MS-B0-0254
  Descripcion: se agrego get_lst_ubicacion que devuelve una lista de ubicaciones
  menos la que es del usuario logueado.
*/
class M_envio extends CI_Model
{

  public function get_lst_solicitud($login, $id_ubicacion)
  {
    /*$query = $this->db->query("SELECT * FROM fn_listar_solicitud($login,$id_ubicacion);");
    return $query->result();*/
  }
  // INICIO Oscar L., GAN-MS-B0-0254 
  public function get_lst_ubicacion($login, $id_ubicacion)
  {
    $query = $this->db->query("SELECT * FROM fn_listar_ubicaciones_provision($id_ubicacion)");
    return $query->result();
  }
  // FIN GAN-MS-B0-0254 
  public function get_producto_cmb($id_ubicacion)
  {
    /*$query = $this->db->query("select * FROM fn_productos_ubicacion($id_ubicacion);");
    return $query->result();*/
  }

  public function get_cantidad_almacen($id_ubicacion, $id_movimiento)
  {
    /*$query = $this->db->query("SELECT * FROM fn_cantidad_producto_ubicacion($id_movimiento,$id_ubicacion); ");
    return $query->result();*/
  }

  public function contador_solicitudes($login, $id_ubicacion)
  {
    /*$query = $this->db->query("SELECT COUNT(id_movimiento) contador_solicitud 
      FROM mov_movimiento 
      WHERE apiestado = 'SOLICITUD'
      AND usucre = '$login'
      AND ubi_fin = $id_ubicacion ");
    return $query->row('contador_solicitud');*/
  }

  public function insert_solicitud($id_mod, $id_usuario, $json)
  {
    /*$query = $this->db->query("SELECT * FROM fn_registrar_solicitud($id_mod,$id_usuario,'$json'::JSON)");
    return $query->result();*/
  }

  public function confirmar_solicitud($id_usuario, $fec_entrega, $sel_ubi)
  {
    /*$query = $this->db->query("SELECT * FROM fn_confirmar_solicitud($id_usuario,'$fec_entrega',$sel_ubi);");
    return $query->result();*/
  }

  public function delete_solicitud($id_usuario, $id_prod)
  {
    /*$query = $this->db->query("SELECT * FROM fn_eliminar_solicitud($id_usuario,$id_prod); ");
    return $query->result();*/
  }
}
