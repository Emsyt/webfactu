<?php
/*A
    -------------------------------------------------------------------------------------------------------------------------------
    Creacion: Melvin Salvador Cussi Callisaya Fecha 23/05/2022, Codigo: GAN-MS-A5-235
    Descripcion: se realizo el modulo de salida_de_produccion segun actividad GAN-MS-A5-235
    -------------------------------------------------------------------------------------------------------------------------------
    Modificacion: Alison Paola Pari Pareja   Fecha:11/08/2022   Actividad:GAN-MS-A1-337
    Descripcion: se realizaron los modificaciones y correcciones en salida_produccion
    -------------------------------------------------------------------------------------------------------------------------------
    Modificacion: Alvaro Ruben Gonzales Vilte   Fecha:29/09/2022   Actividad:GAN-MS-B8-0004
    Descripcion:  al realizar un registro de salida se muestra la hora y fecha actual del registro
    -------------------------------------------------------------------------------------------------------------------------------
    Modificacion: Ariel Ramos Paucara     Fecha:22/03/2023     Actividad:GAN-DPR-M5-0245
    Descripcion:  Se adiciono una funcion en javascript "function cargarDatosCombo(idlote)" para traer datos 
    de cat_productos menos los selecionados que ingresaron mediante un identificador "id_lote"
*/
?>
<?php if (in_array("smod_sal_prod", $permisos)) { ?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.min.js"></script>
<link rel="stylesheet" href="sweetalert2.min.css">

<script type="text/javascript">
    //INICIO Ariel GAN-DPR-M5-0245
    function cargarDatosCombo(idlote) {
        $.ajax({
            url: '<?=base_url()?>produccion/C_salida_produccion/get_lote_selected/'+ idlote,
            type: 'post',
            dataType: 'json',
            success: function (data) {
                let arrayIds = []; // declaramos un array vacío
                $.each(data, function(key, value) {
                    let da = value.oidproducto;
                    arrayIds.push(da); // agregamos cada valor a arrayIds
                });
                // console.log(arrayIds);
                var selectProductos = $('select[name="id_producto"]');
                // crea un objeto vacío
                var valoresAgregados = {};
                $.ajax({
                    url: '<?=base_url()?>produccion/C_salida_produccion/get_idproducto',
                    type: 'post',
                    data: { arrayIds: arrayIds },
                    success: function (data) {
                        $.each(data, function(key, value) {
                            selectProductos.removeAttr("disabled");
                            // verifica si esta la id
                            if (!valoresAgregados[value.oidproducto]) {
                                // agrega la opción y marca el valor como agregado
                                selectProductos.append('<option value="' + value.oidproducto + '">' + value.oproducto + '</option>');
                                valoresAgregados[value.oidproducto] = true;
                            }
                        });
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    }
    //FIN Ariel GAN-DPR-M5-0245
    $(document).ready(function() {
    activarMenu('menu10', 2);
    var count = 0;
    $('#agregarCampo1').click(function(e) {
        count++;
        document.getElementById("count").value = count;

        var fila = '<div class="row" id="cont' + count + '">\
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3">\
                        <div class="form-group floating-label" id="c_producto'+count+'">\
                            <select class="form-control select2-list" name="id_producto" onchange="val_salida('+count+')"\
                             id="producto' + count + '" required disabled>\
                                <option value="">&nbsp;</option>\
                            </select>\
                            <label for="id_producto' + count + '">Seleccione Producto</label>\
                        </div>\
                    </div>\
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">\
                        <div class="form-group floating-label" id="c_cantidad'+count+'">\
                            <input type="number" class="form-control" id="cantidad' + count + '" name="cantidad' +
                             count + '"\
                             required>\
                            <label for="cantidad">Cantidad</label>\
                        </div>\
                    </div>\
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3">\
                        <div class="form-group floating-label" id="c_unidad'+count+'">\
                            <select class="form-control select2-list" name="id_unidad' +
                                count + '" id="id_unidad' +
                                count + '"  required>\
                                <option value="">&nbsp;</option>\
                                <?php foreach ($unidad as $uni) { ?>\
                                    <option value="<?php echo $uni->oidunidades ?>"> <?php echo $uni->ounidad ?>\
                                    </option>\
                                <?php } ?>\
                            </select>\
                            <label for="id_unidad">Unidad</label>\
                        </div>\
                    </div>\
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3">\
                        <div class="form-group floating-label" id="c_destino'+count+'">\
                            <select class="form-control select2-list" name="id_destino' +
                            count + '"\
                            id="id_destino' + count + '" required>\
                                <option value="">&nbsp;</option>\
                                <?php foreach ($ubicacion as $ubic) { ?>\
                                    <option value="<?php echo $ubic->oidubicacion ?>">\
                                        <?php echo $ubic->oubicacion ?> </option>\
                                <?php } ?>\
                            </select>\
                            <label for="id_destino">Seleccione Destino</label>\
                        </div>\
                    </div>\
              <button type="button" class="eliminarContenedor1 btn btn-floating-action btn-danger" onclick"eliminarcontenedor()"><i class="fa fa-trash"></i></button>\
            </div>';
        $('#contenedor1').append(fila);

        //INICIO Ariel GAN-DPR-M5-0245
        let idlote = $('#input-hidden-id').val();
        cargarDatosCombo(idlote);
        //FIN Ariel GAN-DPR-M5-0245

        $(".select2-list").select2({
            allowClear: true,
            language: "es"
        });
        $('#id_producto' + count).html($('#id_producto').html());
            $('#cantidad' + count).html($('#cantidad').html());
            $('#id_unidad' + count).html($('#id_unidad').html());
        return count;
    });
    $("body").on("click", ".eliminarContenedor1", function(e) {
        var e = $(this).parent('div');
        e.remove();
    });

});
</script>
<style>
  table.dataTable tbody tr.selected {
  
  color: #353132;
}
</style>
<!-- BEGIN CONTENT-->
<div id="content">
    <section>
        <div class="section-header">
            <ol class="breadcrumb">
                <li><a href="#">Producci&oacute;n</a></li>
                <li class="active">Salida</li>
            </ol>
        </div>
        <?php if ($this->session->flashdata('success')) { ?>
      <script>
        window.onload = function mensaje() {
          swal.fire({
                        icon: 'success',
                        title:  "<?php echo $this->session->flashdata('success'); ?>",
                        confirmButtonText: 'Continuar'
                    });
        }
      </script>
    <?php } else if ($this->session->flashdata('error')) { ?>
      <script>
        window.onload = function mensaje() {
          swal.fire({
                        icon: 'error',
                        title:  "<?php echo $this->session->flashdata('error'); ?>",
                        confirmButtonText: 'Aceptar'
                    });
        }
      </script>
    <?php } ?>
        <div class="section-body">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <form class="form form-validate" novalidate="novalidate" name="registro_salida" id="registro_salida" style="display: none;"
                        enctype="multipart/form-data" method="POST"
                        action="<?= site_url() ?>produccion/C_salida_produccion/add_salida_produccion">
                        <input type="hidden" name="id_lote" id="id_lote" value="0">
                        <input type="hidden" name="count" id="count" value="0">
                        <div class="card">
                            <div class="card-head style-primary">
                                 <div class="tools">
                                    <div class="btn-group">
                                        <a class="btn btn-icon-toggle" onclick="cerrar_formulario()"><i class="md md-close"></i></a>
                                    </div>
                                 </div>
                                <header>Registro de Salida </header>
                            </div>
                            <div class="card-body" id="mod_salida">
                                <div class="row">
                                    <input type="hidden" id="input-hidden-id">
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3">
                                        <div class="form-group floating-label" id="c_producto">
                                            <select class="form-control select2-list" name="id_producto"
                                                id="producto" required disabled>
                                                <option value="" id="opcion">&nbsp;</option>
                                            </select>
                                            <label for="producto0">Seleccione Producto</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-2">
                                        <div class="form-group floating-label" id="c_cantidad0">
                                            <input type="number" class="form-control" id="cantidad0" name="cantidad0"
                                                required>
                                            <label for="cantidad0">Cantidad</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3">
                                        <div class="form-group floating-label" id="c_unidad0">
                                            <select class="form-control select2-list" name="id_unidad0" id="id_unidad0"
                                                required>
                                                <option value="">&nbsp;</option>
                                                <?php foreach ($unidad as $uni) { ?>
                                                <option value="<?php echo $uni->oidunidades ?>">
                                                    <?php echo $uni->ounidad ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                            <label for="unidad0">Unidad</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-3" >
                                        <div class="form-group floating-label" id="c_destino0">
                                            <select class="form-control select2-list" name="id_destino0" id="id_destino0" required>
                                                <option value="">&nbsp;</option>
                                                <?php foreach ($ubicacion as $ubic) { ?>
                                                    <option value="<?php echo $ubic->oidubicacion ?>">
                                                        <?php echo $ubic->oubicacion ?> </option>
                                                <?php } ?>
                                            </select>
                                            <label for="id_destino0">Seleccione Destino</label>
                                        </div>
                                    </div>
                                    <?php $x=0; ?>
                                     <button type="button" class="btn btn-floating-action btn-primary"
                                        id="agregarCampo1"><i class="fa fa-plus"></i></button> 
                                    </div>
                                    <div id="contenedor1"></div>
                                <div class="row">
                             
                                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
                                        <div class="form-group ">

                                            <div class="col-sm-8">
                                                <div class="input-group date" id="demo-date-val">
                                                    <div class="input-group-content" id="c_fecha">
                                                        <input type="text" class="form-control" name="fecmes"
                                                            id="fecmes" readonly="" required>
                                                        <label for="fecmes" class="col-sm-4 control-label"
                                                            id="fecha">Fecha</label>
                                                    </div>
                                                    <span class="input-group-addon"><i
                                                            class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-4">
                                                <input type="time" class="form-control gx-w-100" id="hora" name="hora">
                                                <label for="hora"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary ink-reaction btn-sm pull-right" name="btn"
                                    value="add"><span class="pull-left"><i class="  "></i></span>Registrar</button>
                            </div>
                        </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-divider visible-xs"><span>Listado de Paquetes</span></div>
                                <div class="card card-bordered style-primary">
                                    <div class="card-body style-default-bright">
                                        <div class="table-responsive">
                                            <table id="datatable1" class="table table-striped table-bordered">
                                                <thead>
                                                    <th>Acci&oacute;n</th>
                                                    <th>Nº de lote</th>
                                                    <th>Fecha</th>
                                                    <th>Hora</th>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($lst_salida as $sal) {
                                                            if($sal!= null){
                                                          ?>
                                                    <tr>
                                                        <td width="15%" align="center">
                                                        <?php if ($sal->boton1==0 && $sal->boton3==0){
                                                            ?>
                                                         
                                                            <button type="button" id="btn_show" title="Confirmar salida" disabled
                                                                    class="btn ink-reaction btn-floating-action btn-xs btn-success" 
                                                                    onclick="confirmar_salida('<?php echo $sal->olote ?>')"><i class="fa fa-book fa-lg"></i></button>
                                                            <button type="button" id="btn_edit" title="Modificar salida" 
                                                                class="btn ink-reaction btn-floating-action btn-xs btn-info btn-select" data-select="<?php echo $sal->olote ?>"
                                                                onclick="editar_salida_ingreso('<?php echo $sal->olote ?>')"><i
                                                                    class="fa fa-pencil-square-o fa-lg"></i></button>
                                                            <button type="button" id="btn_delete" title="Eliminar salida"
                                                                class="btn ink-reaction btn-floating-action btn-xs btn-danger" disabled
                                                                onclick="eliminar_salida('<?php echo $sal->olote ?>','<?php echo $this->session->userdata('usuario') ?>')"><i
                                                                    class="fa fa-trash-o fa-lg"></i></button>
                                                            <button type="button" id="btn_show" title="Mostrar detalle"
                                                                    class="btn ink-reaction btn-floating-action btn-xs btn-warning" 
                                                                    onclick="mostrar_detalle('<?php echo $sal->olote ?>')"><i class="fa fa-book fa-lg"></i></button>
                                                         <?php } else if($sal->boton1==1 && $sal->boton3==1){
                                                            ?>
                                                             <button type="button" id="btn_show" title="Confirmar salida"
                                                                    class="btn ink-reaction btn-floating-action btn-xs btn-success" 
                                                                    onclick="confirmar_salida('<?php echo $sal->olote ?>')"><i class="fa fa-book fa-lg"></i></button>
                                                            <button type="button" id="btn_edit" title="Modificar salida" 
                                                                class="btn ink-reaction btn-floating-action btn-xs btn-info"
                                                                onclick="editar_salida('<?php echo $sal->olote ?>')"><i
                                                                    class="fa fa-pencil-square-o fa-lg"></i></button>
                                                            <button type="button" id="btn_delete" title="Eliminar salida"
                                                                class="btn ink-reaction btn-floating-action btn-xs btn-danger"
                                                                onclick="eliminar_salida('<?php echo $sal->olote ?>','<?php echo $this->session->userdata('usuario') ?>')"><i
                                                                    class="fa fa-trash-o fa-lg"></i></button>
                                                            <button type="button" id="btn_show" title="Mostrar detalle"
                                                                    class="btn ink-reaction btn-floating-action btn-xs btn-warning" 
                                                                    onclick="mostrar_detalle('<?php echo $sal->olote ?>')"><i class="fa fa-book fa-lg"></i></button>
                                                       
                                                             <?php } 
                                                            ?>
                                                           
                                                        </td>
                                                        <td width="10%"><?php echo $sal->olote ?></td>
                                                        <td width="15%"><?php echo $sal->ofecha ?></td>
                                                        <td width="15%"><?php echo $sal->ohora ?></td>
                                                        </td>
                                                    </tr>
                                                    <?php } 
                                                    } ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            
        </div>
</div>


<div class="row">
    <div id="AjaxTblVentas"> </div>
</div>
</div>

</section>
</div>
<div class="modal fade" id="detalleLote" tabindex="-1" role="dialog" aria-labelledby="detalleLoteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-primary text-white">
                <h2 class="modal-title" id="detalleLoteLabel" style="text-align: center;"> DETALLES DE INGRESO DE PRODUCCION </h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <table id="tabla" class="table table-striped table-bordered">
                    <thead>
                        <th style="width: 10%;">Ubicacion</th>
                        <th style="width: 40%;">Producto</th>
                        <th style="width: 10%;">Cantidad</th>
                        <th style="width: 10%;">Unidad</th>
                        <th style="width: 25%;">Fecha</th>
                        <th style="width: 15%;">Hora</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <form class="form" name="lote_prod" id="lote_prod" method="post" >
                    <input type="hidden" class="form-control gx-w-100" id="lote_detalle" name="lote_detalle">
                    <!-- <button type="submit" id="btnSicambiar" class="btn btn-primary">Imprimir</button> -->
                    <button type="button" id="btnNocambiar" class="btn btn-secondary" onclick="cerrar_modal()">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->
<script>
    //INICIO Ariel GAN-DPR-M5-0245
    $(function(){
        $('button.btn-select').on('click', function(e) {
            $('select[name="id_producto"]').attr("disabled", true);
            var inputElement = $("input[type='hidden']#input-hidden-id");
            let idlote = $(this).attr("data-select");
            inputElement.val(idlote);
            cargarDatosCombo(idlote);
        });
    });
    //FIN Ariel GAN-DPR-M5-0245
function mostrar_detalle(id_lote) {
    console.log(id_lote);
$('#lote_detalle').val(id_lote);
$('#detalleLote').modal('show');
$.ajax({
    url: "<?= base_url() ?>produccion/C_salida_produccion/get_lote_salida",
    type: "POST",
    data: {
        id_lote: id_lote
    },
    success: function(data) {
        var data = JSON.parse(data);
        var t = $('#tabla').DataTable({
            data: data,
            responsive: true,
            language: {
                url: "<?= base_url() ?>assets/plugins/datatables_es/es-ar.json"
            },
            destroy: true,
            columnDefs: [{
                searchable: false,
                orderable: false,
                bSortable: false,
                targets: [0]
            }],
            aoColumns: [{
                    mData: "oubicacion",
                },
                {
                    mData: "oproducto",
                },
                {
                    mData: "ocantidad",
                },
                {
                    mData: "ounidad",
                },
                {
                    mData: "ofecha",
                },
                {
                    mData: "ohora",
                }
            ],
            aaSorting: [],
            dom: 'C<"clear">lfrtip',
            colVis: {
                "buttonText": "Columnas"
            }
        });

    },
    error: function(jqXHR, textStatus, errorThrown) {
        alert('Error get data from ajax');
    }
});
}
function cerrar_modal() {
$('#detalleLote').modal('hide');
}
function cerrar_formulario(){
    document.getElementById("registro_salida").style.display = "none";
  }

function val_salida(number) {
     
        $('#c_producto'+number).removeClass("floating-label");
        $('#c_cantidad'+number).removeClass('floating-label');
        $('#c_unidad'+number).removeClass("floating-label");
        $('#c_destino'+number).removeClass("floating-label");
   
  }
function confirmar_salida(id_lote) {
        Swal.fire({
            icon: 'question',
            title: '¿Desea confirmar la salida de produccion?',
            showCancelButton: true,
            confirmButtonText: 'Continuar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
               
                    $.ajax({
                        url: "<?php echo site_url('produccion/C_salida_produccion/confirmar_salida') ?>/" + id_lote,
                        type: "POST",
                        dataType: "JSON",
                        success: function(data) {
                            console.log(data);
                            if (data.oboolean == 't') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Confirmado con exito!',
                                    confirmButtonText: 'Continuar'
                                })
                                setTimeout(() => 5000);
                                location.reload() ;
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error al obtener datos de ajax');
                        }
                    });
              
            }
        })

    }
function editar_salida_ingreso(id) {
    console.log(id);
    document.getElementById('contenedor1').innerText = '';
    document.getElementById("registro_salida").style.display = "block";
    document.getElementById("registro_salida").reset();
    $('#registro_salida')[0].reset();
    $('#id_destino0').val(null).trigger("change");
    $('#id_producto0').val(null).trigger("change");
    $('#cantidad0').val(null).trigger("change");
    $('#id_unidad0').val(null).trigger("change");
    // $('#fecmes').val(null).trigger("change");
    // $('#hora').val(null).trigger("change");
    $('#id_lote').val(id).trigger('change');
    var f = new Date();
    fechaActual = f.getFullYear()+ "/" +(f.getMonth() +1)+ "/" +f.getDate();
    var horas = f.getHours();
    var minutos = f.getMinutes();
    if(horas < 10) { horas = '0' + horas; }
    if(minutos < 10) { minutos = '0' + minutos; }
    var horaActual = horas+":"+minutos+":00";
    $('#fecmes').val(fechaActual);   
    $('#hora').val(horaActual);
}
function editar_salida(id) {
    document.getElementById('contenedor1').innerHTML = '';
    document.getElementById('contenedor1').innerText = '';
    document.getElementById("registro_salida").style.display = "block";
    document.getElementById("registro_salida").reset();
    $('#registro_salida')[0].reset();
    $('#id_destino0').val(null).trigger("change");
    $('#id_producto0').val(null).trigger("change");
    $('#id_unidad0').val(null).trigger("change");
    var f = new Date();
    fechaActual = f.getFullYear()+ "/" +(f.getMonth() +1)+ "/" +f.getDate();
    var horas = f.getHours();
    var minutos = f.getMinutes();
    if(horas < 10) { horas = '0' + horas; }
    if(minutos < 10) { minutos = '0' + minutos; }
    var horaActual = horas+":"+minutos+":00";
    $('#fecmes').val(fechaActual);   
    $('#hora').val(horaActual);
    $.ajax({
        url: "<?php echo site_url('produccion/C_salida_produccion/datos_salida')?>/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            var inputs = new Array();
            JSON.parse(data.fn_recuperar_salida, function(k, v) {
                inputs[k] = v;
            });

            $('#id_lote').val(inputs['lote']).trigger('change')
            // $('#fecmes').val(inputs['fecha']).trigger('change')
            // $('#hora').val(inputs['hora']).trigger('change')
           
            var destino ;
            var producto;
            var cantidad;
            var unidad;
            var obj = JSON.parse(data.fn_recuperar_salida);
            console.log(obj);
                if ( obj.productos.length>=1) {
                    
                    destino = obj.productos[0].id_destino;
                    producto = obj.productos[0].id_producto;
                    cantidad = obj.productos[0].cantidad;
                    unidad = obj.productos[0].id_unidad;
                   
                    $("#id_destino" + 0).val(destino).trigger('change');
                    $("#id_producto" + 0).val(producto).trigger('change');
                    $("#cantidad" + 0).val(cantidad).trigger('change');
                    $("#id_unidad" + 0).val(unidad).trigger('change');
                }
            for (i = 0; i < obj.productos.length; i++) {
                $('#agregarCampo1').trigger('click');
                destino = obj.productos[i].id_destino;
                producto = obj.productos[i].id_producto;
                cantidad = obj.productos[i].cantidad;
                unidad = obj.productos[i].id_unidad;
                let num = document.getElementById("count").value;
                 $("#id_destino" + num).val(destino).trigger('change');
                 $("#id_producto" + num).val(producto).trigger('change');
                 $("#cantidad" + num).val(cantidad).trigger('change');
                 $("#id_unidad" + num).val(unidad).trigger('change');

            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
        }
    });
}

function eliminar_salida(id, login) {
    var titulo = 'ELIMINAR REGISTRO';
    var mensaje = '<div>Esta seguro que desea Eliminar el registro</div>';
    BootstrapDialog.show({
        title: titulo,
        message: mensaje,
        buttons: [{
            label: 'Aceptar',
            cssClass: 'btn-primary',
            action: function(dialog) {
                var $button = this;
                $button.disable();
                window.location = '<?= base_url() ?>produccion/C_salida_produccion/dlt_salida/' +
                    id + '/' + login;
            }
        }, {
            label: 'Cancelar',
            action: function(dialog) {
                dialog.close();
            }
        }]
    });
}
</script>
<?php } else {redirect('inicio');}?>
