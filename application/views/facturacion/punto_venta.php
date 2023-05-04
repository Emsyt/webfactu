<?php

?>

<script>
	$(document).ready(function() {
		activarMenu('menu17', 1);
	});
</script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- BEGIN CONTENT-->
<div id="content">
	<section>
		<div class="section-header">
			<ol class="breadcrumb">
				<li><a href="#">Facturacion</a></li>
				<li class="active">Puntos de Venta</li>
			</ol>
		</div>

		<?php if ($this->session->flashdata('success')) { ?>
			<script>
				window.onload = function mensaje() {
					swal.fire(" ", "<?php echo $this->session->flashdata('success'); ?>", "success");
				}
			</script>
		<?php } else if ($this->session->flashdata('error')) { ?>
			<script>
				window.onload = function mensaje() {
					swal.fire(" ", "<?php echo $this->session->flashdata('error'); ?>", "error");
				}
			</script>
		<?php } ?>

		<div class="section-body">
			<div class="row">
				<div class="col-lg-12">
					<h3 class="text-primary">Listado de Puntos de Venta
						<button type="button" class="btn btn-primary ink-reaction btn-sm pull-right" style="margin-left: 20px;" onclick="consultar_punto_venta()">Consultar Puntos de Ventas</button>
						<button type="button" class="btn btn-primary ink-reaction btn-sm pull-right" style="margin-left: 20px;" onclick="formulario2()"><span class="pull-left"><i class="fa fa-plus"></i></span> &nbsp; Registrar Ubicacion Existente</button>
						<button type="button" class="btn btn-primary ink-reaction btn-sm pull-right" onclick="formulario()"><span class="pull-left"><i class="fa fa-plus"></i></span> &nbsp;Nuevo Punto de Venta</button>
					</h3>
					<hr>
				</div>
			</div>

			<div class="row" style="display: none;" id="form_registro">
				<div class="col-sm-8 col-md-9 col-lg-10 col-lg-offset-1">
					<div class="text-divider visible-xs"><span>Formulario de Registro</span></div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form class="form form-validate" novalidate="novalidate" name="form_punto_venta" id="form_punto_venta" enctype="multipart/form-data" method="post" action="<?= site_url() ?>facturacion/C_punto_venta/add_update_punto_venta">
								<div class="card">
									<div class="card-head style-primary">
										<div class="tools">
											<div class="btn-group">
												<a id="btn_update2" class="btn btn-icon-toggle" onclick="update_formulario()"><i class="md md-refresh"></i></a>
												<a class="btn btn-icon-toggle" onclick="cerrar_formulario()"><i class="md md-close"></i></a>
											</div>
										</div>
										<header id="titulo"></header>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<div class="row">
													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="form-group floating-label" id="c_descripcion">
															<input type="text" class="form-control" name="descripcion" id="descripcion" required>
															<div id="result-error"></div>
															<label for="descripcion">Descripción</label>
														</div>
													</div>

													<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
														<div class="form-group floating-label" id="c_nombre">
															<input type="text" class="form-control" name="nombre" id="nombre" required>
															<label for="nombre">Nombre</label>
														</div>
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_tipo_venta">
													<select class="form-control select2-list" id="tipo_venta" name="tipo_venta" required>
														<option value=""></option>
														<?php foreach ($lst_tipo_venta as $tipo_venta) {  ?>
															<option value="<?php echo $tipo_venta->id_tipo_venta ?>" <?php echo set_select('tipo_venta', $tipo_venta->id_tipo_venta) ?>>
																<?php echo $tipo_venta->descripcion ?></option>
														<?php  } ?>
													</select>
													<label for="tipo_venta">Seleccione Tipo de Punto de Venta</label>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_sucursal">
													<select class="form-control select2-list" id="sucursal" name="sucursal" required>
														<option value=""></option>
														<?php foreach ($lst_sucursales as $sucursal) {
															$descripcion = json_decode($sucursal->descripcion);
															$descripcion = $descripcion->Nombre; ?>
															<option value="<?php echo $sucursal->codigo ?>" <?php echo set_select('sucursal', $sucursal->codigo) ?>>
																<?php echo $descripcion ?></option>
														<?php  } ?>
													</select>
													<label for="sucursal">Seleccione Sucursal</label>
												</div>
											</div>
										</div>
									</div>
									<div class="card-actionbar">
										<div class="card-actionbar-row">
											<button type="submit" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_add" value="add">Registrar Punto de Venta</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="display: none;" id="form_registro2">
				<div class="col-sm-8 col-md-9 col-lg-10 col-lg-offset-1">
					<div class="text-divider visible-xs"><span>Formulario de Registro</span></div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form class="form form-validate" novalidate="novalidate" name="form_punto_venta2" id="form_punto_venta2" enctype="multipart/form-data" method="post" action="<?= site_url() ?>facturacion/C_punto_venta/registrar_punto_venta">
								<div class="card">
									<div class="card-head style-primary">
										<div class="tools">
											<div class="btn-group">
												<a id="btn_update" class="btn btn-icon-toggle" onclick="update_formulario()"><i class="md md-refresh"></i></a>
												<a class="btn btn-icon-toggle" onclick="cerrar_formulario()"><i class="md md-close"></i></a>
											</div>
										</div>
										<header id="titulo2"></header>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_ubicacion">
													<select class="form-control select2-list" id="ubicacion" name="ubicacion" required>
														<option value=""></option>
														<?php foreach ($lst_ubicaciones as $ubi) {  ?>
															<option value="<?php echo $ubi->oidubicacion ?>" <?php echo set_select('ubi', $ubi->oidubicacion) ?>>
																<?php echo $ubi->oubicacion ?></option>
														<?php  } ?>
													</select>
													<label for="ubi">Seleccione Ubicacion</label>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_punto_venta">
													<select class="form-control select2-list" id="punto_venta" name="punto_venta" required>
														<option value=""></option>
														<?php foreach ($lst_puntos_venta as $punto_venta) {  ?>
															<option value="<?php echo $punto_venta->id_venta ?>" <?php echo set_select('punto_venta', $punto_venta->id_venta) ?>>
																<?php echo $punto_venta->nombre ?></option>
														<?php  } ?>
													</select>
													<label for="punto_venta">Seleccione Punto de Venta</label>
												</div>
											</div>
										</div>
									</div>

									<div class="card-actionbar">
										<div class="card-actionbar-row">
											<button type="submit" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_add2" value="exist">Registrar Punto de Venta
												Existente</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="display: none;" id="form_registro3">
				<div class="col-sm-8 col-md-9 col-lg-10 col-lg-offset-1">
					<div class="text-divider visible-xs"><span>Formulario de Registro</span></div>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form class="form form-validate" novalidate="novalidate" name="form_punto_venta3" id="form_punto_venta3" enctype="multipart/form-data" method="post" action="<?= site_url() ?>facturacion/C_punto_venta/registrar_punto_venta">
								<div class="card">
									<div class="card-head style-primary">
										<div class="tools">
											<div class="btn-group">
												<a id="btn_update3" class="btn btn-icon-toggle" onclick="update_formulario()"><i class="md md-refresh"></i></a>
												<a class="btn btn-icon-toggle" onclick="cerrar_formulario()"><i class="md md-close"></i></a>
											</div>
										</div>
										<header id="titulo3"></header>
									</div>

									<div class="card-body">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_ubicacion3">
													<select class="form-control select2-list" id="ubicacion3" name="ubicacion3" required>
														<option value=""></option>
														<?php foreach ($lst_ubicaciones as $ubi) {  ?>
															<option value="<?php echo $ubi->oidubicacion ?>" <?php echo set_select('ubi', $ubi->oidubicacion) ?>>
																<?php echo $ubi->oubicacion ?></option>
														<?php  } ?>
													</select>
													<label for="ubi">Seleccione Ubicacion</label>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
												<div class="form-group floating-label" id="c_punto_venta3">
													<!-- <input type="hidden" class="form-control" name="punto_venta3" id="punto_venta3"> -->
													<select class="form-control select2-list" id="punto_venta3" name="punto_venta3" required>
														<option value=""></option>
														<?php foreach ($lst_puntos_venta as $punto_venta) {  ?>
															<option value="<?php echo $punto_venta->cod_punto_venta ?>" <?php echo set_select('punto_venta', $punto_venta->cod_punto_venta) ?>>
																<?php echo $punto_venta->nombre ?></option>
														<?php  } ?>
													</select>
													<label for="punto_venta3">Seleccione Punto de Venta</label>
												</div>
											</div>
										</div>
									</div>
									<div class="card-actionbar">
										<div class="card-actionbar-row">
											<button type="submit" class="btn btn-flat btn-primary ink-reaction" name="btn" id="btn_add3" value="exist">Registrar Punto de Venta
												Existente</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="tabla_punto_venta" style="display: block;">
				<div class="col-md-12">
					<div class="text-divider visible-xs"><span>Listado de Registros</span></div>
					<div class="card card-bordered style-primary">
						<div class="card-body style-default-bright">
							<div class="table-responsive">
								<table id="datatable_punto_venta" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>Nombre</th>
											<th>Descripcion</th>
											<th>Tipo de Venta</th>
											<th>Codigo Cuis</th>
											<th>Estado</th>
											<th>Acci&oacute;n</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="tabla_punto_venta2" style="display: none;">
				<div class="col-md-12">
					<div class="text-divider visible-xs"><span>Listado de Registros</span></div>
					<div class="card card-bordered style-primary">
						<div class="card-body style-default-bright">
							<div class="table-responsive">
								<table id="datatable_punto_venta_ubicacion" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>Descripcion</th>
											<th>Estado</th>
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
</div>
<!-- END BASE -->
<div class="modal fade" name="puntoVenta" id="puntoVenta" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="form" role="form" name="form_editar" id="form_editar" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="formModalLabel">Lista de Puntos de Venta</h4>
				</div>

				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="table-responsive" style="margin:10px; overflow-y: scroll;">
								<table id="dt_puntoventa" class="table table-striped table-bordered">
									<thead>
										<tr>
											<!-- <th width="5%">N&deg;</th> -->
											<th width="10%">COD. PUNTO VENTA</th>
											<th width="40%">NOMBRE</th>
											<th width="45%">TIPO PUNTO VENTA</th>
											<th width="5%">ACCION</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
						<div><br> </div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$.ajax({
			url: '<?= base_url() ?>facturacion/C_punto_venta/listar_punto_venta_ubicaciones/',
			type: "post",
			datatype: "json",
			success: function(data) {
				var data = JSON.parse(data);
				var t = $('#datatable_punto_venta_ubicacion').DataTable({
					"data": data,
					"responsive": true,
					"language": {
						"url": "<?= base_url() ?>assets/plugins/datatables_es/es-ar.json"
					},
					"destroy": true,
					"order": [
						[2, 'asc']
					],
					"aoColumns": [{
							"mData": "descripcion"
						},
						{
							"mData": "estado"
						},
						{
							"mRender": function(data, type, row, meta) {
								var a = `
                            <button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-danger" onclick=" eliminar_ubicacion_punto_venta(${row.id_ubicacion})" title ="Eliminar" ><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-info" onclick=" editar_ubicacion_punto_venta(${row.id_ubicacion})" title ="Modificar"><i class="fa fa-pencil-square-o fa-lg"></i></button> `;
								return a;
								// var a =
								//     `
								//         <button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-danger" onclick=" eliminar_ubicacion_punto_venta(${row.id_ubicacion})" title ="Eliminar" ><i class="fa fa-trash-o"></i></button>`;
								// return a;
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

		$.ajax({
			url: '<?= base_url() ?>facturacion/C_punto_venta/lst_punto_venta',
			type: "post",
			datatype: "json",

			success: function(data) {
				var data = JSON.parse(data);
				console.log(data);
				var t = $('#datatable_punto_venta').DataTable({
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
						[1, 'asc']
					],
					"aoColumns": [{
							"mData": "nombre"
						},
						{
							"mData": "descripcion"
						},
						{
							"mData": "tipo_venta"
						},
						{
							"mRender": function(data, type, row, meta) {
								var cuis = '';
								if (row.cuis != null) {
									if (new Date(row.fecven) > new Date().getTime()) {
										cuis = row.cuis;
									} else {
										cuis = 'Vencido'
									}
								} else {
									cuis = 'Sin Asignar'
								}
								var valor = '<font>' + cuis + '</font>';
								return valor;
							}
						},
						{
							"mData": "apiestado"
						},
						{
							"mRender": function(data, type, row, meta) {
								var cuis = '';
								if (row.cuis != null) {
									if (new Date(row.fecven) > new Date().getTime()) {
										cuis = '';
									} else {
										cuis = `<button title="Solicitar Cuis" type="button" class="btn ink-reaction btn-floating-action btn-xs btn-info" onclick="generar_cuis(${row.id_facturacion},${row.cod_punto_venta})"><i class="fa fa-pencil-square-o fa-lg"></i></button>`;
									}
								} else {
									cuis = `<button title="Solicitar Cuis" type="button" class="btn ink-reaction btn-floating-action btn-xs btn-info" onclick="generar_cuis(${row.id_facturacion},${row.cod_punto_venta})"><i class="fa fa-pencil-square-o fa-lg"></i></button>`;
								}
								var valor = cuis + ' ' + `<button type="button" class="btn ink-reaction btn-floating-action btn-xs btn-danger" onclick="eliminar_punto_venta(${row.id_facturacion},${row.cod_punto_venta},\'${row.nombre}\')" title ="Eliminar Punto Venta" ><i class="fa fa-trash-o"></i></button>`;
								return valor;
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
	});

	function consultar_punto_venta() {
		$.ajax({
			url: '<?= base_url() ?>facturacion/C_punto_venta/C_consultar_punto_venta',
			type: "post",
			datatype: "json",
			success: function(data) {
				console.log(data)
				var c = JSON.parse(data);
				if (c[0].oboolean == 't') {
					let list_respons = c[0].omensaje;
					let listaPuntosVentas = list_respons['RespuestaConsultaPuntoVenta'].listaPuntosVentas;

					$('#dt_puntoventa').DataTable({
						"data": listaPuntosVentas,
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
							[0, 'asc']
						],
						"aoColumns": [{
								"mData": "codigoPuntoVenta"
							},
							{
								"mData": "nombrePuntoVenta"
							},
							{
								"mData": "tipoPuntoVenta"
							},
							{
								"mRender": function(data, type, row, meta) {
									var valor = `<button type="button" class="btn ink-reaction btn-sm btn-primary" onclick=" adicionar_punto_venta_existente(${row.codigoPuntoVenta},\'${row.nombrePuntoVenta}\',\'${row.tipoPuntoVenta}\')" title ="Modificar">AÑADIR A LA BD</button>`;
									return valor;
								}
							},


						],
						"dom": 'C<"clear">lfrtip',
						"colVis": {
							"buttonText": "Columnas"
						}
					});
					$('#puntoVenta').modal('show');

				} else {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: c[0].omensaje,
						confirmButtonColor: '#d33',
						confirmButtonText: 'ACEPTAR'
					})
				}
				//var data = JSON.parse(data);
				// console.log((data['RespuestaConsultaPuntoVenta'].listaPuntosVentas));
				// data = data['RespuestaConsultaPuntoVenta'].listaPuntosVentas;


			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error al obtener datos de ajax');
			}
		});
	};

	function adicionar_punto_venta_existente(codigoPuntoVenta, nombrePuntoVenta, tipoPuntoVenta) {
		console.log(codigoPuntoVenta, nombrePuntoVenta, tipoPuntoVenta)
		$.ajax({
			url: '<?= base_url() ?>facturacion/C_punto_venta/C_gestionar_punto_venta_existente',
			type: "post",
			datatype: "json",
			data: {
				codigoPuntoVenta: codigoPuntoVenta,
				nombrePuntoVenta: nombrePuntoVenta,
				tipoPuntoVenta: tipoPuntoVenta,
			},
			success: function(data) {
				var c = JSON.parse(data);
				if (c[0].oboolean == 't') {
					Swal.fire({
						icon: 'success',
						title: 'El agregado del punto de venta se realizó con éxito',
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

	function editar_ubicacion_punto_venta(id_ubicacion) {
		$("#titulo3").text("Editar Punto de Venta Existente");
		document.getElementById("form_registro").style.display = "none";
		document.getElementById("form_registro2").style.display = "none";
		document.getElementById("form_registro3").style.display = "block";
		$.ajax({
			url: '<?= base_url() ?>facturacion/C_punto_venta/get_nom_ubicacion',
			type: "post",
			datatype: "json",
			data: {
				id_ubicacion: id_ubicacion
			},
			success: function(data) {
				var data = JSON.parse(data);
				$.ajax({
					url: '<?= base_url() ?>facturacion/C_punto_venta/get_ubicacion',
					type: "post",
					datatype: "json",
					data: {
						id_ubicacion: id_ubicacion
					},
					success: function(dato) {
						var dato = JSON.parse(dato);
						// $('[name="punto_ventas3"]').val(dato[0].codigo_punto_venta).trigger('change');
						if (dato[0].codigo_punto_venta != null) {
							const myOpts = document.getElementById('ubicacion3').options;
							var bool = true;
							for (var i = 0; i < myOpts.length; i++) {
								if (myOpts[i].value == dato[0].id_ubicacion) {
									bool = false;
								}
							}
							if (bool) {
								select = document.getElementById('ubicacion3');
								var opt = document.createElement('option');
								opt.value = data[0].id_ubicacion;
								opt.innerHTML = data[0].descripcion;
								select.appendChild(opt);
							}
							$('[name="punto_venta3"]').val(dato[0].codigo_punto_venta).trigger('change');
							$('[name="ubicacion3"]').val(dato[0].id_ubicacion).trigger('change');
						} else {
							Swal.fire({
								icon: 'warning',
								title: 'Esta ubicación no cuenta con un punto de venta',
								showCancelButton: false,
								denyButtonText: `Aceptar`,
							})
						}
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

	function generar_cuis(id_facturacion, id_punto_venta) {
		$.ajax({
			url: "<?= site_url() ?>facturacion/C_punto_venta/generar_cuis",
			type: "POST",
			data: {
				id_facturacion: id_facturacion,
				id_punto_venta: id_punto_venta,
			},
			success: function(resp) {
				var c = JSON.parse(resp);
				console.log(c);
				if (c.RespuestaCuis.transaccion) {
					let codigo = c.RespuestaCuis.codigo;
					let fechaVigencia = c.RespuestaCuis.fechaVigencia;
					console.log(codigo, fechaVigencia)
					$.ajax({
						url: "<?= site_url() ?>facturacion/C_punto_venta/registrar_cuis",
						type: "POST",
						data: {
							id_punto_venta: id_punto_venta,
							id_facturacion: id_facturacion,
							codigo: codigo,
							fechaVigencia: fechaVigencia
						},
						success: function(resp) {
							var c = JSON.parse(resp);
							console.log(c);
							if (c[0].oboolean == 't') {
								Swal.fire({
									icon: 'success',
									text: 'SE AGREGO CUIS EXITOSAMENTE',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'ACEPTAR'
								})
								location.reload();
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
							alert('Error al intentar registrar el cuis al sistema');
						}
					});
				} else {
					let codigo = c.RespuestaCuis.codigo;
					let fechaVigencia = c.RespuestaCuis.fechaVigencia;
					console.log(id_punto_venta, id_facturacion, codigo, fechaVigencia)
					$.ajax({
						url: "<?= site_url() ?>facturacion/C_punto_venta/registrar_cuis",
						type: "POST",
						data: {
							id_punto_venta: id_punto_venta,
							id_facturacion: id_facturacion,
							codigo: codigo,
							fechaVigencia: fechaVigencia
						},
						success: function(resp) {
							var c = JSON.parse(resp);
							console.log(c);
							if (c[0].oboolean == 't') {
								Swal.fire({
									icon: 'success',
									text: 'SE AGREGO CUIS EXITOSAMENTE',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'ACEPTAR'
								})
								location.reload();
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
							alert('Error al intentar registrar el cuis al sistema');
						}
					});
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error al obtener el cuis de SIAT');
			}
		});
	}

	function eliminar_punto_venta(id_facturacion, cod_punto_venta, nombre) {
		Swal.fire({
			icon: 'warning',
			title: '¿Seguro que desea eliminar el punto de venta?',
			text: 'Al Elimiar el punto este ya no podra recuperarse',
			showDenyButton: true,
			confirmButtonText: 'Eliminar',
			denyButtonText: `Cancelar`,
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "<?= site_url() ?>facturacion/C_punto_venta/cierrePuntoVenta",
					type: "POST",
					data: {
						cod_punto_venta: cod_punto_venta,
					},
					success: function(resp) {
						var c = JSON.parse(resp);
						console.log(c);
						if (c.RespuestaCierrePuntoVenta.transaccion) {
							// let codigo = c.RespuestaCuis.codigo;
							// let fechaVigencia = c.RespuestaCuis.fechaVigencia;
							$.ajax({
								url: "<?= site_url() ?>facturacion/C_punto_venta/registrarCierrePuntoVenta",
								type: "POST",
								data: {
									id_facturacion: id_facturacion,
									cod_punto_venta: cod_punto_venta,
								},
								success: function(resp) {
									var c = JSON.parse(resp);
									console.log(resp);
									if (c[0].fn_eliminar_punto_venta == 't') {
										Swal.fire({
											icon: 'success',
											text: 'ELIMINADO EXITOSAMENTE',
											confirmButtonColor: '#3085d6',
											confirmButtonText: 'ACEPTAR'
										})
										location.reload();
									} else {
										Swal.fire({
											icon: 'error',
											title: 'Oops...',
											text: 'En este momento no se tiene conexión con Impuestos Nacionales, comuniquese con el administrador de sistema o intente mas tarde',
											confirmButtonColor: '#d33',
											confirmButtonText: 'ACEPTAR'
										})
									}
								},
								error: function(jqXHR, textStatus, errorThrown) {
									alert('Error al intentar registrar el cuis al sistema');
								}
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'En este momento no se tiene conexión con Impuestos Nacionales, comuniquese con el administrador de sistema o intente mas tarde',
								confirmButtonColor: '#d33',
								confirmButtonText: 'ACEPTAR'
							})
						}


						// if (c[0].fn_eliminar_punto_venta == 't') {
						// 	Swal.fire({
						// 		icon: 'success',
						// 		text: 'ELIMINADO EXITOSAMENTE',
						// 		confirmButtonColor: '#3085d6',
						// 		confirmButtonText: 'ACEPTAR'
						// 	})
						// 	location.reload();
						// } else {
						// 	Swal.fire({
						// 		icon: 'error',
						// 		title: 'Oops...',
						// 		text: 'En este momento no se tiene conexión con Impuestos Nacionales, comuniquese con el administrador de sistema o intente mas tarde',
						// 		confirmButtonColor: '#d33',
						// 		confirmButtonText: 'ACEPTAR'
						// 	})
						// }
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error al obtener datos de ajax');
					}
				});
			}
		})
	}

	function eliminar_ubicacion_punto_venta(id_ubicacion) {
		Swal.fire({
			icon: 'warning',
			title: '¿Seguro que desea eliminar el punto de venta?',
			showDenyButton: true,
			confirmButtonText: 'Eliminar',
			denyButtonText: `Cancelar`,
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: "<?= site_url() ?>facturacion/C_punto_venta/eliminar_ubicacion_punto_venta",
					type: "POST",
					data: {
						id_ubicacion: id_ubicacion,
					},
					success: function(resp) {
						var c = JSON.parse(resp);
						if (c[0].fn_eliminar_ubicacion_punto_venta == 't') {
							Swal.fire({
								icon: 'success',
								text: 'ELIMINADO EXITOSAMENTE',
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'ACEPTAR'
							})
							location.reload();
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
		})
	}

	function formulario2() {
		document.getElementById("tabla_punto_venta").style.display = "none";
		document.getElementById("form_registro3").style.display = "none";
		document.getElementById("tabla_punto_venta2").style.display = "block";
		document.getElementById("form_registro").style.display = "none";
		$("#titulo2").text("Registrar Punto de Venta Existente");
		$('#form_punto_venta2')[0].reset();

		document.getElementById("form_registro2").style.display = "block";
		// document.getElementById("btn_update").style.display = "block";
	}

	function formulario() {
		document.getElementById("tabla_punto_venta2").style.display = "none";
		document.getElementById("form_registro3").style.display = "none";
		document.getElementById("tabla_punto_venta").style.display = "block";
		document.getElementById("form_registro2").style.display = "none";
		$("#titulo").text("Registrar Punto de Venta");
		$('#form_punto_venta')[0].reset();

		document.getElementById("form_registro").style.display = "block";
		// document.getElementById("btn_update").style.display = "block";
	}

	function cerrar_formulario() {
		document.getElementById("form_registro").style.display = "none";
		$('#form_punto_venta')[0].reset();
		document.getElementById("form_registro2").style.display = "none";
		$('#form_punto_venta2')[0].reset();
		document.getElementById("form_registro3").style.display = "none";
		$('#form_punto_venta3')[0].reset();
		$('[name="punto_venta"]').val(null).trigger('change');
		$('[name="ubicacion"]').val(null).trigger('change');
		// $('[name="punto_ventas3"]').val(null).trigger('change');
		$('[name="ubicacion3"]').val(null).trigger('change');
		$('[name="tipo_venta"]').val(null).trigger('change');
		$('[name="sucursal"]').val(null).trigger('change');
	}

	function update_formulario() {
		$('#form_punto_venta')[0].reset();
		$('#form_punto_venta2')[0].reset();
		$('#form_punto_venta3')[0].reset();
		$('[name="punto_venta"]').val(null).trigger('change');
		$('[name="ubicacion"]').val(null).trigger('change');
		// $('[name="punto_ventas3"]').val(null).trigger('change');
		$('[name="ubicacion3"]').val(null).trigger('change');
		$('[name="tipo_venta"]').val(null).trigger('change');
		$('[name="sucursal"]').val(null).trigger('change');
	}
</script>