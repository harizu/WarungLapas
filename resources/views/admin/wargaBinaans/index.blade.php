@extends('layouts.admin')
@section('content')
@can('warga_binaan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.warga-binaans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.wargaBinaan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.wargaBinaan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-WargaBinaan">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.nomor_registrasi') }}
                        </th>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.foto') }}
                        </th>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.nama_warga_binaan') }}
                        </th>
                        <th>
                            {{ trans('cruds.wargaBinaan.fields.kasus') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                            <input class="search" type="text" placeholder="{{ trans('global.search') }}">
                        </td>
                        <td>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wargaBinaans as $key => $wargaBinaan)
                        <tr data-entry-id="{{ $wargaBinaan->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $wargaBinaan->id ?? '' }}
                            </td>
                            <td>
                                {{ $wargaBinaan->nomor_registrasi ?? '' }}
                            </td>
                            <td>
                                @if($wargaBinaan->foto)
                                    <a href="{{ $wargaBinaan->foto->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $wargaBinaan->foto->getUrl('thumb') }}">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $wargaBinaan->nama_warga_binaan ?? '' }}
                            </td>
                            <td>
                                {{ $wargaBinaan->kasus ?? '' }}
                            </td>
                            <td>
                                @can('warga_binaan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.warga-binaans.show', $wargaBinaan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('warga_binaan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.warga-binaans.edit', $wargaBinaan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('warga_binaan_delete')
                                    <form action="{{ route('admin.warga-binaans.destroy', $wargaBinaan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('warga_binaan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.warga-binaans.massDestroy') }}",
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
  let table = $('.datatable-WargaBinaan:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
let visibleColumnsIndexes = null;
$('.datatable thead').on('input', '.search', function () {
      let strict = $(this).attr('strict') || false
      let value = strict && this.value ? "^" + this.value + "$" : this.value

      let index = $(this).parent().index()
      if (visibleColumnsIndexes !== null) {
        index = visibleColumnsIndexes[index]
      }

      table
        .column(index)
        .search(value, strict)
        .draw()
  });
table.on('column-visibility.dt', function(e, settings, column, state) {
      visibleColumnsIndexes = []
      table.columns(":visible").every(function(colIdx) {
          visibleColumnsIndexes.push(colIdx);
      });
  })
})

</script>
@endsection