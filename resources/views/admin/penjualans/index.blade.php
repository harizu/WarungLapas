@extends('layouts.admin')
@section('content')
@can('penjualan_create')
    {{-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.penjualans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.penjualan.title_singular') }}
            </a>
        </div>
    </div> --}}
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.penjualan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Penjualan">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.order_no') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.buyer') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.warga_binaan') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.total_pembayaran') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.created_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.expired_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- MODAL COMPLETE ORDER --}}
<div class="modal fade" id="modal-complete-order" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-success" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Upload Bukti Pesanan Selesai</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-success" id="order-complete-success" style="display:none">
                <strong id="order-complpete-success-message"></strong>
            </div>
            <div class="alert alert-danger" id="order-complete-alert" style="display:none;">
                <strong id="order-complete-alert-message"></strong>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" id="form-complete-order">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="dropzone" id="complete-order-attachment" name="attachment">
                    <div class="dz-message" data-dz-message><span>Klik disini untuk upload gambar</span></div>
                    <div class="fallback">
                            <input name="attachments[]" type="file" class="form-control" required multiple />
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="btn-complete-order">{{ trans('global.completeOrder') }}</button>
        </div>
      </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
    Dropzone.autoDiscover = false;

    $(function () {

        let dtOverrideGlobals = {
            buttons: [],
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.penjualans.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'order_no', name: 'order_no' },
                { data: 'buyer', name: 'buyer' },
                { data: 'warga_binaan', name: 'warga_binaan' },
                { data: 'total_pembayaran', name: 'total_pembayaran' },
                { data: 'created_at', name: 'created_at' },
                { data: 'expired_at', name: 'expired_at' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: '{{ trans('global.actions') }}' },
            ],
            orderCellsTop: true,
            order: [[ 5, 'desc' ]],
            pageLength: 100,
        };
        let table = $('.datatable-Penjualan').DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

        let completeOrderDz = $('#complete-order-attachment').dropzone({
                url: '{{ route('admin.penjualans.complete', ':id') }}',
                headers : { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                paramName: 'attachments',
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 5,
                maxFiles: 5,
                maxFilesize: 1,
                acceptedFiles: 'image/jpg, image/jpeg, image/png,',
                addRemoveLinks: true,
                init: function () {
                    let wrapperThis = this;

                    // send day off request form
                    $(document).on('click', '#btn-complete-order', function (e) {
                        e.preventDefault();
                        $(this).prop('disabled', true);
                        if (!wrapperThis.files.length) {
                            return alert('Foto pesanan selesai wajib di isi');
                        }

                        return wrapperThis.processQueue();
                    });

                    this.on("processing", function (file) {
                        wrapperThis.options.url = $('#form-complete-order').attr('action');
                    });

                    this.on('sendingmultiple', function (data, xhr, formData) {
                        formData.append("_method", "put");
                    });

                    this.on('errormultiple', function (files, response, xhr) {
                        let errors = response.errors
                        let errorKeys = Object.keys(errors)

                        if (errorKeys.length) {
                            let errorMessage = '<ul>'

                            errorKeys.forEach(function (key) {
                                let errorMessageBags = errors[key]

                                errorMessageBags.forEach(function (message) {
                                    errorMessage += '<li>' + message + '</li>'
                                })
                            })

                            errorMessage += '</ul>'

                            $('#order-complete-alert-message').html(errorMessage);
                            $('#order-complete-alert').show();

                            wrapperThis.removeAllFiles();

                            let dropzoneFilesCopy = files.slice(0);
                            $.each(dropzoneFilesCopy, function(_, file) {
                               if (file.status === Dropzone.ERROR) {
                                    file.status = undefined;
                                    file.accepted = undefined;
                               }
                               wrapperThis.addFile(file);
                            })

                            $('#btn-complete-order').prop('disabled', false);
                        }
                    });

                    this.on('successmultiple', function (files, response) {
                        wrapperThis.removeAllFiles();

                        if (!response.success) {
                            let dropzoneFilesCopy = files.slice(0);

                            $.each(dropzoneFilesCopy, function(_, file) {
                            if (file.status === Dropzone.ERROR) {
                                    file.status = undefined;
                                    file.accepted = undefined;
                            }
                            wrapperThis.addFile(file);
                            })

                            $('#order-complete-alert-message').html(response.message);
                            $('#order-complete-alert').show();

                            return $('#btn-complete-order').prop('disabled', false);
                        }

                        $('#order-complete-alert').hide();
                        $('#order-complpete-success-message').html(response.message);
                        $('#order-complete-success').show()

                        table.ajax.reload();
                        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
                        setTimeout(function () {
                            $('#modal-complete-order').modal('hide');
                            $('#btn-complete-order').prop('disabled', false)
                            $('#order-complete-success').hide()
                        }, 2000)
                    });
                }
            });

        $(document).on('click', '.complete-order', function () {
            let route = $(this).data('route');

            $('#form-complete-order').attr('action', route);
        });
    });

</script>
@endsection
