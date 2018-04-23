@extends('backoffice.main')

@section('page_title', $actividad->nombreActividad)

@section('subtitulo')
    Ultima modificación por
    {{ $actividad->modificadoPor->nombres OR 'N/A' }}
    {{ $actividad->modificadoPor->apellidoPaterno OR '' }}
@endsection

@section('content')
    @if (Session::has('error'))
        <div class="callout callout-danger">
            <h4>{{ Session::get('error') }}</h4>
            @php
                \Illuminate\Support\Facades\Session::remove('error');
            @endphp
        </div>
    @endif

    <form method="POST" id="formDelete"
          action="{{ action('backoffice\ActividadesController@destroy', ['id' => $actividad->idActividad]) }}">
        <input type="hidden" value="DELETE" name="_method">
        {{ csrf_field() }}
    </form>

    <actividades-show
            actividad="{{ $actividad }}"
            paises="{{ $paises }}"
            tipos="{{ $tipos }}"
            categorias="{{ $categorias }}"
            provincias="{{  $provincias }}"
            localidades="{{ $localidades }}"
            edicion={{ $edicion }}
    ></actividades-show>
    <p style="margin-bottom: 4em"></p>
@endsection

@push('additional_scripts')
    <script src="{{ asset('/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
@endpush

@push('additional_css')

@endpush

@section('footer')
    <crud-footer
            cancelar-url="/admin/actividades"
            edicion={{ $edicion }}></crud-footer>
@endsection