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
                    <h3>STEP 2 : Masukan Pesanan Anda</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form id="pembelian-step-2" method="POST" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="step" value="2">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" data-toggle="modal" data-target="#modal-tambah-pesanan" class="btn btn-success float-right" role="button">Tambah Pesanan</a>
                            <label class="control-label d-block">Pesanan Untuk : <strong><span>{{ $warga_binaan['nama'] }}</span> (<span>{{ $warga_binaan['nomor_registrasi'] }}</span>)</strong></label>
                            
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
                                        <th width="50px">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody class="pembelian-item-container">
                                    {{-- Dynamic Itemm --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4 offset-md-8">
                            <table class="table">
                                <tr>
                                    <th>Sub Total</th>
                                    <td id="pembelian-subtotal">0</td>
                                </tr>
                                <tr>
                                    <th>Biaya Layanan</th>
                                    <td id="pembelian-biaya-layanan">0</td>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <td id="pembelian-grandtotal">0</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 justify-content-between">
                                <a href="{{ route('admin.pembelians.index', ['ref' => 'step-1']) }}" class="btn btn-danger"><< Kembali</a>
                                <button type="submit" class="btn btn-primary float-right" id="btn-submit-pesanan" disabled>Kirim Pesanan</button>
                        </div>
                    </div>
                    <input type="hidden" name="biaya_layanan" value="0">
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah-pesanan" aria-modal="true" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Pesanan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
                    <form id="form-tambah-pesanan">
                        <div class="form-group">
                            <label>Kategori</label>
                            <select name="kategori" id="tambah-pesanan-kategori" class="form-control" required>
                                <option value disabled selected>{{ trans('global.pleaseSelect') }}</option>

                                @foreach($list_kategori_produk as $value => $text)
                                <option value="{{ $value }}">{{ $text }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Produk</label>
                            <select name="produk" id="tambah-pesanan-produk" class="form-control" required>
                                <option value disabled selected>{{ trans('global.pleaseSelect') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="text" id="tambah-pesanan-harga" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="text" id="tambah-pesanan-stok" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" id="tambah-pesanan-jumlah" class="form-control input-qty" min="1" max="1" value="1" required>
                        </div>
                    </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="btn-tambah-pesanan" disabled>Tambah</button>
        </div>
      </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function () {
        $('#tambah-pesanan-kategori').on('change', function () {
            let kategori = $(this).val();
            $.ajax({
                url: "{{ route('admin.pembelians.get.produk.by.kategori', ':kategori') }}".replace(':kategori', kategori),
                accept: 'application/json',
                success: function (data) {
                    if (data.data.length) {
                        let option = "<option value disabled selected>{{ trans('global.pleaseSelect') }}</option>";

                        data.data.forEach(function (produk) {
                            option += '<option value="' + produk.id
                                +'" data-nama="' + produk.nama
                                + '" data-harga-int="'+ produk.harga.int
                                +'" data-harga-formatted="' + produk.harga.formatted
                                +'" data-stok="' + produk.stok
                                +'">' + produk.nama + '</option>';
                        });

                        $('select#tambah-pesanan-produk').html(option);
                        resetInputTambahPesanan();
                    }
                }
            });
        });

        $('#tambah-pesanan-produk').on('change', function () {
            let selected = $('option:selected', this);

            if (selected.val() == '') {
                $('#btn-tambah-pesanan').prop('disabled', true);
                resetInputTambahPesanan();
                return;
            }

            $('#btn-tambah-pesanan').prop('disabled', false);

            let hargaString = selected.data('harga-formatted');
            let stok = selected.data('stok');

            $('input#tambah-pesanan-harga').val(hargaString);
            $('input#tambah-pesanan-stok').val(stok);
            $('input#tambah-pesanan-jumlah').attr('max', stok);
        });

        $('.input-qty').on('keyup keydown change', function () {
            if (parseInt($(this).val()) < parseInt($(this).attr('min'))) {
                $(this).val($(this).attr('min'));
            }
            if (parseInt($(this).val()) > parseInt($(this).attr('max'))) {
                $(this).val($(this).attr('max'));
            }
        })

        $('#btn-tambah-pesanan').on('click', function (e) {
            e.preventDefault();

            let selected = $('#tambah-pesanan-produk option:selected');

            if (selected.val() == '') {
                $('#btn-tambah-pesanan').prop('disabled', true);
                resetFormTambahPesanan();
                return;
            }

            let id = selected.val();
            let nama = selected.data('nama');
            let harga = selected.data('harga-int');
            let stok = selected.data('stok');
            let qty = $('input#tambah-pesanan-jumlah').val();

            let itemContainer = $('.pembelian-item-container');

            if ($('tr#produk' + id, itemContainer).length) {
                let row = $('tr#produk' + id, itemContainer);
                let qtyElement = $('input[name$="[qty]"]', row)
                let initialQty = qtyElement.val();
                let finalQty = parseInt(initialQty) + parseInt(qty);

                if (finalQty > stok) {
                    alert('Anda sudah menambahkan produk ini pada batas maksimal stok.');
                    return;
                }

                qtyElement.val(finalQty);
                calculateTotal();
                resetFormTambahPesanan();
                return;
            }

            let no = $('tr', itemContainer).length + 1;
            let total = parseInt(qty) * parseInt(harga);
            let row = '<tr id="produk' + id +'" data-harga="' + harga + '" data-stok="' + stok + '">'
                + '<td class="item-no">' + no + '</td>'
                + '<td>' + nama + '<input type="hidden" name="item[' + no + '][id]" value="' + id + '"></td>'
                + '<td class="item-qty"><input type="number" class="input-qty input-qty-row" min="1" max="' + stok + '" name="item[' + no + '][qty]" value="' + qty + '" onchange="calculateTotal();"></td>'
                + '<td class="item-harga">' + harga.toLocaleString('id-ID') + '</td>'
                + '<td class="item-total">' + total.toLocaleString('id-ID') + '</td>'
                + '<td><button class="btn btn-sm btn-danger hapus-item" data-target="#produk' + id +'" role="button"><i class="fa fa-trash"></i></button></td>';

            itemContainer.append(row);
            calculateTotal();
            resetFormTambahPesanan();
            enableDisableSubmit();
        });
    });

    $(document).on('click', 'button.hapus-item', function (e) {
        e.preventDefault();
        let target = $(this).data('target');
        $('tr' + target ).remove();
        rearrangeRowPesanan();
        calculateTotal();
        enableDisableSubmit();
    });

    function resetFormTambahPesanan() {
        $('#tambah-pesanan-kategori').val('');

        let option = "<option value disabled selected>{{ trans('global.pleaseSelect') }}</option>";
        $('#tambah-pesanan-produk').val('');
        $('#tambah-pesanan-produk').html(option);

        resetInputTambahPesanan();
    }

    function resetInputTambahPesanan() {
        $('input#tambah-pesanan-harga').val('');
        $('input#tambah-pesanan-stok').val('');
        $('input#tambah-pesanan-jumlah').val(1);
    }

    function calculateTotal() {
        let subTotal = 0;
        let biayaLayanan = 0;
        let grandTotal = 0;
        let rows = $('.pembelian-item-container tr');

        $.each(rows, function (index, row) {
            let harga = $(row).data('harga');
            let qty = $('td.item-qty input', $(row)).val();

            subTotal += (parseInt(harga) * parseInt(qty));
        });

        if(subTotal > 10000){
            biayaLayanan = "<?=env('biaya_layanan_1',1000);?>";
        }else{
            biayaLayanan = "<?=env('biaya_layanan_2',500);?>";
        }

        grandTotal = subTotal + biayaLayanan;

        $('#pembelian-subtotal').text(subTotal.toLocaleString('id-ID'));
        $('#pembelian-biaya-layanan').text(biayaLayanan.toLocaleString('id-ID'));
        $('#pembelian-grandtotal').text(grandTotal.toLocaleString('id-ID'));
    }

    function rearrangeRowPesanan() {
        let rows = $('.pembelian-item-container tr');

        $.each(rows, function (index, row) {
            row = $(row);
            let no = index + 1;

            $('.item-no', row).text(no);
            $('input[name$="[id]"]', row).attr('name', 'item[' + no + '][id]');
            $('input[name$="[qty]"]', row).attr('name', 'item[' + no + '][qty]');
        });
    }

    function enableDisableSubmit()
    {
        let itemContainer = $('.pembelian-item-container');

        if ($('tr[id^="produk"]', itemContainer).length) {
            $('#btn-submit-pesanan').prop('disabled', false);
            return;
        }

        $('#btn-submit-pesanan').prop('disabled', true);
    }
</script>

@endsection
