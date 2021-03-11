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
                            @if ($order->status === $order::STATUS_COMPLETED)
                                <div class="row">
                                    <div class="col-md-12"></div>
                                        @foreach ($order->order_complete_attachments as $attachment)
                                        <a href="{{ $attachment->getUrl('preview') }}" target="_blank">
                                            <img class="img-thumbnail img-bordered mx-1" src="{{ $attachment->getUrl('thumb') }}">
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
                                    <form action="{{ route('admin.penjualans.complete', $order->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-success" value="{{ trans('global.completeOrder') }}">
                                    </form>
                                @endif
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection
