<?php
/* A
-------------------------------------------------------------------------------------------------------------------------------
Creador: Denilson Santander Rios Fecha:22/09/2022, GAN-MS-A1-474,
Descripcion: Creacion de la vista de cierre caja
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 26/09/2022 GAN-FR-A1-486
Descripcion: Se agrego un boton cierre para que nuestre un modal con un formulario, ademas se agrego la funcion de
seleccionar todos los check box

-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 28/09/2022 GAN-DPR-M0-0016
Descripcion: Se agrego la funcion de todo lo seleccionado se suma y se muestra en pantalla, ademas de que en en
la ventana modal se pueda agregar mas de un gasto

-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 30/09/2022 GAN-DPR-M0-0020
Descripcion: Se modifico el modal de cierre ademas de agregar un modal de confirmacion de cierre
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 04/09/2022 GAN-MS-B9-0027
Descripcion: Se agrego la funcinalidad de mostrar los datos de fn_datos_cierre
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 07/10/2022 GAN-MS-M0-0036
Descripcion: Se creo la funcion cerrar1 para la captura de los dats de n o mas gastos 
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 14/10/2022 GAN-PN-M4-0042
Descripcion: Se corrigio el boton seleccionar-todos para que al desplegar el modal almenos este marcado un check
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 24/10/2022 GAN-CV-M0-0066
Descripcion: Se agrego la funcionalidad de mostrar ingreso en la pantalla modal
-------------------------------------------------------------------------------------------------------------------------------
Modificado: Denilson Santander Rios Fecha: 1/11/2022 GAN-MS-A3-0083
Descripcion: Se realizaron correcciones en el codigo




*/
?>
<style>
.modalbody {
    padding: 5%;
}

.div1 {
    overflow: auto;
    height: 100px;
}

.div1 table {
    width: 100%;
    background-color: lightgray;
}
</style>
<script type="text/javascript">
var f = new Date();
fechap_inicial = f.getFullYear() + "/" + (f.getMonth() + 1) + "/" + f.getDate();
fechap_fin = f.getFullYear() + "/" + (f.getMonth() + 1) + "/" + f.getDate();
var id_cli = "-1";

$(document).ready(function() {
    activarMenu('menu5_5');
    $('[name="fecha_inicial"]').val(fechap_inicial);
    $('[name="fecha_fin"]').val(fechap_fin);
    opeCierre();
    buscar();

    //$('[name="cli_trabajo"]').val(id_cli);


    /* GAN-DPR-M0-0016 Denilson Santander Rios, 28/09/2000*/
    var x = 1;
    var MaxInputs = 100; //maximum input boxes allowed
    var FieldCount = 0; //to keep track of text box added
    var numerador = 1;

    //Begin contenedor 1
    $('#agregarCampo1').click(function(e) { //on add input button click

        if (x <= MaxInputs) { //max input box allowed
            FieldCount++; //text box added increment
            numerador++;
            // alert("Ingreso contenedor1: " + FieldCount)

            //add input box

            $('#contenedor1').append('<div class="row">\
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">\
                    <div class="form-group">\
                        <div class="input-group input-group-lg">\
                            <div class="input-group-content">\
                                <div class="col-xs-12 col-sm-4 col-md-5 col-lg-5">\
                                    <div class="form-group form-floating" id="c_descripcion' + FieldCount + '">\
                                        <input type="text" class="form-control" name="descripcion[]" id="descripcion' +
                FieldCount + '" min="1"  placeholder="Descripcion">\
                                        <label id="lable_produc' + FieldCount + '" for="descripcion"></label>\
                                        </div>\
                                    </div>\
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">\
                                        <div class="form-group form-floating" id="c_monto' + FieldCount + '">\
                                            <input type="number" class="form-control" name="monto[]" id="monto' +
                FieldCount + '" min="1" placeholder="Monto unitario">\
                                            <label id="lable_produc' + FieldCount + '" for="monto"></label>\
                                        </div>\
                                    </div>\
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">\
                                        <div class="form-group form-floating" id="c_cantidad' + FieldCount + '">\
                                            <input type="number" class="form-control" name="cantidad[]" id="cantidad' +
                FieldCount + '"min="1" placeholder="Cantidad">\
                                            <label id="lable_produc' + FieldCount + '" for="cantidad"></label>\
                                        </div>\
                                    </div>\
                                </div>\
                            <div class="input-group-btn">\
                                <button type="button" class="btn btn-floating-action btn-danger"  id="eliminarContenedor1"><i class="fa fa-trash"></i></button>\
                            </div>\
                        </div>\
                    </div>\
                </div>\
            </div> ');



            $(".select2-list").select2({
                allowClear: true,
                language: "es"
            });

            x++; //text box increment
        }
        $('#contador').val(FieldCount).trigger('change');
        $('#numerador').val(numerador).trigger('change');
        return false;
    });

    $("body").on("click", "#eliminarContenedor1", function(e) { //user click on remove text
        if (x > 1) {
            var e = $(this).parent('div').parent('div').parent('div').parent('div').parent('div');
            e.remove();
            x--; //decrement textbox
            numerador--;
            $('#numerador').val(numerador).trigger('change');
        }
        return false;
    });
    /* FIN GAN-DPR-M0-0016 Denilson Santander Rios, 28/09/2000*/




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
                <li><a href="#">Venta</a></li>
                <li class="active">Cierre ventas</li>
            </ol>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="text-primary">Cierre venta

                    </h3>
                    <hr>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="">
                        <form class="form" name="form_busqueda" id="form_busqueda" method="post" target="_blank">
                            <div class="card">

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" style="text-align: center;">
                                            <br>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                                    <div class="form-group">
                                                        <div class="input-group date" id="demo-date">
                                                            <div class="input-group-content">
                                                                <input type="text" class="form-control"
                                                                    name="fecha_inicial" id="fecha_inicial" readonly=""
                                                                    required>
                                                                <label for="fecha_inicial">Fecha Inicial</label>
                                                            </div>
                                                            <span class="input-group-addon"><i
                                                                    class="fa fa-calendar"></i></span>
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
                                                                <input type="text" class="form-control" name="fecha_fin"
                                                                    id="fecha_fin" readonly="" required>
                                                                <label for="fecha_fin">Fecha Final</label>
                                                            </div>
                                                            <span class="input-group-addon"><i
                                                                    class="fa fa-calendar"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <button class="btn ink-reaction btn-raised btn-primary" id="Buscar"
                                                    name="Buscar" type="button" onclick="buscar();">Generar
                                                    Listado</button><br><br>

                                                <div class="form-group" id="process" style="display:none;">
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-striped active"
                                                            role="progressbar" aria-valuemin="0" aria-valuemax="120">
                                                        </div>

                                                    </div>
                                                    <div class="status"></div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                                    style="text-align: center;">

                                                    <h4 class="text-ultra-bold" style="color:red; font-size: large;">
                                                        Total venta:</h4>
                                                    <h4 id="total_venta" class="text-ultra-bold"
                                                        style="color:red;font-size: x-large;">
                                                    </h4>
                                                </div>


                                            </div>

                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Total ventas
                                                (Seleccionado):</h4>
                                            <h4 id="total_venta_seleccionado" class="text-ultra-bold"
                                                style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Efectivo:</h4>
                                            <h4 id="pago_efectivo" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Tarjeta:</h4>
                                            <h4 id="pago_tarjeta" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">QR:</h4>
                                            <h4 id="pago_qr" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Giftcard:</h4>
                                            <h4 id="pago_giftcard" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Ventas a deuda:</h4>
                                            <h4 id="deuda_total" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Ventas a deuda
                                                (pagado):</h4>
                                            <h4 id="deuda_pagado" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Ventas a deuda
                                                (saldo):</h4>
                                            <h4 id="deuda_saldo" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1" style="text-align: center;">
                                            <br><br><br>
                                            <h4 class="text-ultra-bold" style="color:#655e60;">Total a entregar:</h4>
                                            <h4 id="total_entregar" class="text-ultra-bold" style="color:#655e60;"></h4>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="datatable3" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="5%">N&deg;</th>
                                                    <th width="16%">Codigo de Ventas</th>
                                                    <th width="16%">Cliente</th>
                                                    <th width="16%">Total</th>
                                                    <th width="16%">Fecha</th>
                                                    <th width="15%">Hora</th>
                                                    <th width="16%">Accion <input type="checkbox"
                                                            id="seleccionar-todos"> </th>


                                                </tr>
                                            </thead>
                                        </table>

                                    </div>
                                    <button type="button" class="btn btn-primary ink-reaction btn-sm pull-right"
                                        onclick="abrirModalCierre()" id="confirm">Cerrar</button>


                                    <div><br> </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>
<!-- END CONTENT -->



<!-- Modal cierre -->
<!-- GAN-DPR-M0-0016 Denilson Santander Rios, 28/09/2000-->

<div class="modal fade" id="modal_cierre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <center>
                    <h3 class="text-ultra-bold" style="color:#655e60;"><b>Cierre ventas</b></h3>
                </center>
            </div>

            <div id="modalbody1" style="margin:20px;">
                <input type="hidden" class="form-control" name="contador" id="contador" value="0">
                <form name="form_productos" id="form_productos">
                    <div id="productos">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div id="contenedor1">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <div class="input-group input-group-lg ">
                                                    <div class="input-group-content">
                                                        <div class="col-xs-12 col-sm-4 col-md-5 col-lg-5">
                                                            <div class="form-group form-floating" id="c_descripcion0">
                                                                <input type="text" class="form-control"
                                                                    name="descripcion[]" id="descripcion0" min="1"
                                                                    placeholder="Descripcion">
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                                                            <div class="form-group form-floating" id="c_monto0">
                                                                <input type="number" class="form-control" name="monto[]"
                                                                    id="monto0" min="1" placeholder="Monto unitario"
                                                                    style="width: 118px;"> <br>

                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                                                            <div class="form-group form-floating" id="c_cantidad0">
                                                                <input type="number" class="form-control"
                                                                    name="cantidad[]" id="cantidad0" min="1"
                                                                    placeholder="Cantidad" style="width: 100px;"> <br>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="input-group-btn">
                                                        <button type="button"
                                                            class="btn btn-floating-action btn-success"
                                                            id="agregarCampo1"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div id="modalfooter">
                <center>
                    <button type="button" class="btn ink-reaction btn-raised btn-primary" style="margin-bottom:20px;"
                        formtarget="_blank" onclick="cerrar()" data-toggle="modal"
                        data-target="#modal_afirmacion">Cerrar</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!--Modal de afirmacion-->
<div class="modal fade" id="modal_afirmacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <center>
                    <h3 class="text-ultra-bold" style="color:#655e60;"><b>Cierre ventas</b></h3>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <h4 class="text-ultra-bold" style="color:black; font-size: large;">Monto Total: <span
                        id="montoTotalFechas"></span> Bs. </h4>
                <h4 class="text-ultra-bold" style="color:black; font-size: large;">Monto Total seleccionado: <span
                        id="montoTotalSeleccionado"></span> Bs. </h4>
                <h4 class="text-ultra-bold" style="color:black; font-size: large;">Monto gasto: <span
                        id="montoGasto"></span> Bs. </h4>
                <h4 class="text-ultra-bold" style="color:green; font-size: large;">Monto a entregar: <span
                        id="resultadoEntregar"></span> Bs. </h4>
                <h4 class="text-ultra-bold" style="color:red; font-size: large;">Monto saldo: <span
                        id="resultadoSaldo"></span> Bs. </h4>
                <form method="post" action="">
                    <label class="btn btn-primary btn-sm btn-file ink-reaction btn-raised">
                        Seleccionar Imagen<input style="display: none;" type="file" id="files" name="img_factura"
                            class="form-control" />
                    </label>
                    <h5 id="nombre_archivo"></h5>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                <button type="button" class="btn btn-primary btnsubir" id="btn_confirmar"
                    onclick="cerrar1()">CONFIRMAR</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="afirmacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <center>
                    <h3 class="text-ultra-bold" style="color:#655e60;"><b>Cierre ventas</b></h3>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>CIERRE DE CAJA EXITOSO</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ACEPTAR</button>
            </div>
        </div>
    </div>
</div>






<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var cont = 0;
var globaslTotalVenta = 0;
var globalGasto = 0;
var globalMontoSeleccionado = 0;
var globalEntregar = 0;
var globalLotes = "";
var globalIdLotes = [];
var globalTodoMontoSeleccionado = 0;
var globalTodoMontoefectivo = 0;
var globalTodoMontoeEntregar = 0;
var globalMarcaTodos = false;
var arrayGlobal = [];



function opeCierre() {
    var a = "";
    var aux = "";
    $.ajax({
        url: "<?php echo site_url('venta/C_cierre_venta/datos_opecierre') ?>",
        type: "POST",
        success: function(data) {

            var data = JSON.parse(data);


            for (var i = 0; i < data.length; i++) {

                var a = data[i].oidlote.split("|");

                for (var j = 0; j < a.length; j++) {
                    aux = a[j];
                    if (aux != "") {
                        globalIdLotes.push(aux);
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
        }
    });
}

function buscar() {
    //contador para gestionar los productos entregados/registrados/por entregar

    let contEntrega = 0;
    let contRespuestaVentas = 0;

    var selc_frep = document.getElementById("fecha_inicial").value;
    var selc_finrep = document.getElementById("fecha_fin").value;



    $.ajax({
        url: "<?php echo site_url('venta/C_cierre_venta/lst_listado_ventas_c') ?>",
        type: "post",
        datatype: "json",
        data: {
            selc_frep: selc_frep,
            selc_finrep: selc_finrep
        },
        xhr: function() {
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
                xhr.upload.addEventListener('progress', function(event) {
                    var percent = 0;
                    var position = event.loaded || event.position;
                    var total = event.total;
                    if (event.lengthComputable) {
                        percent = Math.ceil(position / total * 100);
                    }
                    //update progressbar
                    $(".progress-bar").css("width", +percent + "%");
                    if (percent >= 100) {
                        var delayInMilliseconds = 230;
                        setTimeout(function() {
                            $('#process').css('display', 'none');
                            $('.progress-bar').css('width', '0%');
                            percent == 0;
                        }, delayInMilliseconds);
                    }
                }, true);
            }
            return xhr;
        },
        beforeSend: function() {
            $('#process').css('display', 'block');
        },
        success: function(data) {
            var data = JSON.parse(data);

            if (data.responce == "success") {
                var t = $('#datatable3').DataTable({
                    "data": data.posts,
                    "responsive": true,
                    "language": {
                        "url": "<?= base_url()?>assets/plugins/datatables_es/es-ar.json"
                    },
                    "destroy": true,
                    "columnDefs": [{
                        "searchable": false,
                        "orderable": false,
                        "targets": 0,
                        className: 'select-checkbox',
                        orderable: false,
                    }],
                    "order": [
                        [1, 'asc']
                    ],
                    "aoColumns": [{
                            "mData": "onro"
                        },
                        {
                            "mData": "ocodigo"
                        },
                        {
                            "mData": "ocliente"
                        },
                        {
                            "mData": "ototal"
                        },
                        {
                            "mData": "ofecha"
                        },
                        {
                            "mData": "ohora"
                        },
                        {
                            "mRender": function(data, type, row, meta) {

                                cont++;
                                //console.log(row.olote)
                                var varLote = row.olote;


                                if (!arrayGlobal.includes(Number(varLote))) {
                                    arrayGlobal.push(Number(varLote));
                                }


                                if (row.oidtipo == 1324 || row.oidtipo == 1280 || row
                                    .oidtipo == 1323 || globalIdLotes.includes(row.olote)) {




                                    var a = ` 
                                     <input disabled  type="checkbox" name="checkbox"  id="checkbox${cont}"  value="${row.olote}" onchange="cambioSeleccionado(${row.oidubicacion},${row.olote})">
                                     <input type="hidden" id="ubicacion" value="${row.oidubicacion}">
                                `;
                                    return a;
                                } else {
                                    var a = ` 
                                     <input type="checkbox" name="checkbox"  id="checkbox${cont}"  value="${row.olote}" onchange="cambioSeleccionado(${row.oidubicacion},${row.olote})">
                                     <input type="hidden" id="ubicacion" value="${row.oidubicacion}">
                                `;
                                    return a;

                                }

                            }
                        },

                    ],
                    "dom": 'C<"clear">lfrtip',
                    "colVis": {
                        "buttonText": "Columnas"
                    }
                });

                /*$('#confirm').click(function() {
                    
                    var cells = t.cells().nodes();
                    var array = [];
                    var array2 = [];
                    for (var c = 0; c < cont; c++) {
                        alert('Entra al for');
                        if ($(cells).find(':checkbox')[c].checked) {
                            alert('entra al if')
                            array.push($(cells).find(':checkbox')[c].value);
                            array2.push($(cells).find(':text')[c].value);
                        }
                        alert('sale del for')
                    }
                   
                    if (array.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Debe elegir por lo menos un producto!',
                        })
                    } else {
                        $('#modal_cierre').modal('show');
                    }
                    
                });*/
                /* GAN-DPR-M0-0016 Denilson Santander Rios, 28/09/2020*/
                $('#seleccionar-todos').change(function() {
                    var cells = t.cells().nodes();
                    $(cells).find(':checkbox').prop('checked', $(this).is(':checked'));

                    var marca = document.getElementById('seleccionar-todos');
                    globalMarcaTodos = true;
                    //console.log(arrayGlobal);

                    if (marca.checked) {
                        $.ajax({
                            url: "<?php echo site_url('venta/C_cierre_venta/cierre_ventas_datos') ?>",
                            type: "post",
                            datatype: "json",
                            data: {
                                dato1: document.getElementById("ubicacion").value,
                                selc_frep: selc_frep,
                                selc_finrep: selc_finrep
                            },
                            success: function(data) {

                                var data = JSON.parse(data);
                                var aux = data[0].fn_datos_cierre;
                                var aux2 = JSON.parse(aux);


                                document.getElementById("total_venta").innerHTML = aux2
                                    .total_venta;
                                document.getElementById("total_venta_seleccionado")
                                    .innerHTML = aux2.total_venta;
                                document.getElementById("pago_efectivo").innerHTML =
                                    aux2.total_venta_efectivo;
                                document.getElementById("pago_tarjeta").innerHTML = aux2
                                    .total_venta_tarjeta;
                                document.getElementById("pago_qr").innerHTML = aux2
                                    .total_venta_qr;
                                document.getElementById("pago_giftcard").innerHTML =
                                    aux2.total_venta_GiftCard;
                                document.getElementById("deuda_total").innerHTML = aux2
                                    .total_venta_deuda;
                                document.getElementById("deuda_pagado").innerHTML = aux2
                                    .total_venta_deuda_cancelado;
                                document.getElementById("deuda_saldo").innerHTML = aux2
                                    .total_venta_deuda_saldo;
                                document.getElementById("total_entregar").innerHTML =
                                    aux2.total_a_cerrar;

                                //globalTodoMontoSeleccionado = Number(aux2.total_venta);
                                globalTodoMontoefectivo = aux2.total_venta_efectivo;
                                globalTodoMontoeEntregar = aux2.total_a_cerrar;
                                globalMontoSeleccionado = aux2.total_a_cerrar;
                                //console.log(globalTodoMontoSeleccionado);
                                //console.log(globalTodoMontoefectivo);
                                //console.log(globalTodoMontoeEntregar);


                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Error al obtener datos de ajax');
                            }
                        });
                    } else {
                        $.ajax({
                            url: "<?php echo site_url('venta/C_cierre_venta/cierre_ventas_datos') ?>",
                            type: "post",
                            datatype: "json",
                            data: {
                                dato1: document.getElementById("ubicacion").value,
                                selc_frep: selc_frep,
                                selc_finrep: selc_finrep
                            },
                            success: function(data) {

                                var data = JSON.parse(data);
                                var aux = data[0].fn_datos_cierre;
                                var aux2 = JSON.parse(aux);

                                document.getElementById("total_venta_seleccionado")
                                    .innerHTML = 0;
                                document.getElementById("pago_efectivo").innerHTML = 0;
                                document.getElementById("pago_tarjeta").innerHTML = 0;
                                document.getElementById("pago_qr").innerHTML = 0;
                                document.getElementById("pago_giftcard").innerHTML = 0;
                                document.getElementById("deuda_total").innerHTML = 0;
                                document.getElementById("deuda_pagado").innerHTML = 0;
                                document.getElementById("deuda_saldo").innerHTML = 0;
                                document.getElementById("total_entregar").innerHTML = 0;

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Error al obtener datos de ajax');
                            }
                        });
                    }


                });
                /* FIN GAN-DPR-M0-0016 Denilson Santander Rios, 28/09/2020*/



                t.on('order.dt search.dt', function() {
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

                $('#contadorCheck').val(cont).trigger('change');
            }

            $.ajax({
                url: "<?php echo site_url('venta/C_cierre_venta/cierre_ventas_datos') ?>",
                type: "post",
                datatype: "json",
                data: {
                    dato1: document.getElementById("ubicacion").value,
                    selc_frep: selc_frep,
                    selc_finrep: selc_finrep
                },
                success: function(data) {

                    var data = JSON.parse(data);
                    var aux = data[0].fn_datos_cierre;
                    var aux2 = JSON.parse(aux);


                    document.getElementById("total_venta").innerHTML = aux2.total_venta;

                    globaslTotalVenta = aux2.total_venta;

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error al obtener datos de ajax');
                }
            });

        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
        }
    });
}

$(".btnsubir").on('click', function() {
    var formData = new FormData();
    var files = $('#files')[0].files[0];
    formData.append('file', files);
    $.ajax({
        url: 'venta/C_cierre_venta/subirImagen',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {

        }
    });

});

function cambioSeleccionado(idubicacion, idLote) {
    aux = idLote;
    aux1 = "";
    var selc_frep = document.getElementById("fecha_inicial").value;
    var selc_finrep = document.getElementById("fecha_fin").value;

    //console.log(aux);
    let indice = false;
    let indice2 = false;
    var array2 = [];
    var arrayAux = [];

    document.getElementById("seleccionar-todos").checked = false;

    for (var i = 0; i < array.length; i++) {
        if (array[i] != aux) {
            array2.push(array[i]);
        } else {
            indice = true;
        }
    }
    if (!indice) {
        array2.push(aux);
    }
    array = array2;
    //console.log(array);
    //----------------------------------------
    for (var j = 0; j < arrayGlobal.length; j++) {
        if (arrayGlobal[j] != aux) {
            arrayAux.push(arrayGlobal[j]);
        } else {
            indice2 = true;
        }
    }
    if (!indice2) {
        arrayAux.push(aux);
    }
    arrayGlobal = arrayAux;
    //console.log(arrayGlobal);
    //-------------------
    for (var j = 0; j < array.length; j++) {
        aux1 = array[j] + "|" + aux1;
    }
    globalLotes = aux1;

    if (globalMarcaTodos) {
        //console.log(arrayGlobal);
        //console.log("a");

        if (arrayGlobal.length === 0) {
            document.querySelector('#total_venta_seleccionado').innerText = 0;
            document.querySelector('#pago_efectivo').innerText = 0;
            document.querySelector('#pago_tarjeta').innerText = 0;
            document.querySelector('#pago_qr').innerText = 0;
            document.querySelector('#pago_giftcard').innerText = 0;
            document.querySelector('#deuda_total').innerText = 0;
            document.querySelector('#deuda_pagado').innerText = 0;
            document.querySelector('#deuda_saldo').innerText = 0;
            document.querySelector('#total_entregar').innerText = 0;
        } else {
            $.ajax({
                url: "<?php echo site_url('venta/C_cierre_venta/cierre_ventas_datos_seleccionado') ?>",
                type: "POST",
                data: {
                    dato1: idubicacion,
                    selc_frep: selc_frep,
                    selc_finrep: selc_finrep,
                    array: arrayGlobal
                },
                success: function(data) {

                    console.log(data);
                    var data = JSON.parse(data);
                    var aux = data[0].fn_datos_cierre_seleccionado;
                    var aux2 = JSON.parse(aux);

                    //console.log(globalMarcaTodos);
                    document.getElementById("total_venta_seleccionado").innerHTML = aux2.total_venta;
                    document.getElementById("pago_efectivo").innerHTML = aux2.total_venta_efectivo;
                    document.getElementById("pago_tarjeta").innerHTML = aux2.total_venta_tarjeta;
                    document.getElementById("pago_qr").innerHTML = aux2.total_venta_qr;
                    document.getElementById("pago_giftcard").innerHTML = aux2.total_venta_GiftCard;

                    document.getElementById("deuda_total").innerHTML = aux2.total_venta_deuda;
                    document.getElementById("deuda_pagado").innerHTML = aux2.total_venta_deuda_cancelado;
                    document.getElementById("deuda_saldo").innerHTML = aux2.total_venta_deuda_saldo;
                    document.getElementById("total_entregar").innerHTML = aux2.total_a_cerrar;
                    globalMontoSeleccionado = aux2.total_a_cerrar;

                }
            })
        }

    } else {
        //console.log(array);
        //console.log("b");
        if (array.length === 0) {
            document.querySelector('#total_venta_seleccionado').innerText = 0;
            document.querySelector('#pago_efectivo').innerText = 0;
            document.querySelector('#pago_tarjeta').innerText = 0;
            document.querySelector('#pago_qr').innerText = 0;
            document.querySelector('#pago_giftcard').innerText = 0;
            document.querySelector('#deuda_total').innerText = 0;
            document.querySelector('#deuda_pagado').innerText = 0;
            document.querySelector('#deuda_saldo').innerText = 0;
            document.querySelector('#total_entregar').innerText = 0;
        } else {
            $.ajax({
                url: "<?php echo site_url('venta/C_cierre_venta/cierre_ventas_datos_seleccionado') ?>",
                type: "POST",
                data: {
                    dato1: idubicacion,
                    selc_frep: selc_frep,
                    selc_finrep: selc_finrep,
                    array: array
                },
                success: function(data) {

                    //console.log(data);
                    var data = JSON.parse(data);
                    var aux = data[0].fn_datos_cierre_seleccionado;
                    var aux2 = JSON.parse(aux);
                    var resta = 0;
                    //resta = globalTodoMontoSeleccionado - aux2.total_venta;

                    //console.log(resta);
                    //console.log(globalMarcaTodos);
                    document.getElementById("total_venta_seleccionado").innerHTML = aux2.total_venta;
                    document.getElementById("pago_efectivo").innerHTML = aux2.total_venta_efectivo;
                    document.getElementById("pago_tarjeta").innerHTML = aux2.total_venta_tarjeta;
                    document.getElementById("pago_qr").innerHTML = aux2.total_venta_qr;
                    document.getElementById("pago_giftcard").innerHTML = aux2.total_venta_GiftCard;

                    document.getElementById("deuda_total").innerHTML = aux2.total_venta_deuda;
                    document.getElementById("deuda_pagado").innerHTML = aux2.total_venta_deuda_cancelado;
                    document.getElementById("deuda_saldo").innerHTML = aux2.total_venta_deuda_saldo;
                    document.getElementById("total_entregar").innerHTML = aux2.total_a_cerrar;
                    globalMontoSeleccionado = aux2.total_a_cerrar;
                }
            })
        }
    }




}
/* GAN-PN-M4-0042 Denilson Santander Rios, 14/10/2022 */
function abrirModalCierre() {
    var marca = document.getElementById('seleccionar-todos');
    var marca1 = document.getElementsByName('checkbox');
    var abc = false;
    for (var x = 0; x < marca1.length; x++) {
        if (marca1[x].checked) {
            abc = true;
        }
    }

    if (abc) {
        $('#modal_cierre').modal('show');
        //console.log(x);
        //console.log(y);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Debe elegir por lo menos un producto!',
        })
    }

}
/* FIN GAN-PN-M4-0042 Denilson Santander Rios, 14/10/2022 */


/*  GAN-CV-M0-0066 Denilson Santander Rios, 24/10/2022 */
function cerrar() {
    $('#modal_cierre').modal('hide');
    descripcion = document.getElementById('descripcion0').value;
    monto = document.getElementById('monto0').value;
    cantidad = document.getElementById('cantidad0').value;
    contador = document.getElementById("contador").value;


    des = Array();
    mon = Array();
    cant = Array();

    des.push(descripcion);
    mon.push(monto);
    cant.push(cantidad);

    for (var i = 1; i <= contador; i++) {
        descripcion = document.getElementById('descripcion' + i);
        monto = document.getElementById('monto' + i);
        cantidad = document.getElementById('cantidad' + i);

        if (descripcion != null && cantidad != null && monto != null) {
            descripcion = descripcion.value;
            monto = monto.value;
            cantidad = cantidad.value;
            des.push(descripcion);
            mon.push(monto);
            cant.push(cantidad);


        }
    }


    var suma = 0;

    for (var i = 0; i < mon.length; i++) {
        suma += Number(mon[i]);
    }
    globalGasto = suma;
    //console.log(globaslTotalVenta);
    //console.log(globalMontoSeleccionado);
    //console.log(suma);


    $.ajax({
        url: "<?php echo site_url('venta/C_cierre_venta/calcular_ingreso') ?>",
        type: "POST",
        dataType: "json",
        data: {
            TotalVenta: globaslTotalVenta,
            MontoSelccionado: globalMontoSeleccionado,
            Suma: suma
        },
        success: function(data) {
            //console.log(data);      
            document.getElementById("montoTotalFechas").innerHTML = globaslTotalVenta;
            document.getElementById("montoTotalSeleccionado").innerHTML = globalMontoSeleccionado;
            document.getElementById("montoGasto").innerHTML = suma;
            document.getElementById("resultadoEntregar").innerHTML = data[0].omontoentregar;
            document.getElementById("resultadoSaldo").innerHTML = data[0].omontosaldo;
            globalEntregar = data[0].omontoentregar;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
        }
    })
}

/* FIN GAN-CV-M0-0066 Denilson Santander Rios, 24/10/2022 */



/* GAN-MS-M0-0036 Denilson Santander Rios, 07/10/2022 */
function cerrar1() {
    $('#modal_afirmacion').modal('hide');
    var selc_frep = document.getElementById("fecha_inicial").value;
    var selc_finrep = document.getElementById("fecha_fin").value;
    var img = document.getElementById('nombre_archivo').innerHTML;
    descripcion = document.getElementById('descripcion0').value;

    monto = document.getElementById('monto0').value;
    cantidad = document.getElementById('cantidad0').value;
    contador = document.getElementById("contador").value;

    des = Array();
    mon = Array();
    cant = Array();

    des.push(descripcion);
    mon.push(monto);
    cant.push(cantidad);

    for (var i = 1; i <= contador; i++) {
        descripcion = document.getElementById('descripcion' + i);
        monto = document.getElementById('monto' + i);
        cantidad = document.getElementById('cantidad' + i);
        if (descripcion != null && cantidad != null && monto != null) {
            descripcion = descripcion.value;
            monto = monto.value;
            cantidad = cantidad.value;
            des.push(descripcion);
            mon.push(monto);
            cant.push(cantidad);
        }
    }

    var formData = new FormData();
    var files = $('#files')[0].files[0];
    formData.append('file', files);

   


    $.ajax({
        url: "<?php echo site_url('venta/C_cierre_venta/cerrar_caja')?>",
        type: "POST",
        data: {
            montoTotal: globaslTotalVenta,
            montoGasto: globalGasto,
            montoEntregar: globalEntregar,
            imagen: img,
            lotes: globalLotes,
            selc_frep: selc_frep,
            selc_finrep: selc_finrep

        },
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'Se registro con exito',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'ACEPTAR',
            })

            location.href = "<?php echo base_url(); ?>cierre_ventas";
        }
    })


    $.ajax({
        url: "<?php echo site_url('venta/C_cierre_venta/add_gastos_cierre') ?>",
        type: "POST",
        dataType: "json",
        data: {
            'des': JSON.stringify(des),
            'mon': JSON.stringify(mon),
            'cant': JSON.stringify(cant)
        },
        success: function() {


        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error al obtener datos de ajax');
        }
    })
}
/* FIN GAN-MS-M0-0036 Denilson Santander Rios, 07/10/2022 */
var array = [];
</script>


<script>
let archivo = document.querySelector('#files');
archivo.addEventListener('change', () => {
    document.querySelector('#nombre_archivo').innerText = archivo.files[0].name;
});
</script>