@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.produk.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.produks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.id') }}
                        </th>
                        <td>
                            {{ $produk->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.seller') }}
                        </th>
                        <td>
                            {{ $produk->seller->nama_seller ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.kategori_produk') }}
                        </th>
                        <td>
                            {{ App\Models\Produk::KATEGORI_PRODUK_SELECT[$produk->kategori_produk] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.nama_produk') }}
                        </th>
                        <td>
                            {{ $produk->nama_produk }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.qty') }}
                        </th>
                        <td>
                            {{ $produk->qty }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.harga') }}
                        </th>
                        <td>
                            {{ $produk->harga }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.produk.fields.foto_produk') }}
                        </th>
                        <td>
                            @foreach($produk->foto_produk as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.produks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection