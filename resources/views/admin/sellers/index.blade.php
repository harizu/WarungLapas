@extends('layouts.admin')
@section('content')
@can('seller_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.sellers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.seller.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.seller.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Seller">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.seller.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.seller.fields.nama_seller') }}
                        </th>
                        <th>
                            {{ trans('cruds.seller.fields.alamat_seller') }}
                        </th>
                        <th>
                            {{ trans('cruds.seller.fields.nomor_telp') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sellers as $key => $seller)
                        <tr data-entry-id="{{ $seller->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $seller->id ?? '' }}
                            </td>
                            <td>
                                {{ $seller->nama_seller ?? '' }}
                            </td>
                            <td>
                                {{ $seller->alamat_seller ?? '' }}
                            </td>
                            <td>
                                {{ $seller->nomor_telp ?? '' }}
                            </td>
                            <td>
                                @can('seller_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.sellers.show', $seller->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('seller_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.sellers.edit', $seller->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('seller_delete')
                                    <form action="{{ route('admin.sellers.destroy', $seller->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('seller_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.sellers.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Seller:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection