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
        {{ trans('cruds.pembelian.title') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                    <h3>Pemesanan Berhasil!</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>Silakan Melakukan Pembayaran ke Rekening Berikut :</p>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td width="20%">No Order</td>
                        <td width="5%">:</td>
                        <td>{{ $order_no ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Total Pembayaran</td>
                        <td>:</td>
                        <td>Rp {{ number_format($total) ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Bank</td>
                        <td>:</td>
                        <td>{{ $rekening_bank ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>No Rekening</td>
                        <td>:</td>
                        <td>{{ $rekening_no ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Atas Nama</td>
                        <td>:</td>
                        <td>{{ $rekening_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pembayaran Kadaluarsa</td>
                        <td>:</td>
                        <td>{{ $payment_expired ?? '' }}</td>
                    </tr>
                   
                </table>

            </div>
        </div>
    </div>
</div>


@endsection

