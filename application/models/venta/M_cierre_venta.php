<?php
/* A
------------------------------------------------------------------------------------------
Creador:Denilson Santander Rios Fecha:04/10/2022, GAN-MS-B9-0027,
Creacion del Model M_cierre_venta con sus respectivas funciones para la relacion con la base de datos
------------------------------------------------------------------------------
------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 04/09/2022 GAN-MS-B9-0027
Se agre la funcion get_datos
------------------------------------------------------------------------------

Modificado: Denilson Santander Rios Fecha: 07/10/2022 GAN-MS-M0-0036
Se agrego  la funcion  fn_gasto_cierre
------------------------------------------------------------------------------

Modificado: Denilson Santander Rios Fecha: 24/10/2022 GAN-CV-M0-0066
Se agrego la funcion mostrar_ingreso
------------------------------------------------------------------------------

*/
class M_cierre_venta extends CI_Model {

  public function get_lst_clientes() {
    $query = $this->db->query("SELECT * from vw_clientes vc where id_personas <> 0");
    return $query->result();
  }

  public function get_opecierre(){
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_get_opecierre($id_usuario)");
    return $query->result();
  }
  public function get_lst_reporte_ABMventas_c($json) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_cierre_abmventas($id_usuario,'$json'::JSON)");
    return $query->result();
  }

  public function get_eliminar_venta($idubicacion, $idlote, $usucre) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_eliminar_venta_completada($id_usuario,$idubicacion, $idlote,'$usucre')");
    return $query->result();
  }

  public function get_cargar_venta($idubicacion, $idlote, $usucre) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_cargar_venta($id_usuario,$idubicacion, $idlote,'$usucre')");
    return $query->result();
  }

  public function get_historial_venta($idubicacion, $idlote, $usucre) {
    $query = $this->db->query("SELECT * FROM fn_historial_venta($idubicacion, $idlote, '$usucre')");
    return $query->result();
  }

  public function get_ingresos_ventas($json) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_ingresos_dia($id_usuario,'$json'::JSON)");
    return $query->result();
  }

  public function get_datos($idubicacion,$fechaIni,$fechaFin) {
    
    $query = $this->db->query("SELECT * FROM fn_datos_cierre($idubicacion,'$fechaIni'::DATE,'$fechaFin'::DATE)");
    return $query->result();
  } 

 

  public function get_datos_seleccionado($idubicacion,$fechaIni,$fechaFin,$array) {
    $array = json_encode($array);
    $array = str_replace('"', '', $array);
    $query = $this->db->query("SELECT * FROM fn_datos_cierre_seleccionado($idubicacion,'$fechaIni'::DATE,'$fechaFin'::DATE,ARRAY$array,false)");
    return $query->result();
    
  } 

  public function gasto_cierre($idlogin,$json){
    $query = $this->db->query("SELECT * FROM fn_gasto_cierre($idlogin,'$json'::JSON)");
    return $query->result();
  }

  public function mostrar_ingreso($var, $var1, $var2){
    $query = $this->db->query("SELECT * FROM fn_mostrar_ingreso($var::NUMERIC, $var1::NUMERIC, $var2::NUMERIC)");
    return $query->result();
  }

  public function registro_cierre_caja($montoTotal, $montoGasto, $montoEntregar,$lotes,$f1,$f2,$imagen){
   $id_usuario = $this->session->userdata('id_usuario');
   $fechaActual = date('Y-m-d');
  
    $query = $this->db->query("SELECT * FROM fn_registro_cierre_caja($montoTotal, $montoGasto, $montoEntregar, $id_usuario,'$fechaActual','$lotes', '$f1', '$f2', '$imagen')");
    return $query->result();
  }

  public function get_lst_nota_venta_cotizacion($id_usuario,$id_venta) {
    $query = $this->db->query("SELECT * FROM fn_nota_venta($id_usuario,$id_venta,0)");
    return $query->result();
  }

  public function get_lst_entregas($idventa, $idlote) {
    $query = $this->db->query("SELECT * FROM fn_listar_productos_entrega($idventa, $idlote)");
    return $query->result();
  }

  public function get_ingresar_entrega($idventa, $idlote, $producto, $cantidad) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_registrar_productos_entrega($idventa, $idlote,'$producto'::text, $cantidad, '$id_usuario'::text)");
    return $query->result();
  }
  
  public function get_actualizar_entrega($identrega, $cant_Total_Entregada, $apiestado) {
    $id_usuario = $this->session->userdata('id_usuario');
    $query = $this->db->query("SELECT * FROM fn_cambiar_productos_entrega($identrega, $cant_Total_Entregada, $id_usuario, '$apiestado'::text)");  
    return $query->result();
  }
}