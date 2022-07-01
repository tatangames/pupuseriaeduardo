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
                            <th>Descripción</th>
                            <th>Producto</th>
                            <th>Imagen</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody id="tablecontents">
                        @foreach($slider as $dato)
                            <tr class="row1" data-id="{{ $dato->id }}">

                                <td>{{ $dato->posicion }}</td>
                                <td>{{ $dato->descripcion }}</td>
                                <td>{{ $dato->producto }}</td>
                                <td>
                                    <center><img alt="Imagenes" src="{{ url('storage/imagenes/'.$dato->imagen) }}" width="150px" height="150px" /></center>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary btn-xs" onclick="informacion({{ $dato->id }})">
                                        <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    <button type="button" class="btn btn-danger btn-xs" onclick="informacionBorrar({{ $dato->id }})">
                                        <i class="fas fa-trash-alt" title="Borrar"></i>&nbsp; Borrar
                                    </button>

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

            axios.post('/admin/sliders/ordenar',  {
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
