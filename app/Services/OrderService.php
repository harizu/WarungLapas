<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Produk;
use App\Services\TransactionCodeService;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class OrderService
{
    const EXPIRATION_DATE_MODIFIER = '+3 hours';
    const TRANSACTION_TYPE = 'order';

    public function makeOrder(int $person_ordering, int $order_for, $biaya_layanan, array $items)
    {
        $order_details = $this->mapOrderDetails($items);

        $transactionCodeService = new TransactionCodeService();
        $transaction_date = Carbon::now()->toDateString();
        $created_at = Carbon::now()->toDateTimeString();
        $expired_at = Carbon::now()->add(static::EXPIRATION_DATE_MODIFIER)->toDateTimeString();

        $order_no = $transactionCodeService->generateCode(self::TRANSACTION_TYPE, $transaction_date);

        $order = Order::create([
            'order_no' => $order_no,
            'user_id' => $person_ordering,
            'warga_binaan_id' => $order_for,
            'total' => $order_details->sum('subtotal'),
            'biaya_layanan' => $biaya_layanan,
            'expired_at' => $expired_at,
            'created_at' => $created_at,
        ]);

        $order_details = $this->makeOrderDetails($order, $order_details);

        return $order;
    }

    protected function makeOrderDetails(Order $order, Collection $order_details)
    {
        $order_details = $order_details->toArray();

        return $order->details()->createMany($order_details);
    }

    protected function mapOrderDetails(array $items)
    {
        $items = collect($items);

        $produks = Produk::whereIn('id', $items->pluck('id'))->get();

        return $items->map(function ($item) use ($produks) {
            $produk = $produks->where('id', $item['id'])->first();

            return [
                'produk_id' => $produk->id,
                'qty' => $item['qty'],
                'harga' => $produk->harga,
                'subtotal' => $produk->harga * $item['qty'],
            ];
        });
    }

    public function cancel(Order $order)
    {
        if ($order->is_expired) {
            return false;
        }

        if ($order->status !== Order::STATUS_NEW_ORDER) {
            return false;
        }

        $order->status = Order::STATUS_CANCELED;

        return $order->save();
    }

    public function reject(Order $order)
    {
        if ($order->is_expired) {
            return false;
        }

        if ($order->status !== Order::STATUS_NEW_ORDER) {
            return false;
        }

        $order->status = Order::STATUS_REJECTED;

        return $order->save();
    }

    public function accept(Order $order)
    {
        if ($order->is_expired) {
            return false;
        }

        if ($order->status !== Order::STATUS_NEW_ORDER) {
            return false;
        }

        $order->status = Order::STATUS_ON_PROCESS;

        return $order->save();
    }

    public function complete(Order $order)
    {
        if ($order->status !== Order::STATUS_ON_PROCESS) {
            return false;
        }

        $order->status = Order::STATUS_COMPLETED;

        return $order->save();
    }
}
