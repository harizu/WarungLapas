<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostPembelianRequest;
use App\Models\Produk;
use App\Services\OrderService;
use App\Services\ProdukService;
use App\Services\WargaBinaanService;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PembelianController extends Controller
{
    const STEP_1_SESSION_DATA_KEY = 'pembelian_step_1';

    protected $orderService;
    protected $produkService;
    protected $wargaBinaanService;

    public function __construct(OrderService $orderService, ProdukService $produkService, WargaBinaanService $wargaBinaanService)
    {
        $this->orderService       = $orderService;
        $this->produkService      = $produkService;
        $this->wargaBinaanService = $wargaBinaanService;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->get('ref') !== 'step-1' && $data = session()->get(static::STEP_1_SESSION_DATA_KEY)) {
            if (!empty($data['nomor_registrasi'])) {
                return $this->postPembelianStep1($data['nomor_registrasi']);
            }
        }

        return view('admin.pembelians.index');
    }

    public function postPembelian(PostPembelianRequest $request)
    {
        $data = $request->validated();

        switch ($data['step']) {
            case 1:
                session([
                    static::STEP_1_SESSION_DATA_KEY => [
                        'nomor_registrasi' => $data['nomor_registrasi'],
                    ],
                ]);

                return $this->postPembelianStep1($data['nomor_registrasi']);
                break;

            case 2:
                return $this->postPembelianStep2($data);
                break;

            default:
                return abort(404);
                break;
        }
    }

    private function postPembelianStep1(string $nomor_registrasi)
    {
        $warga_binaan = $this->wargaBinaanService->getWargaBinaanDetailByNomorRegistrasi($nomor_registrasi);

        if (empty($warga_binaan)) {
            session()->forget(static::STEP_1_SESSION_DATA_KEY);

            return $this->index();
        }

        $list_kategori_produk = Produk::KATEGORI_PRODUK_SELECT;

        return view('admin.pembelians.step2', [
            'warga_binaan'         => $warga_binaan,
            'list_kategori_produk' => $list_kategori_produk,
        ]);
    }

    private function postPembelianStep2(array $data)
    {
        $pdo = DB::connection()->getPdo();
        $pdo->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");

        DB::beginTransaction();
        try {
            foreach ($data['item'] as $item) {
                if (!$this->produkService->checkStockAvailability($item['id'], $item['qty'])) {
                    DB::rollback();

                    $nama_produk = $this->produkService->getNamaProdukById($item['id']);

                    return redirect()->back()->with('error', "Stok {$nama_produk} tidak tersedia.");
                }
            }

            $data_step1 = session()->get(static::STEP_1_SESSION_DATA_KEY);

            $user_id         = request()->user()->id;
            $warga_binaan_id = $this->wargaBinaanService->getIdByNomorRegistrasi($data_step1['nomor_registrasi']);

            if (!$order = $this->orderService->makeOrder($user_id, $warga_binaan_id, $data['biaya_layanan'], $data['item'])) {
                DB::rollback();
                return redirect()->back()->with('error', 'Terjadi kesalahan pada server. Coba kembali beberapa saat lagi');
            }

            DB::commit();

            session()->forget(static::STEP_1_SESSION_DATA_KEY);

            return redirect()->route('admin.pembelians.success',[
                'order_no'        => '#' . $order->order_no,
                'total'           => $order->total_pembayaran,
                'rekening_bank'   => config('transaction.rekening.bank'),
                'rekening_no'     => config('transaction.rekening.no'),
                'rekening_name'   => config('transaction.rekening.atas_nama'),
                'payment_expired' => date('d M Y H:i:s', strtotime($order->expired_at)),
            ]);
        } catch (\Throwable $t) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server. Coba kembali beberapa saat lagi');
        }
    }

    public function getWargaBinaanByNomorRegistrasi(Request $request, string $nomor_registrasi)
    {
        abort_if(!$request->ajax(), Response::HTTP_NOT_FOUND, '404 Not Found');

        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $warga_binaan = $this->wargaBinaanService->getWargaBinaanDetailByNomorRegistrasi($nomor_registrasi);

        return response()->json([
            'result' => !empty($warga_binaan),
            'data'   => $warga_binaan,
        ]);
    }

    public function getProdukListByKategori(Request $request, string $kategori)
    {
        abort_if(!$request->ajax(), Response::HTTP_NOT_FOUND, '404 Not Found');

        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $produk = $this->produkService->getAvailableProdukByKategori($kategori);

        return response()->json([
            'data' => $produk,
        ]);
    }

    public function success(Request $request)
    {
        if(count($request->all()) > 0)
        {
            return view('admin.pembelians.success',$request->all());
        }else{
            return redirect()->route('admin.pembelians.index');
        }
    }
}
