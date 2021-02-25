@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.wargaBinaan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.warga-binaans.update", [$wargaBinaan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nomor_registrasi">{{ trans('cruds.wargaBinaan.fields.nomor_registrasi') }}</label>
                <input class="form-control {{ $errors->has('nomor_registrasi') ? 'is-invalid' : '' }}" type="text" name="nomor_registrasi" id="nomor_registrasi" value="{{ old('nomor_registrasi', $wargaBinaan->nomor_registrasi) }}" required>
                @if($errors->has('nomor_registrasi'))
                    <span class="text-danger">{{ $errors->first('nomor_registrasi') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.wargaBinaan.fields.nomor_registrasi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="foto">{{ trans('cruds.wargaBinaan.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <span class="text-danger">{{ $errors->first('foto') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.wargaBinaan.fields.foto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nama_warga_binaan">{{ trans('cruds.wargaBinaan.fields.nama_warga_binaan') }}</label>
                <input class="form-control {{ $errors->has('nama_warga_binaan') ? 'is-invalid' : '' }}" type="text" name="nama_warga_binaan" id="nama_warga_binaan" value="{{ old('nama_warga_binaan', $wargaBinaan->nama_warga_binaan) }}" required>
                @if($errors->has('nama_warga_binaan'))
                    <span class="text-danger">{{ $errors->first('nama_warga_binaan') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.wargaBinaan.fields.nama_warga_binaan_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kasus">{{ trans('cruds.wargaBinaan.fields.kasus') }}</label>
                <input class="form-control {{ $errors->has('kasus') ? 'is-invalid' : '' }}" type="text" name="kasus" id="kasus" value="{{ old('kasus', $wargaBinaan->kasus) }}">
                @if($errors->has('kasus'))
                    <span class="text-danger">{{ $errors->first('kasus') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.wargaBinaan.fields.kasus_helper') }}</span>
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
    Dropzone.options.fotoDropzone = {
    url: '{{ route('admin.warga-binaans.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
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
      $('form').find('input[name="foto"]').remove()
      $('form').append('<input type="hidden" name="foto" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="foto"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($wargaBinaan) && $wargaBinaan->foto)
      var file = {!! json_encode($wargaBinaan->foto) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="foto" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
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