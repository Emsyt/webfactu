<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Milena Rojas Fecha:29/06/2022, Codigo: Facturacion electrónica,
Descripcion: Se Realizo el frontend para la anulacion de facturas electrónicas
 */
?>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  var f = new Date();
  fechap_inicial = f.getFullYear() + "/" + (f.getMonth() + 1) + "/" + f.getDate();
  fechap_fin = f.getFullYear() + "/" + (f.getMonth() + 1) + "/" + f.getDate();
  var id_ubi = "PAGADO";

  $(document).ready(function() {
    activarMenu('menu17', 4);

    $('[name="fecha_inicial"]').val(fechap_inicial);
    $('[name="fecha_fin"]').val(fechap_fin);
    $('[name="tipofactura"]').val(1);
    cargartabla();
  });
</script>

<script>
  function enviar(destino) {
    document.form_busqueda.action = destino;
    document.form_busqueda.submit();
  }
</script>

<!-- BEGIN CONTENT-->
<div id="content">
  <section>
    <div class="section-header">
      <ol class="breadcrumb">
        <li><a href="#">Facturaci&oacute;n</a></li>
        <li class="active">Anulaci&oacute;n</li>
      </ol>
    </div>

    <?php if ($this->session->flashdata('success')) { ?>
      <script>
        window.onload = function mensaje() {
          swal(" ", "<?php echo $this->session->flashdata('success'); ?>", "success");
        }
      </script>
    <?php } else if ($this->session->flashdata('error')) { ?>
      <script>
        window.onload = function mensaje() {
          swal(" ", "<?php echo $this->session->flashdata('error'); ?>", "error");
        }
      </script>
    <?php } ?>

    <div class="section-body">
      <div class="row">
        <div class="col-md-10 col-md-offset-1">
          <form class="form" name="form_busqueda" id="form_busqueda" method="post" target="_blank">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2 text-right">
                    <img style="height: 65px;" src="assets/img/icoLogo/<?php $obj = json_decode($logo->fn_mostrar_ajustes);
                                                                        print($obj->{'logo'}); ?>">
                  </div>

                  <div class="col-xs-9 col-sm-9 col-md-7 col-lg-7 text-center">
                    <h5 class="text-ultra-bold" style="color:#655e60;"> EMPRESA <?php $obj = json_decode($titulo->fn_mostrar_ajustes);
                                                                                print_r($obj->{'titulo'}); ?> </h5>
                    <h5 class="text-ultra-bold" style="color:#655e60;"> ANULACION DE FACTURAS </h5>
                  </div>

                  <div class="col-xs-9 col-sm-9 col-md-3 col-lg-3">
                    <h6 class="text-ultra-bold text-default-light">Usuario: <?php echo $usuario; ?></h6>
                    <h6 class="text-ultra-bold text-default-light">Fecha: <?php echo $fecha_imp; ?></h6>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" style="text-align: center;">
                    <br>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <select class="form-control select2-list" id="tipofactura" name="tipofactura" required>
                        <option value="1">FACTURA COMPRA-VENTA</option>
                        <option value="24">NOTA DE CRÉDITO-DÉBITO</option>
                        <option value="41">FACTURA COMPRA VENTA TASAS</option>
                      </select>
                    </div>
                    <br>
                    <br>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="form-group">
                          <div class="input-group date" id="demo-date">
                            <div class="input-group-content">
                              <input type="text" class="form-control" name="fecha_inicial" id="fecha_inicial" readonly="" required>
                              <label for="fecha_inicial">Fecha Inicial</label>
                            </div>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <br>
                        <p>AL</p>
                      </div>
                      <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="form-group">
                          <div class="input-group date" id="demo-date-val">
                            <div class="input-group-content">
                              <input type="text" class="form-control" name="fecha_fin" id="fecha_fin" readonly="" required>
                              <label for="fecha_fin">Fecha Final</label>
                            </div>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <button class="btn ink-reaction btn-raised btn-primary" id="Buscar" name="Buscar" type="button" onclick="cargartabla()">BUSCAR FACTURAS</button><br><br>
                      <div class="form-group" id="process" style="display:none;">
                        <div class="progress">
                          <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="120">
                          </div>
                        </div>
                        <div class="status"></div>
                      </div>
                      <br>
                    </div>
                  </div>
                </div>
                <div>
                </div>
                <div class="table-responsive">
                  <table id="datatable3" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Nro. Factura</th>
                        <th>Fecha</th>
                        <th>Razon Social</th>
                        <th>Documento</th>
                        <th>Correo</th>
                        <th>Monto</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div><br></div>
              </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<!-- END CONTENT -->

<!-- Modal Tipo de Anulacion-->
<div class="modal fade bd-example-modal-sm" id="tipo_anulacion" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">

      <div class="modal-header card card-head style-primary">
        <center>
          <h3 class="text-ultra-bold"><b>TIPO DE ANULACI&oacute;N</b></h3>
        </center>
      </div>
      <div id="modal-body" style="padding-left: 20px; padding-right: 20px; margin-top: 10px; overflow-x: auto;">
        <div class="container-fluid form">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group floating-label" id="c_nrofactura">
                <input type="text" class="form-control" name="nrofactura" id="nrofactura">
                <label for="nrofactura">Nº Factura</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group floating-label" id="c_cuf">
                <textarea class="form-control" name="cuf" id="cuf" rows="3" disabled></textarea>
                <label for="cuf">Codigo de Autorización</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group floating-label" id="c_cuf">
                <select class="form-control select2-list" id="tipoanulacion" name="tipoanulacion" required>
                  <option value="1">FACTURA MAL EMITIDA</option>
                  <option value="2">NOTA DE CREDITO-DEBITO MAL EMITIDA</option>
                  <option value="3">DATOS DE EMISION INCORRECTOS</option>
                  <option value="4">FACTURA O NOTA DE CREDITO-DEBITO DEVUELTA</option>
                </select>
                <label for="cuf">Tipo de Anulación</label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group floating-label" id="c_correo">
                <input type="text" class="form-control" name="correo" id="correo">
                <label for="sigla">Correo Electr&oacute;nico</label>
              </div>
            </div>
          </div>

          <input type="hidden" class="form-control" name="rsocial" id="rsocial">
          <!-- <textarea name="textarea" rows="10" cols="50">Write something here</textarea> -->
        </div>
      </div>
      <div id="modal-footer" style="text-align: right; padding-right: 20px;  margin-top: 10px; margin-bottom: 10px;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn_emitir_nota" name="btn_emitir_nota" onclick="anular_factura()">Anular</button>

      </div>

    </div>
  </div>
</div>

<script>
  function cargartabla() {

    cod_tipofactura = document.getElementById("tipofactura");
    var tipofactura = cod_tipofactura.options[cod_tipofactura.selectedIndex].value;
    var fecha_inicial = document.getElementById("fecha_inicial").value;
    var fecha_fin = document.getElementById("fecha_fin").value;

    console.log(fecha_inicial, fecha_fin)
    $.ajax({
      url: '<?= site_url() ?>facturacion/C_anulacion/lst_listado_facturas/',
      type: "post",
      datatype: "json",
      data: {
        fecha_inicial: fecha_inicial,
        fecha_fin: fecha_fin,
        tipofactura: tipofactura
      },
      success: function(data) {

        var data = JSON.parse(data);
        console.log(data);
        var t = $('#datatable3').DataTable({
          "data": data,
          "responsive": true,
          "language": {
            "url": "<?= base_url() ?>assets/plugins/datatables_es/es-ar.json"
          },
          "destroy": true,
          "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
          }],
          "order": [
            [0, 'desc']
          ],
          "aoColumns": [{
              "mData": "id_lote"
            }, {
              "mData": "feccre"
            },
            {
              "mData": "razon_social"
            },
            {
              "mData": "numero_documento"
            },
            {
              "mData": "correo"
            },
            {
              "mData": "monto_cancelado"
            },
            {
              "mRender": function(data, type, row, meta) {
                var a = `
                  <button type="button" title="ANULAR FACTURA" class="btn ink-reaction btn-floating-action btn-xs btn-danger" onclick="eliminar_venta(\'${row.numero_documento}\',\'${row.cuf}\',\'${row.correo}\',${row.id_lote});" ><i class="fa fa-trash-o"></i></button>
                  `;
                return a;
              }
            }

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

  function eliminar_venta(numero_documento, cuf, correo, nrofactura) {
    console.log(cuf, correo, nrofactura);
    $('#cuf').val(cuf).trigger('change');
    if (correo == 'No se asigno un correo electronico' || correo == 'null') {
      correo = '';
    }
    $('#correo').val(correo).trigger('change');
    $('#rsocial').val(numero_documento).trigger('change');
    $('#nrofactura').val(nrofactura).trigger('change');
    $('#tipo_anulacion').modal('show');

  }

  function anular_factura() {
    $('#tipo_anulacion').modal('hide');
    cod_tipoanulacion = document.getElementById("tipoanulacion");
    var tipoanulacion = cod_tipoanulacion.options[cod_tipoanulacion.selectedIndex].value;
    var cuf = document.getElementById('cuf').value;
    var correo = document.getElementById('correo').value;
    var nrofactura = document.getElementById('nrofactura').value;
    cod_tipofactura = document.getElementById("tipofactura");
    var tipofactura = cod_tipofactura.options[cod_tipofactura.selectedIndex].value;

    // Swal.fire({
    //   icon: 'warning',
    //   title: "¿Esta seguro de Anular la factura?",
    //   showDenyButton: true,
    //   confirmButtonColor: '#3085d6',
    //   confirmButtonText: 'ACEPTAR',
    //   denyButtonText: 'CANCELAR',
    // }).then((result) => {
    // if (result.isConfirmed) {
    $.ajax({
      url: "<?php echo site_url('facturacion/C_anulacion/eliminar_factura') ?>",
      type: "POST",
      data: {
        dato1: cuf,
        dato2: tipofactura,
        correo: correo,
        nrofactura: nrofactura,
        tipoanulacion: tipoanulacion,
      },
      success: function(respuesta) {
        var js = JSON.parse(respuesta);
        console.log(js);
        if (!js.RespuestaServicioFacturacion.transaccion) {
          Swal.fire({
            icon: 'error',
            title: js.RespuestaServicioFacturacion.codigoDescripcion,
            text: js.RespuestaServicioFacturacion.mensajesList.descripcion,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ACEPTAR',
          }).then((result) => {
            if (result.isConfirmed) {
              location.href = "<?php echo base_url(); ?>anulacion_factura";
            }
          })
        } else {
          Swal.fire({
            icon: 'success',
            title: js.RespuestaServicioFacturacion.codigoDescripcion,
            text: 'La Factura se Anulo con exito',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'ACEPTAR',
          }).then((result) => {
            if (result.isConfirmed) {
              enviar_correo();
            }
          })
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {

        alert('Error al obtener datos de ajax', textStatus);
      }
    });
    // }
    // })

  }


  function enviar_correo() {

    var cuf = document.getElementById('cuf').value;
    var correo = document.getElementById('correo').value;
    var nrofactura = document.getElementById('nrofactura').value;
    var rsocial = document.getElementById('rsocial').value;
    $.ajax({
      url: "<?php echo site_url('facturacion/C_anulacion/enviar_correo') ?>",
      type: "POST",
      data: {
        cuf: cuf,
        correo: correo,
        nrofactura: nrofactura,
        rsocial: rsocial,
      },
      success: function(respuesta) {
        var js = JSON.parse(respuesta);
        console.log(js);
        location.href = "<?php echo base_url(); ?>anulacion_factura";
      },
      error: function(jqXHR, textStatus, errorThrown) {

        alert('Error al obtener datos de ajax', textStatus);
      }
    });

  }

  function enviar_correo2(rsocial, cuf, correo, nrofactura) {
    console.log(rsocial, cuf, correo, nrofactura);
    $.ajax({
      url: "<?php echo site_url('facturacion/C_anulacion/enviar_correo') ?>",
      type: "POST",
      data: {
        cuf: cuf,
        correo: correo,
        nrofactura: nrofactura,
        rsocial: rsocial,
      },
      success: function(respuesta) {
        console.log(respuesta);
        // var js = JSON.parse(respuesta);
        // console.log(js);
        //location.href = "<?php echo base_url(); ?>anulacion_factura";
      },
      error: function(jqXHR, textStatus, errorThrown) {

        alert('Error al obtener datos de ajax', textStatus);
      }
    });

  }
</script>