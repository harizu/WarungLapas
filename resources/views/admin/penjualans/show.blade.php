@extends('layouts.admin')
@section('styles')
    <style>
        .input-qty-row {
            width: 50px;
        }
    </style>
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.penjualan.title') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label d-block">Pesanan Untuk : <strong><span>{{ $order->wargaBinaan->nama_warga_binaan }}</span> (<span>{{ $order->wargaBinaan->nomor_registrasi }}</span>)</strong></label>
                            @include('admin.order.partials.status')
                            @if ($order->status == $order::STATUS_COMPLETED)
                                <div class="row">
                                    <div class="col-md-12"></div>
                                        @foreach ($order->order_complete_attachments as $attachment)
                                        <a href="{{ $attachment->getFullUrl('preview') }}" target="_blank">
                                            <img class="img-thumbnail img-bordered mx-1" src="{{ $attachment->getFullUrl('thumb') }}">
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-12 mt-2">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Produk</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="pembelian-item-container">
                                    @foreach ($order->details as $index => $detail)
                                        <tr id="produk{{ $detail->produk_id }}">
                                        <td class="item-no">{{ $index + 1 }}</td>
                                        <td>{{ $detail->produk->nama_produk }}</td>
                                        <td class="item-qty">{{ $detail->qty }}</td>
                                        <td class="item-harga">{{ number_format($detail->harga, 0, '.', ',') }}</td>
                                        <td class="item-total">{{ number_format($detail->subtotal, 0, '.', ',') }}</td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 offset-md-8">
                            <table class="table">
                                <tr>
                                    <th>Sub Total</th>
                                    <td id="pembelian-subtotal">{{ number_format($order->total, 0, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <th>Biaya Layanan</th>
                                    <td id="pembelian-biaya-layanan">{{ number_format($order->biaya_layanan, 0, '.', ',') }}</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td id="pembelian-grandtotal">{{ number_format($order->total_pembayaran, 0, '.', ',') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="biaya_layanan" value="0">
                </form>
                <div class="row">
                    <div class="col-md-12 justify-content-between">
                            <a href="{{ route('admin.penjualans.index') }}" class="btn btn-danger"><< Kembali</a>

                            <div class="float-right d-inline">
                                @if(!$order->is_expired && $order->status === $order::STATUS_NEW_ORDER)
                                    <form action="{{ route('admin.penjualans.reject', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-danger" value="{{ trans('global.rejectOrder') }}">
                                    </form>
                                @endif

                                @if(!$order->is_expired && $order->status === $order::STATUS_NEW_ORDER)
                                    <form action="{{ route('admin.penjualans.accept', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-info" value="{{ trans('global.acceptOrder') }}">
                                    </form>
                                @endif

                                @if($order->status === $order::STATUS_ON_PROCESS)
                                <a href="#" role="button" data-toggle="modal" data-target="#modal-complete-order" data-route="{{ route('admin.penjualans.complete', $order->id) }}" class="btn btn-success complete-order">{{ trans('global.completeOrder') }}</a>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script>
        Dropzone.autoDiscover = false;

        $(function () {

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
                            $('#order-complete-success').show();

                            location.reload();
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
