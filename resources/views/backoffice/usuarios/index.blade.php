@extends('backoffice.main')

@section('page_title', 'Usuarios')

@section('add-new')

    <span class="pull-right">
        <a href="/admin/usuarios/registrar" class="btn btn-primary btn-lg">
            <i class="fa fa-plus"></i> Registrar Usuario
        </a>
    </span>
@endsection

@section('content')
    <div class="box">
        <div class="box-body  with-border">
            <usuarios-datatable
                    api-url="/admin/ajax/usuarios/"
                    fields="{{ $fields }}"
                    sort-order="{{ $sortOrder }}"
                    placeholder-text="Buscar por nombre, apellido o documento"
                    detail-url="/admin/usuarios/"
            ></usuarios-datatable>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection