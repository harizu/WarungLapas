@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Riwayat Pemesanan
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Penjualan">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        Order No
                    </th>
                    <th>
                        Warga Binaan
                    </th>
                    <th>
                        Pembeli
                    </th>
                    <th>
                        Total Pembelian
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
$(function () {

  let dtOverrideGlobals = {
    buttons: [],
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.riwayat-pembelians.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'trx_no', name: 'trx_no' },
{ data: 'product_nama_produk', name: 'product.nama_produk' },
{ data: 'qty', name: 'qty' },
{ data: 'total_price', name: 'total_price' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-Penjualan').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      // $($.fn.dataTable.tables(true)).DataTable()
      //     .columns.adjust();
  });
  
});

</script>
@endsection