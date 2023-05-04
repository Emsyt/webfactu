<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Gabriela Mamani Choquehuanca Fecha:24/06/2022, Codigo: GAN-MS-A5-275,
Descripcion: Se creo la vista del ABM llamado Ubicaciones, el cual muestra el formulario de registro de ubicaciones 
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-290,
Descripcion: Se creo la vista del listado de Ubicaciones, el cual muestra la listado del registro de ubicaciones
-------------------------------------------------------------------------------------------------------------------------------
Modificacion: Gabriela Mamani Choquehuanca Fecha:27/06/2022, Codigo: GAN-MS-A4-291,
Descripcion: Se creo la vista para  eliminar y modificar los registros  del listado de Ubicaciones
.
 */
?>
<?php if (in_array("smod_ubi", $permisos)) { ?>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function(){
      activarMenu('menu8',4);
      listar_ubicaciones();
  });
</script>
<style>
  hr {
    margin-top: 0px;
  }
</style>
  <!-- BEGIN CONTENT-->
  <div id="content">
    <section>
      <div class="section-header">
          <ol class="breadcrumb">
          <li><a href="#">Administraci&oacute;n</a></li>
              <li class="active">Ubicaciones</li>
          </ol>
      </div>

      <?php if ($this->session->flashdata('success')) { ?>
        <script> window.onload = function mensaje(){ swal(" ","<?php echo $this->session->flashdata('success'); ?>","success"); } </script>
      <?php } else if($this->session->flashdata('error')){ ?>
        <script> window.onload = function mensaje(){ swal(" ","<?php echo $this->session->flashdata('error'); ?>","error"); } </script>
      <?php } ?>

      <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="text-primary">Listado de Ubicaciones
            <button type="button" class="btn btn-primary ink-reaction btn-sm pull-right" onclick="formulario()"><span class="pull-left"><i class="fa fa-plus"></i></span> &nbsp; Nueva Ubicacion</button>
            </h3>
            <hr>
          </div>
        </div>

        <div class="row" style="display: none;" id="form_registro">
          <div class="col-sm-8 col-md-9 col-lg-10 col-lg-offset-1">
            <div class="text-divider visible-xs"><span>Formulario de Registro</span></div>
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <form class="form form-validate" novalidate="novalidate" name="form_proveedor" id="form_proveedor" method="post" action="<?= site_url() ?>administracion/C_ubicaciones/add_update_ubicacion">
                  <input type="hidden" name="id_proveedor" id="id_proveedor">
                  <div class="card">
                    <div class="card-head style-primary">
                      <div class="tools">
                        <div class="btn-group">
                          <a class="btn btn-icon-toggle" onclick="update_formulario()"><i class="md md-refresh"></i></a>
                          <a class="btn btn-icon-toggle" onclick="cerrar_formulario()"><i class="md md-close"></i></a>
                        </div>
                      </div>
                      <header id="titulo"></header>
                    </div>

                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group floating-label" id="c_razon_social">
                            <select class="form-control select2-list" id="ubi_ini" name="ubi_ini"  required>
                              <option value=""></option>
                                <?php foreach ($lst_ubicacion as $ubi) {  ?>
                                 <option value="<?php echo $ubi->id_catalogo?>" <?php echo set_select('ubi_ini', $ubi->id_catalogo) ?>>
                                <?php echo strtoupper($ubi->descripcion ) ?></option>
                              <?php } ?>
                            </select>
                            <label for="producto">Seleccione Tipo Ubicacion</label>
                          </div>
                        </div>
                      </div>
                   

                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group floating-label" id="c_codigo">
                            <input type="text" class="form-control" name="codigo" id="codigo" required>
                            <input type="hidden" class="form-control" name="id_ubicacion" id="id_ubicacion">
                            <label for="sigla">Codigo</label>
                          </div>
                        </div>

                        <div class="col-sm-6">
                          <div class="form-group floating-label" id="c_descripcion">
                            <input type="text" class="form-control" name="descripcion" id="descripcion" required >
                            <label for="sigla">Descripcion</label>
                          </div>
                        </div>
                        
                        <div class="col-sm-6">
                          <div class="form-group floating-label" id="c_area">
                            <input type="text" class="form-control" name="area" id="area" >
                            <label for="sigla">Area</label>
                          </div>
                        </div>
                        
                      </div>
                    </div>

                    <div class="card-actionbar">
                      <div class="card-actionbar-row">
                        <button type="submit" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_edit" value="edit" disabled>Modificar Ubicacion</button>
                        <button type="submit" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_add" value="add">Registrar Ubicacion</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        
    <div class="row">
    <div class="col-md-12">
        <div class="text-divider visible-xs"><span>Listado de Ubicaciones</span></div>
        <div class="card card-bordered style-primary">
            <div class="card-body style-default-bright">
                <div class="table-responsive">
                    <table id="datatable_ubi" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Codigo</th>
                                <th>Ubicacion</th>
                                <th>Descripcion </th>
                                <th>Area</th>
                                <th>Acci&oacute;n</th>
                            </tr>
                        </thead>
                     
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
      
      </div>
    </section>
  </div>
  <!-- END CONTENT -->


<script>
 
  function formulario(){
    $("#titulo").text("Registrar Ubicacion");
    document.getElementById("form_registro").style.display = "block";
  }

  function cerrar_formulario(){
    document.getElementById("form_registro").style.display = "none";
  }

  function update_formulario(){
    $('#form_proveedor')[0].reset();
    $('#btn_edit').attr("disabled", true);
    $('#btn_add').attr("disabled", false);
  }

  function listar_ubicaciones(){
    $.ajax({
            url:'<?=base_url()?>administracion/C_ubicaciones/lista_ubicacion1',
            type:"post",
            datatype:"json",
           
            success: function(data){
                var data = JSON.parse(data);
                
 
                    var t = $('#datatable_ubi').DataTable({
                        "data": data,
                        "responsive": true,
                        "language": {
                        "url": "<?= base_url()?>assets/plugins/datatables_es/es-ar.json"
                        },
                        "destroy": true,
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "aoColumns": [
                            { "mData": "nro" },
                            { "mData": "codigo" },
                            { "mData": "catalogo" },
                            { "mData": "descripcion" },
                            { "mData": "direccion" },
                            {
                            "mRender": function(data, type, row, meta) {
                          
                                var a = `  
                                    <button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-danger" onclick=" eliminar_proveedor(${row.id_ubicacion})" title ="Eliminar" ><i class="fa fa-trash-o"></i></button>
                                    <button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-info" onclick=" editar_ubicaciones(${row.id_ubicacion})" title ="Modificar"><i class="fa fa-pencil-square-o fa-lg"></i></button> `;
                                return a;
                            }
                            },
                            
                        ],
                        "dom": 'C<"clear">lfrtip',
                        "colVis": {
                            "buttonText": "Columnas"
                        }
                    });
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
            }
        });
  }

  function editar_ubicaciones(id_ubi){
    $("#titulo").text("Modificar Ubicacion");
    document.getElementById("form_registro").style.display = "block";
    $('#form_proveedor')[0].reset();

    $('#btn_edit').attr("disabled", false);
    $('#btn_add').attr("disabled", true);

    $("#c_razon_social").removeClass("floating-label");
    $("#c_codigo").removeClass("floating-label");
    $("#c_descripcion").removeClass("floating-label");
    $("#c_area").removeClass("floating-label");

    $.ajax({
        url : "<?php echo site_url('administracion/C_ubicaciones/datos_ubicacion')?>/" + id_ubi,
        type: "POST",
        dataType: "JSON",
        success: function(data)
       
        {
          $('[name="id_ubicacion"]').val(id_ubi);

            $('[name="ubi_ini"]').val(data[0].id_catalogo).trigger('change');
            $('[name="codigo"]').val(data[0].codigo);
            $('[name="descripcion"]').val(data[0].descripcion);
            $('[name="area"]').val(data[0].direccion);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error al obtener datos de ajax');
        }
    });
  }

  function eliminar_proveedor(id_prov) {
     
      var titulo = 'ELIMINAR REGISTRO';
      var mensaje = '<div>Esta seguro que desea Eliminar el registro</div>';
    
    BootstrapDialog.show({
      title: titulo,
      message: $(mensaje),
      buttons: [{
          label: 'Aceptar',
          cssClass: 'btn-primary',
            action: function (dialog) {
             
              dialog.close();
       
          $.ajax({
            url:'<?=base_url()?>administracion/C_ubicaciones/dlt_ubicaciones/'+id_prov,
            type:"post",
            datatype:"json",
           
            success: function(data){
                var data = JSON.parse(data);
             
                if(data[0].oboolean=='t'){
                   Swal.fire({
                   icon: 'success',
                  text: "La ubicacion se ha eliminado correctamente",
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'ACEPTAR'
                 }).then((result) => {
                   
                    if (result.isConfirmed) {
                     location.reload();
                    } else{
                      location.reload();
                    }
                  })
                 
                }else{
                  Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Error',
                  
                   })
                }
        
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
            }
        });

            }
          }, {
          label: 'Cancelar',
          action: function (dialog) {
              dialog.close();
          }
      }]
    });
  }

</script>
<?php } else {redirect('inicio');}?>
