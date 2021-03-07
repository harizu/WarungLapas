<?php

namespace App\Services;

use App\Models\Produk;

class ProdukService
{
    public function checkStockAvailability(int $id, int $qty)
    {
        return Produk::available()
            ->where('id', $id)
            ->where('qty', '>=', $qty)
            ->exists();
    }

    public function checkoutStock(int $id, int $qty)
    {
        $produk = Produk::lockForUpdate()->find($id);

        if ($produk->qty <= 0 || $produk->qty < $qty) {
            return false;
        }

        return $produk->decrement('qty', $qty);
    }

    public function getNamaProdukById(int $id)
    {
        return Produk::withTrashed()->where('id', $id)->value('nama_produk');
    }

    public function getAvailableProdukByKategori(string $kategori): array
    {
        $produks = Produk::available()
            ->select([
                'id',
                'nama_produk as nama',
                'qty as stok',
                'harga',
            ])
            ->where('kategori_produk', $kategori)
            ->orderBy('nama_produk')
            ->get();

        return $produks->map(function ($produk) {
            return [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'stok' => $produk->stok,
                'harga' => [
                    'int' => (int) $produk->harga,
                    'formatted' => 'Rp '. number_format($produk->harga, 0, ',', '.'),
                ],
            ];
        })->toArray();
    }
}
