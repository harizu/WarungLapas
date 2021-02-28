@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.produk.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.produks.update", [$produk->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="seller_id">{{ trans('cruds.produk.fields.seller') }}</label>
                <select class="form-control select2 {{ $errors->has('seller') ? 'is-invalid' : '' }}" name="seller_id" id="seller_id" required>
                    @foreach($sellers as $id => $seller)
                        <option value="{{ $id }}" {{ (old('seller_id') ? old('seller_id') : $produk->seller->id ?? '') == $id ? 'selected' : '' }}>{{ $seller }}</option>
                    @endforeach
                </select>
                @if($errors->has('seller'))
                    <span class="text-danger">{{ $errors->first('seller') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.seller_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.produk.fields.kategori_produk') }}</label>
                <select class="form-control {{ $errors->has('kategori_produk') ? 'is-invalid' : '' }}" name="kategori_produk" id="kategori_produk" required>
                    <option value disabled {{ old('kategori_produk', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Produk::KATEGORI_PRODUK_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('kategori_produk', $produk->kategori_produk) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('kategori_produk'))
                    <span class="text-danger">{{ $errors->first('kategori_produk') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.kategori_produk_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nama_produk">{{ trans('cruds.produk.fields.nama_produk') }}</label>
                <input class="form-control {{ $errors->has('nama_produk') ? 'is-invalid' : '' }}" type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required>
                @if($errors->has('nama_produk'))
                    <span class="text-danger">{{ $errors->first('nama_produk') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.nama_produk_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="qty">{{ trans('cruds.produk.fields.qty') }}</label>
                <input class="form-control {{ $errors->has('qty') ? 'is-invalid' : '' }}" type="number" name="qty" id="qty" value="{{ old('qty', $produk->qty) }}" step="1" required>
                @if($errors->has('qty'))
                    <span class="text-danger">{{ $errors->first('qty') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.qty_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="harga">{{ trans('cruds.produk.fields.harga') }}</label>
                <input class="form-control {{ $errors->has('harga') ? 'is-invalid' : '' }}" type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}" step="0.01" required>
                @if($errors->has('harga'))
                    <span class="text-danger">{{ $errors->first('harga') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.harga_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="foto_produk">{{ trans('cruds.produk.fields.foto_produk') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto_produk') ? 'is-invalid' : '' }}" id="foto_produk-dropzone">
                </div>
                @if($errors->has('foto_produk'))
                    <span class="text-danger">{{ $errors->first('foto_produk') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.produk.fields.foto_produk_helper') }}</span>
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

@section('scripts')
<script>
    var uploadedFotoProdukMap = {}
Dropzone.options.fotoProdukDropzone = {
    url: '{{ route('admin.produks.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="foto_produk[]" value="' + response.name + '">')
      uploadedFotoProdukMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFotoProdukMap[file.name]
      }
      $('form').find('input[name="foto_produk[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($produk) && $produk->foto_produk)
      var files = {!! json_encode($produk->foto_produk) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="foto_produk[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection