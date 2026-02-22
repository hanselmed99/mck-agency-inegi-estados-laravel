@extends('layouts.app')

@section('title', 'Estados de México')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🗺️ Estados de México</h5>
        <span class="badge bg-secondary">32 entidades federativas</span>
    </div>
    <div class="card-body">
        <table id="tablaEstados" class="table table-hover table-bordered w-100">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Clave Geo</th>
                    <th>Nombre</th>
                    <th>Abreviación</th>
                    <th>Población Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal de Bootstrap -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalEstadoLabel">Detalle del Estado</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modal-spinner" class="text-center py-3">
                    <div class="spinner-border text-secondary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
                <div id="modal-contenido" class="d-none">
                    <table class="table table-sm table-striped">
                        <tbody>
                            <tr><th scope="row">Clave Geoestadística</th><td id="m-cvegeo"></td></tr>
                            <tr><th scope="row">Clave Entidad</th><td id="m-cve_ent"></td></tr>
                            <tr><th scope="row">Nombre</th><td id="m-nomgeo"></td></tr>
                            <tr><th scope="row">Nombre Abreviado</th><td id="m-nom_abrev"></td></tr>
                            <tr><th scope="row">Población Total</th><td id="m-pob_total"></td></tr>
                        </tbody>
                    </table>
                </div>
                <div id="modal-error" class="alert alert-danger d-none">
                    No se pudo cargar la información del estado.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ─── Inicializar DataTables con AJAX ────────────────────────────────────
    const tabla = $('#tablaEstados').DataTable({
        ajax: {
            url: '/api/estados',
            dataSrc: 'data',
            error: function () {
                alert('Error al cargar los estados. Intenta de nuevo.');
            }
        },
        columns: [
            { data: 'id' },
            { data: 'cvegeo' },
            { data: 'nomgeo' },
            { data: 'nom_abrev', defaultContent: '—' },
            { data: 'pob_total', defaultContent: '—' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (id) {
                    return `<button class="btn btn-sm btn-outline-primary btn-detalle" data-id="${id}">
                                Ver detalle
                            </button>`;
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-MX.json'
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });

    // ─── Abrir Modal con detalle del estado vía AJAX ────────────────────────
    $('#tablaEstados').on('click', '.btn-detalle', function () {
        const estadoId = $(this).data('id');
        const modal    = new bootstrap.Modal(document.getElementById('modalEstado'));

        // Resetear estado del modal
        $('#modal-spinner').removeClass('d-none');
        $('#modal-contenido').addClass('d-none');
        $('#modal-error').addClass('d-none');

        modal.show();

        // Consumir endpoint REST interno
        $.ajax({
            url: `/api/estados/${estadoId}`,
            method: 'GET',
            success: function (data) {
                $('#m-cvegeo').text(data.cvegeo   ?? '—');
                $('#m-cve_ent').text(data.cve_ent ?? '—');
                $('#m-nomgeo').text(data.nomgeo   ?? '—');
                $('#m-nom_abrev').text(data.nom_abrev ?? '—');
                $('#m-pob_total').text(
                    data.pob_total
                        ? Number(data.pob_total).toLocaleString('es-MX')
                        : '—'
                );
                $('#modal-spinner').addClass('d-none');
                $('#modal-contenido').removeClass('d-none');
            },
            error: function () {
                $('#modal-spinner').addClass('d-none');
                $('#modal-error').removeClass('d-none');
            }
        });
    });

});
</script>
@endpush
