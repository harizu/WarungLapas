<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Produk;
use App\Services\TransactionCodeService;

use Carbon\Carbon;

use Illuminate\Support\Collection;

class OrderService
{
    CONST TRANSACTION_TYPE = 'order';

    public function makeOrder(int $person_ordering, int $order_for, $biaya_layanan, array $items)
    {
        $order_details = $this->mapOrderDetails($items);

        $transactionCodeService = new TransactionCodeService();
        $transaction_date = Carbon::now()->toDateString();
        $order_no = $transactionCodeService->generateCode(self::TRANSACTION_TYPE, $transaction_date);

        $order = Order::create([
            'order_no' => $order_no,
            'user_id' => $person_ordering,
            'warga_binaan_id' => $order_for,
            'total' => $order_details->sum('subtotal'),
            'biaya_layanan' => $biaya_layanan,
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
}
