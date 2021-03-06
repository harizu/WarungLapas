@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.pembelian.title') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form>
                    <div class="input-group">
                        <input type="search" id="nomor-registrasi" class="form-control" placeholder="Masukkan nomor registrasi warga binaan">
                        <div class="input-group-append">
                            <button id="get-warga-binaan" class="btn btn-default">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <form id="pembelian-step-1" action="{{ route('admin.pembelians.post') }}" method="POST" class="d-none" disabled>
                    {{ csrf_field() }}
                    <label class="control-label">Data Warga Binaan</label>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('cruds.wargaBinaan.fields.foto') }}
                                </th>
                                <td id="foto-warga-binaan">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.wargaBinaan.fields.nomor_registrasi') }}
                                </th>
                                <td id="nomor-registrasi-warga-binaan">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.wargaBinaan.fields.nama_warga_binaan') }}
                                </th>
                                <td id="nama-warga-binaan">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.wargaBinaan.fields.kasus') }}
                                </th>
                                <td id="kasus-warga-binaan">
                                    &nbsp;
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="step" value="1">
                    <input type="hidden" name="nomor_registrasi" id="field-nomor-registrasi">
                    <button type="submit" class="btn btn-primary float-right">Selanjutnya >></button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="alert-nomor-registrasi" aria-modal="true" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Info</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="alert-nomor-registrasi-text"></p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).on('input click', '#get-warga-binaan', function (e) {
        e.preventDefault();

        let nomor_registrasi = $('#nomor-registrasi').val();

        if (nomor_registrasi.length < 1) {
            $('#alert-nomor-registrasi-text').text('Mohon isi nomor registrasi terlebih dahulu.');
            return $('#alert-nomor-registrasi').modal('show');
        }

        $.ajax({
            url: "{{ route('admin.pembelians.get.warga.binaan', ':nomor_registrasi') }}".replace(':nomor_registrasi', nomor_registrasi),
            success: function (data) {
                if (!data.result) {
                    $('form#pembelian-step-1').addClass('d-none');
                    $('form#pembelian-step-1').prop('disabled', true);

                    $('#alert-nomor-registrasi-text')
                        .text(
                            'Nomor registrasi warga binaan "'
                            + nomor_registrasi
                            + '" tidak dapat ditemukan. Silahkan cek kembali nomor registrasi yang anda masukkan.'
                        );

                    return $('#alert-nomor-registrasi').modal('show');
                }

                data = data.data;

                if (data.foto.thumb.length) {
                    $('#foto-warga-binaan').html('<img src="' + data.foto.thumb + '">');
                } else {
                    $('#foto-warga-binaan').html('');
                }

                $('#nomor-registrasi-warga-binaan').text(data.nomor_registrasi);
                $('#field-nomor-registrasi').val(data.nomor_registrasi);
                $('#nama-warga-binaan').text(data.nama);
                $('#kasus-warga-binaan').text(data.kasus);

                $('form#pembelian-step-1').removeClass('d-none');
                $('form#pembelian-step-1').prop('disabled', false);
            }
        })
    });

</script>

@endsection'
