@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.riwayatPesanan.title') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-riwayatPesanan">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.riwayatPesanan.fields.order_no') }}
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
            ajax: "{{ route('admin.riwayatPesanan.index') }}",
            columns: [
                { data: 'placeholder', name: 'placeholder' },
                { data: 'order_no', name: 'order_no' },
                { data: 'warga_binaan', name: 'warga_binaan' },
                { data: 'total_pembayaran', name: 'total_pembayaran' },
                { data: 'created_at', name: 'created_at' },
                { data: 'expired_at', name: 'expired_at' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: '{{ trans('global.actions') }}' },
            ],
            orderCellsTop: true,
            order: [[ 4, 'desc' ]],
            pageLength: 100,
        };

        let table = $('.datatable-riwayatPesanan').DataTable(dtOverrideGlobals);

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });

</script>
@endsection
