<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="table" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Nombre</th>
                            <th>Activo</th>
                            <th>Imagen</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody id="tablecontents">
                        @foreach($bloques as $dato)
                            <tr class="row1" data-id="{{ $dato->id }}">

                                <td>{{ $dato->posicion }}</td>
                                <td>{{ $dato->nombre }}</td>

                                <td>
                                    @if($dato->activo == 0)
                                        <span class="badge bg-danger">Desactivado</span>
                                    @else
                                        <span class="badge bg-success">Activado</span>
                                    @endif
                                </td>

                                <td>
                                    <center><img alt="Imagenes" src="{{ url('storage/imagenes/'.$dato->imagen) }}" width="150px" height="150px" /></center>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="informacion({{ $dato->id }})">
                                        <i class="fas fa-eye" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    @if($dato->tiposervicio_id == 1)
                                        <button type="button" class="btn btn-warning btn-xs" onclick="verEventos()">
                                            <i class="fas fa-eye" title="Eventos"></i>&nbsp; Eventos
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-xs" onclick="verCategorias()">
                                            <i class="fas fa-eye" title="Categorías"></i>&nbsp; Categorías
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {

        $( "#tablecontents" ).sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });

        function sendOrderToServer() {

            var order = [];
            $('tr.row1').each(function(index,element) {
                order.push({
                    id: $(this).attr('data-id'),
                    posicion: index+1
                });
            });

            openLoading();

            axios.post('/admin/bloques/ordenar',  {
                'order': order
            })
                .then((response) => {
                    closeLoading();
                    toastr.success('Actualizado correctamente');
                    recargar();
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al actualizar');
                });
        }
    });

</script>
