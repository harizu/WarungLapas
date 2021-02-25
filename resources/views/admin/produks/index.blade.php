@extends('layouts.admin')
@section('content')
@can('produk_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.produks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.produk.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.produk.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Produk">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.seller') }}
                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.nama_produk') }}
                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.qty') }}
                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.harga') }}
                        </th>
                        <th>
                            {{ trans('cruds.produk.fields.foto_produk') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produks as $key => $produk)
                        <tr data-entry-id="{{ $produk->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $produk->id ?? '' }}
                            </td>
                            <td>
                                {{ $produk->seller->nama_seller ?? '' }}
                            </td>
                            <td>
                                {{ $produk->nama_produk ?? '' }}
                            </td>
                            <td>
                                {{ $produk->qty ?? '' }}
                            </td>
                            <td>
                                {{ $produk->harga ?? '' }}
                            </td>
                            <td>
                                @foreach($produk->foto_produk as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @can('produk_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.produks.show', $produk->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('produk_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.produks.edit', $produk->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('produk_delete')
                                    <form action="{{ route('admin.produks.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('produk_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.produks.massDestroy') }}",
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
  let table = $('.datatable-Produk:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection