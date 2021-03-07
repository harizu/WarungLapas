
<a class="btn btn-xs btn-primary" href="{{ route('admin.riwayatPesanan.show', $row->id) }}">
    {{ trans('global.view') }}
</a>

@if(!$row->is_expired && $row->status === $row::STATUS_NEW_ORDER)
    <form action="{{ route('admin.riwayatPesanan.cancel', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.cancelOrder') }}">
    </form>
@endif
