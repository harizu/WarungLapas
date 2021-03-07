
<a class="btn btn-xs btn-primary" href="{{ route('admin.penjualans.show', $row->id) }}">
    {{ trans('global.view') }}
</a>

@if(!$row->is_expired && $row->status === $row::STATUS_NEW_ORDER)
    <form action="{{ route('admin.penjualans.reject', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.rejectOrder') }}">
    </form>
@endif

@if(!$row->is_expired && $row->status === $row::STATUS_NEW_ORDER)
    <form action="{{ route('admin.penjualans.accept', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-info" value="{{ trans('global.acceptOrder') }}">
    </form>
@endif

@if($row->status === $row::STATUS_ON_PROCESS)
    <form action="{{ route('admin.penjualans.complete', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-success" value="{{ trans('global.completeOrder') }}">
    </form>
@endif
