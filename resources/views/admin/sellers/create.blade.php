@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.seller.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sellers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama_seller">{{ trans('cruds.seller.fields.nama_seller') }}</label>
                <input class="form-control {{ $errors->has('nama_seller') ? 'is-invalid' : '' }}" type="text" name="nama_seller" id="nama_seller" value="{{ old('nama_seller', '') }}" required>
                @if($errors->has('nama_seller'))
                    <span class="text-danger">{{ $errors->first('nama_seller') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seller.fields.nama_seller_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="alamat_seller">{{ trans('cruds.seller.fields.alamat_seller') }}</label>
                <input class="form-control {{ $errors->has('alamat_seller') ? 'is-invalid' : '' }}" type="text" name="alamat_seller" id="alamat_seller" value="{{ old('alamat_seller', '') }}">
                @if($errors->has('alamat_seller'))
                    <span class="text-danger">{{ $errors->first('alamat_seller') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seller.fields.alamat_seller_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nomor_telp">{{ trans('cruds.seller.fields.nomor_telp') }}</label>
                <input class="form-control {{ $errors->has('nomor_telp') ? 'is-invalid' : '' }}" type="text" name="nomor_telp" id="nomor_telp" value="{{ old('nomor_telp', '') }}">
                @if($errors->has('nomor_telp'))
                    <span class="text-danger">{{ $errors->first('nomor_telp') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.seller.fields.nomor_telp_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection