@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Petunjuk Pemesanan
                </div>

                <div class="card-body">
                    <ul>
                        <li>
                            Klik menu <strong>"Belanja"</strong> pada menu di samping kiri
                        </li>
                        <li>
                            Lalu klik submenu <a href="{{ route('admin.pembelians.index') }}"><strong>"Pemesanan"</strong></a>
                        </li>
                        <li>
                            Pada langkah pertama, anda akan di minta untuk memasukkan nomor registrasi warga binaan yang akan di pesankan makanan/minuman
                        </li>
                        <li>
                            Pastikan data warga binaan yang di munculkan pada halaman sudah <strong><u>benar</u></strong>
                        </li>
                        <li>
                            Klik tombol <a href="#" class="btn btn-xs btn-primary">Selanjutnya >></a> untuk melanjutkan ke halaman pesanan untuk memilih makanan/minuman yang akan di pesan
                        </li>
                        <li>
                            Klik tombol <a href="#" class="btn btn-xs btn-success">Tambah Pesanan</a> untuk memilih jenis makanan/minuman
                        </li>
                        <li>
                            Setelah selesai menginputkan jenis makanan/minuman dan jumlah order. Klik <a href="#" class="btn btn-xs btn-primary">Kirim Pesanan</a> untuk melanjutkan pesanan ke Admin
                        </li>
                        <li>
                            Selesaikan pembayaran anda ke rekening yang tertera pada halaman selanjutnya
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection
