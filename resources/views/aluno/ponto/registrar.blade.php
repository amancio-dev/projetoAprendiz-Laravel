@extends('layouts.app')

@section('title','Registrar Ponto')
@section('page-title','Registrar Ponto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('aluno.dashboard') }}">Início</a></li>
    <li class="breadcrumb-item active">Registrar Ponto</li>
@endsection

@push('styles')
<style>
    #map { height: 280px; border-radius: 6px; border: 1px solid #dee2e6; }
    #geo-status { font-size: 0.875rem; }
    .geo-card { border-left: 4px solid #007bff; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>Registrar presença
                </h3>
            </div>
            <div class="card-body">

                {{-- Info do aluno --}}
                <div class="callout callout-info geo-card mb-4">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" width="40%">Aprendiz</td>
                            <td><strong>{{ $aluno->nome }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Empresa</td>
                            <td>{{ $aluno->empresa->nome ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Instituição</td>
                            <td>{{ $aluno->instituicao->nome ?? '—' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Turma</td>
                            <td>{{ $aluno->instituicao->cod_turma ?? '—' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- Localização --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="mb-0 font-weight-bold">
                            <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                            Localização GPS
                        </label>
                        <button type="button" id="btn-get-location" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-crosshairs mr-1"></i>Capturar localização
                        </button>
                    </div>

                    <div id="geo-status" class="text-muted mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Clique em "Capturar localização" para registrar sua posição.
                    </div>

                    {{-- Mapa OpenStreetMap via Leaflet --}}
                    <div id="map" class="d-none mb-2"></div>
                </div>

                <form action="{{ route('aluno.ponto.store') }}" method="POST" id="form-ponto">
                    @csrf
                    <input type="hidden" name="localizacao" id="localizacao">

                    <div class="form-group">
                        <label>Coordenadas capturadas</label>
                        <input type="text" id="coord-display" class="form-control" readonly
                               placeholder="Aguardando captura de GPS...">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('aluno.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i>Voltar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg" id="btn-registrar">
                            <i class="fas fa-clock mr-2"></i>Confirmar Registro
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Leaflet.js para o mapa --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map, marker;

document.getElementById('btn-get-location').addEventListener('click', function () {
    const statusEl  = document.getElementById('geo-status');
    const mapDiv    = document.getElementById('map');
    const coordDisp = document.getElementById('coord-display');
    const locInput  = document.getElementById('localizacao');
    const btnReg    = document.getElementById('btn-registrar');

    if (!navigator.geolocation) {
        statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>Geolocalização não suportada neste navegador.</span>';
        return;
    }

    statusEl.innerHTML = '<span class="text-primary"><i class="fas fa-spinner fa-spin mr-1"></i>Obtendo localização...</span>';
    this.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function (pos) {
            const lat = pos.coords.latitude.toFixed(6);
            const lng = pos.coords.longitude.toFixed(6);
            const coords = `${lat}, ${lng}`;

            // Preenche os campos
            locInput.value  = coords;
            coordDisp.value = coords;

            statusEl.innerHTML = `<span class="text-success"><i class="fas fa-check-circle mr-1"></i>Localização capturada com sucesso! Precisão: ~${Math.round(pos.coords.accuracy)}m</span>`;

            // Exibe mapa
            mapDiv.classList.remove('d-none');

            if (!map) {
                map = L.map('map').setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                marker = L.marker([lat, lng]).addTo(map)
                    .bindPopup('<strong>Você está aqui</strong>').openPopup();
            } else {
                map.setView([lat, lng], 16);
                marker.setLatLng([lat, lng]).openPopup();
            }

            document.getElementById('btn-get-location').disabled  = false;
            document.getElementById('btn-get-location').innerHTML  = '<i class="fas fa-sync mr-1"></i>Atualizar localização';
        },
        function (err) {
            const msgs = {
                1: 'Permissão de localização negada. Permita o acesso nas configurações do navegador.',
                2: 'Posição indisponível. Tente novamente.',
                3: 'Tempo limite esgotado. Tente novamente.',
            };
            statusEl.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-circle mr-1"></i>${msgs[err.code] || 'Erro desconhecido.'}</span>`;
            document.getElementById('btn-get-location').disabled = false;
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
});
</script>
@endpush
