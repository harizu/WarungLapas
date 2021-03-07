@if ($order->is_expired)
    <span class="badge badge-warning">{{ $order->status_name }}</span>
@else
    @switch($order->status)
        @case($order::STATUS_CANCELED_BY_BUYER)
            <span class="badge badge-danger">{{ $order->status_name }}</span>
            @break

        @case($order::STATUS_CANCELED_BY_ADMIN)
            <span class="badge badge-danger">{{ $order->status_name }}</span>
            @break

        @case($order::STATUS_NEW_ORDER)
            <span class="badge badge-info">{{ $order->status_name }}</span>
            @break

        @case($order::STATUS_ON_PROCESS)
            <span class="badge badge-primary">{{ $order->status_name }}</span>
            @break

        @case($order::STATUS_COMPLETED)
            <span class="badge badge-success">{{ $order->status_name }}</span>
            @break

        @default
        <span class="badge badge-dark">{{ $order->status_name }}</span>
            @break
    @endswitch
@endif
