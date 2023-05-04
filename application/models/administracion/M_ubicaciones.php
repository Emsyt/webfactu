<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Gabriela Mamani Choquehuanca Fecha:24/06/2022, Codigo: GAN-MS-A5-275,
Descripcion: Creacion del Model M_ubicaciones con sus respectivas funciones para la relacion con la base de datos.
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-290,
Descripcion: Se creo la funcion get_lst_ubicacion() para  mostrar los registros  en la tabla de la vista
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-291,
Descripcion: Se creo la funciones para  eliminar y modificar los registros  en la tabla de la vista
-------------------------------------------------------------------------------
Modificado: Gabriela Mamani Choquehuanca     Fecha: 09/08/2022    Código: GAN-MS-A1-330
Descripción: Se modifico la funcion insert_ubicacion para que ya no se tome en cuanta el atributo id_relacion
al momento de registrar
-------------------------------------------------------------------------------
Modificado: Gabriela Mamani Choquehuanca     Fecha: 09/08/2022    Código: GAN-MS-A1-330
Descripción: Se modifico la funcion modificar_ubicacion para que ya no se tome en cuanta el atributo id_relacion
al momento de modificar registros 
-------------------------------------------------------------------------------
Modificado: Jose Daniel Luna Flores     Fecha: 30/08/2022    Código: GAN-SC-M5-409
Descripción: Se reemplazo el query de la funcion modificar_ubicacion para que use la funcion creada por mi persona: fn_modificar_ubicacion
Se reemplazo el query de la funcion insert_ubicacion para que use la funcion creada por mi persona: fn_registrar_ubicacion, 
y se modificó la funcion para que reciba el valor de id_relacion para poder registrar una ubIcacion
-------------------------------------------------------------------------------
Modificado: Jose Daniel Luna Flores     Fecha: 07/08/2022    Código: GAN-MS-A1-435
Descripción: se realizó la recreación de la función que registra los campos en la tabla cat_ubicaciones, pero en este caso a la vez se
añadan todos los productos existentes en mov_provision y mov_inventario con cantidad y          
precio en 0 con la id de la ubicacion que se esta creando, para lo cual se esta modificando la función 'insert_ubicacion()'
*/

class M_ubicaciones extends CI_Model {
     public function insert_ubicacion($id_catalogo,$codigo ,$descripcion, $area,$usucre,$id_departamento, $latitud, $longitud, $id_relacion){
     // GAN-SC-M5-409 , 30/08/2022, dev_jluna 
        // GAN-MS-A1-435 , 07/08/2022, dev_jluna 
     $resultado = $this->db->query("SELECT * FROM fn_registrar_ubicacion($id_catalogo,'$codigo','$descripcion','$area','$usucre',$id_departamento,$latitud,$longitud, $id_relacion);");
     return $resultado->result();
        // FIN GAN-MS-A1-435 , 07/08/2022, dev_jluna 
     // FIN GAN-SC-M5-409 , 30/08/2022, dev_jluna  
     }

     public function get_lst_ubicacion(){
      $query = $this->db->query("SELECT * FROM fn_tipo_ubicacion()");
      return $query->result();
    }
   

    public function get_lst_ubicacion1(){
      $query = $this->db->query("SELECT * FROM fn_listas_ubicaciones()");
      return $query->result();
    }

    public function get_datos_ubicacion($id_ubi){
      $query = $this->db->query("SELECT * FROM fn_recuperar_ubicacion($id_ubi);");
      return $query->result();
    }

    public function delete_ubicacion($id_prov){
      $query = $this->db->query(" SELECT * FROM fn_eliminar_ubicacion($id_prov)");
      return $query->result();
    }

    // GAN-SC-M5-409 , 30/08/2022, dev_jluna 
    public function modificar_ubicacion($id_ubicacion,$id_catalogo,$codigo ,$descripcion, $area,$usucre,$id_departamento, $latitud, $longitud,$apiestado){
      $query = $this->db->query(" SELECT * FROM fn_modificar_ubicacion($id_ubicacion,$id_catalogo,'$codigo','$descripcion','$area','$usucre',$id_departamento, $latitud, $longitud,'ELABORADO')");
      return $query->result();
    }
    // FIN GAN-SC-M5-409 , 30/08/2022, dev_jluna  
}

