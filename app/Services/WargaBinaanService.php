<?php

namespace App\Services;

use App\Models\WargaBinaan;

class WargaBinaanService
{
    public function getIdByNomorRegistrasi(string $nomor_registrasi)
    {
        return WargaBinaan::where('nomor_registrasi', $nomor_registrasi)->value('id');
    }

    /**
     * Get detail warga binaan using
     *
     * @param string $nomor_registrasi
     *
     * @return App\Models\WargaBinaan
     */
    public function getWargaBinaanDetailByNomorRegistrasi(string $nomor_registrasi): array
    {
        $wargaBinaan = WargaBinaan::with('media')
            ->where('nomor_registrasi', $nomor_registrasi)->first();

        if (empty($wargaBinaan)) {
            return [];
        }

        return [
            'nomor_registrasi' => $wargaBinaan->nomor_registrasi,
            'nama' => $wargaBinaan->nama_warga_binaan,
            'kasus' => $wargaBinaan->kasus,
            'foto' => [
                'thumb' => $wargaBinaan->foto ? $wargaBinaan->foto->getUrl('thumb') : '',
                'url' => $wargaBinaan->foto ? $wargaBinaan->foto->getUrl() : '',
            ],
        ];
    }
}
