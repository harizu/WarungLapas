@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.seller.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sellers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.seller.fields.id') }}
                        </th>
                        <td>
                            {{ $seller->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seller.fields.nama_seller') }}
                        </th>
                        <td>
                            {{ $seller->nama_seller }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seller.fields.alamat_seller') }}
                        </th>
                        <td>
                            {{ $seller->alamat_seller }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.seller.fields.nomor_telp') }}
                        </th>
                        <td>
                            {{ $seller->nomor_telp }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sellers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection