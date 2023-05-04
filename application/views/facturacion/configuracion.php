<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Brayan Janco Cahuana Fecha:29/06/2022, Codigo: Facturacion Computarizada,
Descripcion: Se Realizo el frontend para las configuraciones necesarias para la facturacion computarizada
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Gabriela Mamani Choquehuanca Fecha:20/07/2022, Codigo: GAN-MS-A1-314,
Descripcion: Se modifico en su totalidad la vista,ademas se crearon las funciones de mostrardatos,valores y valores1;
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Gabriela Mamani Choquehuanca Fecha:21/07/2022, Codigo: GAN-MS-A1-314,
Descripcion: Se añadio la funcion formulario;
 */
?>
<style>
  textarea {
    resize: none;
  }
</style>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  $(document).ready(function() {
    activarMenu('menu17', 6);
    datos_sistema();
  });
</script>

<script>
  // function enviar(destino) {
  //   document.form_busqueda.action = destino;
  //   document.form_busqueda.submit();
  // }
</script>
<style>
  .toggle.ios,
  .toggle-on.ios,
  .toggle-off.ios {
    border-radius: 20rem;
  }

  .toggle.ios .toggle-handle {
    border-radius: 20rem;
  }
</style>


<!-- BEGIN CONTENT-->
<div id="content">
  <section>
    <div class="section-header">
      <ol class="breadcrumb">
        <li><a href="#">Facturaci&oacute;n</a></li>
        <li class="active">Configuracion</li>
      </ol>
    </div>

    <div class="section-body">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <form class="form form-validate" novalidate="novalidate" name="form_configuracion" id="form_configuracion" method="post" action="<?= site_url() ?>facturacion/C_configuracion/add_update_facturacion">
            <div class="card">
              <div class="card-head style-primary">
                <header>Configuraci&oacute;n de sistemas inform&aacute;ticos de facturaci&oacute;n</header>
              </div>
              <div class="card-body">
                <div class="col-md-12">
                  <div class="row">
                    <input type="hidden" class="form-control" name="id_facturacion" id="id_facturacion">
                    <div class="col-md-6">
                      <div class="form-group floating-label" id="c_codigo">
                        <input type="text" class="form-control" name="codigo" id="codigo" required>
                        <label for="codigo">Codigo de Sistema</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group floating-label" id="c_nit">
                        <input type="text" class="form-control" name="nit" id="nit" required>
                        <label for="nit">NIT</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <select class="form-control select2-list" id="ambiente" name="ambiente" required>
                          <option value="2">PRUEBAS</option>
                          <option value="1">PRODUCCIÓN</option>
                        </select>
                        <label for="ambiente">Tipo Ambiente</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <select class="form-control select2-list" id="modalidad" name="modalidad" required>
                          <option value="1">ELECTRÓNICA EN LÍNEA</option>
                          <option value="2">COMPUTARIZADA EN LÍNEA</option>
                        </select>
                        <label for="modalidad">Tipo Modalidad</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <div class="floating-label" id="c_cafc">
                          <input type="text" class="form-control" name="cafc" id="cafc">
                          <label for="cafc">CAFC - Factura Compra Venta</label>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="col-sm-6">
                      <div class="form-group">
                        <select class="form-control select2-list" id="estado" name="estado" onchange="tipo_doc()" required>
                          <option value="0">EN LINEA</option>
                          <option value="1">CORTE DEL SERVICIO DE INTERNET</option>
                          <option value="2">INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA</option>
                          <option value="3">INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES</option>
                          <option value="4">VENTA EN LUGARES SIN INTERNET</option>
                        </select>
                        <label for="estado">Estado</label>
                      </div>
                    </div> -->
                  </div>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group floating-label" id="c_token">
                        <textarea class="form-control" name="token" id="token" rows="4"></textarea>
                        <label for="token">Token</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-actionbar">
                <div class="card-actionbar-row" style="display: block;" id="form1">
                  <button type="button" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_edit" value="edit" onclick="gestionar_sistema(this)">Guardar Cambios</button>
                  <button type="button" class="btn btn-flat btn-primary ink-reaction" onclick="formulario()"> Nueva Configuracion</button>
                </div>
                <div class="row" style="display: none;" id="form_registro12">
                  <div class="card-actionbar">
                    <div class="card-actionbar-row">
                      <button type="button" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_add" value="add" onclick="gestionar_sistema(this)">Registrar Configuracion</button>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </form>

        </div>

      </div>
    </div>

  </section>
</div>
<!-- END CONTENT -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header card card-head style-primary" style="text-align: center;">
        <header><b>REGISTRAR EVENTO SIGNIFICATIVO FUERA DE LINEA</b></header>
      </div>
      <div class="modal-body">
        <div class="container-fluid form">
          <div class="row">
            <div class="col-md-12">
              <font>SE A REACTIVADO LA FACTURACION EN LINEA, CON EL EVENTO SIGNIFICATIVO</font>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <select class="form-control select2-list" id="evento" name="evento" required disabled>
                  <?php foreach ($eventos as $evt) {  ?>
                    <option value="<?php echo $evt->id_tipo_evento ?>" <?php echo set_select('evento', $evt->id_tipo_evento) ?>>
                      <?php echo $evt->evento ?></option>
                  <?php  } ?>
                </select>
                <label for="evento">Evento Significativo</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="registrar_evento()">Registrar Evento</button>
      </div>
    </div>
  </div>
</div> -->

<!-- <div class="modal fade" id="evento_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header card card-head style-primary" style="text-align: center;">
        <header><b>CAMBIO DE ESTADO DE EMISIÓN</b></header>
      </div>
      <div class="modal-body">
        <div class="container-fluid form">
          <div class="row">
            <div class="col-md-12">
              <font>SELECCIONE EL TIPO DE ESTADO AL QUE SE CAMBIARA</font>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="form-group">
                <select class="form-control select2-list" id="estado" name="estado" required>
                  <?php foreach ($eventos as $evt) {  ?>
                    <option value="<?php echo $evt->id_tipo_evento ?>"><?php echo $evt->evento ?></option>
                  <?php  } ?>
                </select>
                <label for="estado">Evento Significativo</label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="registrar_estado()">Cambiar Estado</button>
      </div>
    </div>
  </div>
</div> -->

<script>
  function datos_sistema() {
    $.ajax({
      url: '<?= base_url() ?>facturacion/C_configuracion/C_datos_sistema',
      type: "POST",
      dataType: "JSON",
      success: function(data) {
        if (data.length > 0) {
          $('#id_facturacion').val(data[0].id_facturacion).trigger('change');
          $('#codigo').val(data[0].cod_sistema).trigger('change');
          $('#nit').val(data[0].nit).trigger('change');
          $('#ambiente').val(data[0].cod_ambiente).trigger('change');
          $('#modalidad').val(data[0].cod_modalidad).trigger('change');
          $('#cafc').val(data[0].cod_cafc).trigger('change');
          $('#estado').val(data[0].cod_emision).trigger('change');
          $('#token').val(data[0].cod_token).trigger('change');
        } else {
          formulario();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Error al obtener datos de ajax');
      }
    });
  };
  // Registra y edita un sistema de facturacion en la base de datos
  function gestionar_sistema(val) {
    if ($('#form_configuracion').valid()) {
      var formData = new FormData($('#form_configuracion')[0]);
      $.ajax({
        type: "POST",
        url: "<?= base_url() ?>facturacion/C_configuracion/C_gestionar_sistema/" + val.value,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(resp) {
          var c = JSON.parse(resp);
          if (c[0].oboolean == 't') {
            Swal.fire({
              icon: 'success',
              title: 'El agregado o modificación se realizó con éxito',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'ACEPTAR'
            }).then(function(result) {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: c[0].omensaje,
              confirmButtonColor: '#d33',
              confirmButtonText: 'ACEPTAR'
            })
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('Error al obtener datos de ajax');
        }
      });
    }
  };



  // function registrar_estado() {
  //   estado = document.getElementById("estado");
  //   var tipo = estado.options[estado.selectedIndex].value;

  //   var tipo_factura = document.getElementById("tipo_factura");
  //   tipo_factura = tipo_factura.options[tipo_factura.selectedIndex].value;
  //   var descripcion = tipo_factura;

  //   $.ajax({
  //     url: '<?= base_url() ?>facturacion/C_configuracion/cambio_tipo_estado',
  //     type: "POST",
  //     data: {
  //       tipo: tipo
  //     },
  //     success: function(respuesta) {
  //       if (respuesta) {
  //         console.log(respuesta + "aaa");
  //         $.ajax({
  //           url: '<?= base_url() ?>facturacion/C_configuracion/cambio_estado',
  //           type: "POST",
  //           data: {
  //             descripcion: descripcion
  //           },
  //           success: function(respuesta) {
  //             var json = JSON.parse(respuesta);
  //             var estado_activo = json[0].oestado;
  //             console.log(estado_activo);
  //             if (estado_activo == 1) {
  //               // $('#exampleModal').modal('show');
  //               registrar_evento();
  //             } else {
  //               location.href = "<?php echo base_url(); ?>control";
  //             }
  //           },
  //           error: function(jqXHR, textStatus, errorThrown) {
  //             Swal.fire({
  //               icon: 'error',
  //               title: 'Sucedio un error con el cambio de estado',
  //               text: 'Comuniquese con el administrador de sistemas ECONOTEC',
  //               confirmButtonColor: '#d33',
  //               confirmButtonText: 'ACEPTAR'
  //             });
  //           }
  //         });
  //       }

  //     },
  //     error: function(jqXHR, textStatus, errorThrown) {
  //       Swal.fire({
  //         icon: 'error',
  //         title: 'Sucedio un error con el cambio de estado',
  //         text: 'Comuniquese con el administrador de sistemas ECONOTEC',
  //         confirmButtonColor: '#d33',
  //         confirmButtonText: 'ACEPTAR'
  //       });
  //     }
  //   });
  // }

  // function tipo_doc() {
  //   cod = document.getElementById("estado");
  //   var codigo = cod.options[cod.selectedIndex].value;
  //   console.log(codigo);
  //   var evento = $('#estado :selected').text();
  //   evento = evento.trim()
  //   console.log(evento);
  //   var estado = '<?php echo $estado_emision[0]->cod_emision ?>';
  //   console.log(estado);
  //   if (estado == 0) {
  //     if (codigo != 0) {
  //       $.ajax({
  //         url: '<?= base_url() ?>facturacion/C_configuracion/C_registrar_inicio_evento',
  //         type: "POST",
  //         data: {
  //           codigo: codigo,
  //           evento: evento,
  //         },
  //         success: function(respuesta) {
  //           console.log(respuesta)
  //           var json = JSON.parse(respuesta);
  //           var estado_activo = json[0].oestado;
  //           console.log(estado_activo);
  //           if (estado_activo != 0) {
  //             location.href = "<?php echo base_url(); ?>control";
  //           }
  //         },
  //         error: function(jqXHR, textStatus, errorThrown) {
  //           Swal.fire({
  //             icon: 'error',
  //             title: 'Sucedio un error con el cambio de estado',
  //             text: 'Comuniquese con el administrador de sistemas ECONOTEC',
  //             confirmButtonColor: '#d33',
  //             confirmButtonText: 'ACEPTAR'
  //           });
  //         }
  //       });
  //     }
  //   } else {
  //     if (codigo == 0) {
  //       $.ajax({
  //         url: '<?= base_url() ?>facturacion/C_configuracion/C_registrar_inicio_evento',
  //         type: "POST",
  //         data: {
  //           codigo: codigo,
  //           evento: evento,
  //         },
  //         success: function(respuesta) {
  //           console.log(respuesta)
  //           var json = JSON.parse(respuesta);
  //           var estado_activo = json[0].oestado;
  //           estado = '<?php echo $estado_emision[0]->cod_emision ?>';
  //           console.log(estado);
  //           if (estado_activo == 0) {
  //             registrar_evento_fin(estado);
  //           } else {
  //             location.href = "<?php echo base_url(); ?>control";
  //           }
  //         },
  //         error: function(jqXHR, textStatus, errorThrown) {
  //           Swal.fire({
  //             icon: 'error',
  //             title: 'Sucedio un error con el cambio de estado',
  //             text: 'Comuniquese con el administrador de sistemas ECONOTEC',
  //             confirmButtonColor: '#d33',
  //             confirmButtonText: 'ACEPTAR'
  //           });
  //         }
  //       });
  //     }

  //   }

    // function registrar_evento_fin(cod_estado) {
      
    //   var evento = '';
    //   var estado_num = parseInt(cod_estado);
    //   switch (estado_num) {
    //     case 1:
    //       evento = 'CORTE DEL SERVICIO DE INTERNET';
    //       break;
    //     case 2:
    //       evento = 'INACCESIBILIDAD AL SERVICIO WEB DE LA ADMINISTRACIÓN TRIBUTARIA';
    //       break;
    //     case 3:
    //       evento = 'INGRESO A ZONAS SIN INTERNET POR DESPLIEGUE DE PUNTO DE VENTA EN VEHICULOS AUTOMOTORES';
    //       break;
    //     case 4:
    //       evento = 'VENTA EN LUGARES SIN INTERNET';
    //       break;
    //   }
    //   $.ajax({
    //     url: '<?= base_url() ?>facturacion/C_configuracion/C_registrar_evento_fin',
    //     type: "post",
    //     datatype: "json",
    //     data: {
    //       codigo: estado_num,
    //       evento: evento,

    //     },
    //     success: function(respuesta) {

    //       console.log(respuesta);
    //       //location.href = "<?php echo base_url(); ?>control";
    //       // var json = JSON.parse(respuesta);
    //       // fecha_inicial = json[1];
    //       // fecha_fin = json[2];
    //       // json = JSON.parse(json[0]);
    //       // var resp = json.RespuestaListaEventos.transaccion;
    //       // console.log(json);
    //       // if (resp == false) {
    //       //   Swal.fire({
    //       //     icon: 'error',
    //       //     text: json.RespuestaListaEventos.mensajesList.descripcion,
    //       //     confirmButtonColor: '#3085d6',
    //       //     confirmButtonText: 'ACEPTAR',
    //       //   })
    //       // } else {
    //       //   Swal.fire({
    //       //     icon: 'success',
    //       //     title: 'Se registro con exito',
    //       //     confirmButtonColor: '#3085d6',
    //       //     confirmButtonText: 'ACEPTAR',
    //       //   }).then((result) => {

    //       //     var codg = json.RespuestaListaEventos.codigoRecepcionEventoSignificativo
    //       //     console.log(fecha_inicial);
    //       //     console.log(fecha_fin);
    //       //     console.log(codg);
    //       //     $.ajax({
    //       //       url: '<?= base_url() ?>facturacion/C_configuracion/registro_evento',
    //       //       type: "post",
    //       //       datatype: "json",
    //       //       data: {
    //       //         codg: codg,
    //       //         codigo: codigo,
    //       //         fecha_inicial: fecha_inicial,
    //       //         fecha_fin: fecha_fin,
    //       //       }
    //       //     });
    //       //     location.href = "<?php echo base_url(); ?>control";

    //       //   })
    //       // }

    //     },

    //     error: function(jqXHR, textStatus, errorThrown) {
    //       alert('Error al obtener datos de ajax');
    //     }
    //   });
    // }

    //var descripcion = tipo;
    // 


    // console.log(estado);
    // if (tipo == 'EN LINEA') {
    //   tipo = 1;
    // } else {
    //   tipo = 2;
    // }
    // console.log(estado);
    // if (tipo != estado) {
    //   if (estado == 1) {
    //     $('#evento_modal').modal('show');
    //   } else {

    //     Swal.fire({
    //       icon: 'question',
    //       title: '¿PREGUNTA?',
    //       text: '¿Desea cambiar el estado de la emisión y envío de Facturas Digitales?',
    //       confirmButtonColor: '#3085d6',
    //       confirmButtonText: 'ACEPTAR',
    //       showDenyButton: true,
    //       denyButtonText: 'CANCELAR',
    //     }).then((result) => {
    //       if (result.isConfirmed) {
    //         $.ajax({
    //           url: '<?= base_url() ?>facturacion/C_configuracion/cambio_estado',
    //           type: "POST",
    //           data: {
    //             descripcion: descripcion
    //           },
    //           success: function(respuesta) {
    //             var json = JSON.parse(respuesta);
    //             var estado_activo = json[0].oestado;
    //             console.log(estado_activo);
    //             if (estado_activo == 1) {
    //               var estado = '<?php echo $cod_estado[0]->cod_estado ?>';

    //               $('[name="evento"]').val(estado).trigger('change');
    //               //$('#exampleModal').modal('show');
    //               registrar_evento();
    //             } else {
    //               location.href = "<?php echo base_url(); ?>control";
    //             }
    //           },
    //           error: function(jqXHR, textStatus, errorThrown) {
    //             Swal.fire({
    //               icon: 'error',
    //               title: 'Sucedio un error con el cambio de estado',
    //               text: 'Comuniquese con el administrador de sistemas ECONOTEC',
    //               confirmButtonColor: '#d33',
    //               confirmButtonText: 'ACEPTAR'
    //             });
    //           }
    //         });


    //       } else {
    //         if (estado == 1) {
    //           $('#tipo_factura').val('EN LINEA').trigger('change');
    //         } else {
    //           $('#tipo_factura').val('SIN LINEA').trigger('change');
    //         }
    //       }
    //     })
    //   }


    // }
  // }

  function formulario() {

    document.getElementById("form_registro12").style.display = "block";

    document.getElementById("form1").style.display = "none";
    $('#form_configuracion')[0].reset();
  }

  // function estado() {
  //   cod = document.getElementById("tipo_factura");
  //   var codigo = cod.options[cod.selectedIndex].value;
  //   //console.log(codigo);
  //   $.ajax({
  //     url: '<?= base_url() ?>facturacion/C_configuracion/cambio_estado',
  //     type: "post",
  //     datatype: "json",
  //     data: {
  //       codigo: codigo,
  //     },
  //     success: function(data) {

  //       console.log(data);
  //       if (data == true) {
  //         Swal.fire({
  //           icon: 'success',
  //           title: 'Se realizó los cambios con exito',
  //           confirmButtonColor: '#3085d6',
  //           confirmButtonText: 'ACEPTAR',
  //         })
  //       } else {
  //         Swal.fire({
  //           icon: 'error',
  //           title: 'No se pudo realizar los cambios',
  //           confirmButtonColor: '#3085d6',
  //           confirmButtonText: 'ACEPTAR',
  //         })
  //       }

  //     },
  //     error: function(jqXHR, textStatus, errorThrown) {
  //       alert('Error al obtener datos de ajax');
  //     }
  //   });
  // }

  // function registrar_evento() {
  //   cod = document.getElementById("evento");
  //   var codigo = cod.options[cod.selectedIndex].value;
  //   console.log(codigo);
  //   var evento = $('#evento :selected').text();
  //   evento = evento.trim()
  //   console.log(evento);

  //   $.ajax({
  //     url: '<?= base_url() ?>facturacion/C_configuracion/eventos_fuera_de_linea',
  //     type: "post",
  //     datatype: "json",
  //     data: {
  //       codigo: codigo,
  //       evento: evento,

  //     },
  //     success: function(respuesta) {

  //       console.log(respuesta);
  //       var json = JSON.parse(respuesta);
  //       fecha_inicial = json[1];
  //       fecha_fin = json[2];
  //       json = JSON.parse(json[0]);
  //       var resp = json.RespuestaListaEventos.transaccion;
  //       console.log(json);
  //       if (resp == false) {
  //         Swal.fire({
  //           icon: 'error',
  //           text: json.RespuestaListaEventos.mensajesList.descripcion,
  //           confirmButtonColor: '#3085d6',
  //           confirmButtonText: 'ACEPTAR',
  //         })
  //       } else {
  //         Swal.fire({
  //           icon: 'success',
  //           title: 'Se registro con exito',
  //           confirmButtonColor: '#3085d6',
  //           confirmButtonText: 'ACEPTAR',
  //         }).then((result) => {

  //           var codg = json.RespuestaListaEventos.codigoRecepcionEventoSignificativo
  //           console.log(fecha_inicial);
  //           console.log(fecha_fin);
  //           console.log(codg);
  //           $.ajax({
  //             url: '<?= base_url() ?>facturacion/C_configuracion/registro_evento',
  //             type: "post",
  //             datatype: "json",
  //             data: {
  //               codg: codg,
  //               codigo: codigo,
  //               fecha_inicial: fecha_inicial,
  //               fecha_fin: fecha_fin,
  //             }
  //           });
  //           location.href = "<?php echo base_url(); ?>control";

  //         })
  //       }

  //     },

  //     error: function(jqXHR, textStatus, errorThrown) {
  //       alert('Error al obtener datos de ajax');
  //     }
  //   });
  // }
</script>