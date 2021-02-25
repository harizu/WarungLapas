@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.wargaBinaan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.warga-binaans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.id') }}
                        </th>
                        <td>
                            {{ $wargaBinaan->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.nomor_registrasi') }}
                        </th>
                        <td>
                            {{ $wargaBinaan->nomor_registrasi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.foto') }}
                        </th>
                        <td>
                            @if($wargaBinaan->foto)
                                <a href="{{ $wargaBinaan->foto->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $wargaBinaan->foto->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.nama_warga_binaan') }}
                        </th>
                        <td>
                            {{ $wargaBinaan->nama_warga_binaan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.kasus') }}
                        </th>
                        <td>
                            {{ $wargaBinaan->kasus }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.warga-binaans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection