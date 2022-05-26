@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />

@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }

    #card-header-color {
        background-color: #673AB7 !important;
    }
</style>

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <h1>Records</h1>

            <button type="button" style="margin-left: 30px" onclick="modalNuevo()" class="btn btn-info btn-sm">
                <i class="fas fa-pencil-alt"></i>
                Nuevo Record
            </button>
        </div>

    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" id="card-header-color">
                <h3 class="card-title" style="color: white">Lista de Records</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="tablaDatatable">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- modal agregar -->
<div class="modal fade" id="modalAgregar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Record</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Fecha</label>
                                    <input type="date" class="form-control" id="fecha-nuevo" placeholder="Fecha">
                                </div>

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" maxlength="100" class="form-control" id="nombre-nuevo" placeholder="Nombre">
                                </div>

                                <div class="form-group">
                                    <label>Descripción</label>
                                    <input type="text" maxlength="500" class="form-control" id="descripcion-nuevo" placeholder="Descripción">
                                </div>

                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" class="form-control" id="cantidad-nuevo" placeholder="Cantidad">
                                </div>

                                <div class="form-group">
                                    <div>
                                        <label>Imagen</label>
                                    </div>
                                    <br>
                                    <div class="col-md-10">
                                        <input type="file" style="color:#191818" id="imagen-nuevo" accept="image/jpeg, image/jpg, image/png"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="nuevo()">Guardar</button>
            </div>
        </div>
    </div>
</div>


<!-- modal editar-->
<div class="modal fade" id="modalEditar" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Record</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="fecha-editar" placeholder="Fecha">
                            </div>

                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="hidden" id="id-editar">
                                <input type="text" maxlength="100" class="form-control" id="nombre-editar" placeholder="Nombre">
                            </div>

                            <div class="form-group">
                                <label>Descripción</label>
                                <input type="text" maxlength="500" class="form-control" id="descripcion-editar" placeholder="Descripción">
                            </div>

                            <div class="form-group">
                                <label>Cantidad</label>
                                <input type="number" class="form-control" id="cantidad-editar" placeholder="Cantidad">
                            </div>

                            <div class="form-group">
                                <div>
                                    <label>Imagen</label>
                                </div>
                                <br>
                                <div class="col-md-10">
                                    <input type="file" style="color:#191818" id="imagen-editar" accept="image/jpeg, image/jpg, image/png"/>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editar()">Guardar</button>
            </div>
        </div>
    </div>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery-ui-drag.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-drag.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ URL::to('/admin/records/tablas') }}";
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ url('/admin/records/tablas') }}";
            $('#tablaDatatable').load(ruta);
        }

        // abrir modal
        function modalNuevo(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        //nuevo servicio
        function nuevo(){

            var fecha = document.getElementById('fecha-nuevo').value;
            var nombre = document.getElementById('nombre-nuevo').value;
            var descripcion = document.getElementById('descripcion-nuevo').value;
            var cantidad = document.getElementById('cantidad-nuevo').value;
            var imagen = document.getElementById('imagen-nuevo');

            if(fecha === '') {
                toastr.error('Fecha es requerido');
                return;
            }

            if(nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            if(nombre.length > 100){
                toastr.error('Nombre máximo 100 caracteres');
                return;
            }

            if(descripcion.length > 0){
                if(descripcion.length > 500){
                    toastr.error('Descripción máximo 500 caracteres');
                    return;
                }
            }

            var reglaNumeroEntero = /^[0-9]\d*$/;

            if(!cantidad.match(reglaNumeroEntero)) {
                toastr.error('Cantidad es Requerido');
                return;
            }

            if(cantidad <= 0){
                toastr.error('Cantidad no debe ser negativo o Cero');
                return;
            }

            if(cantidad.length > 10){
                toastr.error('Cantidad máximo 10 caracteres');
                return;
            }

            if(imagen.files && imagen.files[0]){ // si trae imagen
                if (!imagen.files[0].type.match('image/jpeg|image/jpeg|image/png')){
                    toastr.error('Formato de imagen permitido: .png .jpg .jpeg');
                    return;
                }
            }else{
                toastr.error('Imagen es requerido');
                return;
            }

            openLoading();

            var formData = new FormData();
            formData.append('fecha', fecha);
            formData.append('nombre', nombre);
            formData.append('descripcion', descripcion);
            formData.append('cantidad', cantidad);
            formData.append('imagen', imagen.files[0]);

            axios.post('/admin/records/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalAgregar').modal('hide');
                        toastr.success('Registrado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al guardar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al guardar');
                });
        }




        function modalBorrar(id){
            Swal.fire({
                title: 'Borrar Record?',
                text: "",
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarEvento(id);
                }
            })
        }

        function borrarEvento(id){
            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post('/admin/records/borrar', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Eliminado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al Borrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al Borrar');
                    closeLoading();
                });
        }

    </script>


@endsection
