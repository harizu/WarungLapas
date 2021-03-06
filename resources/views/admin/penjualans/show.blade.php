@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.penjualan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.penjualans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.penjualan.fields.id') }}
                        </th>
                        <td>
                            {{ $penjualan->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.penjualan.fields.trx_no') }}
                        </th>
                        <td>
                            {{ $penjualan->trx_no }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.penjualan.fields.product') }}
                        </th>
                        <td>
                            {{ $penjualan->product->nama_produk ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.penjualan.fields.qty') }}
                        </th>
                        <td>
                            {{ $penjualan->qty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.penjualan.fields.total_price') }}
                        </th>
                        <td>
                            {{ $penjualan->total_price }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.penjualans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection