<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPenjualanRequest;
use App\Http\Requests\StorePenjualanRequest;
use App\Http\Requests\UpdatePenjualanRequest;
use App\Models\Order;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Services\OrderService;
use App\Services\ProdukService;
use DB;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    protected $orderService;
    protected $produkService;

    public function __construct(OrderService $orderService, ProdukService $produkService)
    {
        $this->orderService = $orderService;
        $this->produkService = $produkService;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with('wargaBinaan')
                ->select('*');

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('admin.penjualans.partials.datatablesActions', compact('row'));
            });

            $table->editColumn('order_no', function ($row) {
                return $row->order_no ? $row->order_no : "";
            });
            $table->editColumn('buyer', function ($row) {
                return $row->user ? $row->user->name : "";
            });
            $table->editColumn('warga_binaan', function ($row) {
                return $row->wargaBinaan ? $row->wargaBinaan->nama_warga_binaan : "";
            });
            $table->editColumn('total_pembayaran', function ($row) {
                return $row->total_pembayaran ? $row->total_pembayaran : "";
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d M Y H:i:s', strtotime($row->created_at)) : "";
            });
            $table->editColumn('expired_at', function ($row) {
                if ($row->status == Order::STATUS_NEW_ORDER) {
                    return $row->expired_at ? date('d M Y H:i:s', strtotime($row->expired_at)) : "";
                }

                return "";
            });
            $table->editColumn('status', function ($row) {
                return view('admin.order.partials.status', ['order' => $row]);
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.penjualans.index');
    }

    public function create()
    {
        abort_if(Gate::denies('penjualan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Produk::all()->pluck('nama_produk', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.penjualans.create', compact('products'));
    }

    public function store(StorePenjualanRequest $request)
    {
        $penjualan = Penjualan::create($request->all());

        return redirect()->route('admin.penjualans.index');
    }

    public function edit(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Produk::all()->pluck('nama_produk', 'id')->prepend(trans('global.pleaseSelect'), '');

        $penjualan->load('product');

        return view('admin.penjualans.edit', compact('products', 'penjualan'));
    }

    public function update(UpdatePenjualanRequest $request, Penjualan $penjualan)
    {
        $penjualan->update($request->all());

        return redirect()->route('admin.penjualans.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('wargaBinaan');

        return view('admin.penjualans.show', compact('order'));
    }

    public function destroy(Penjualan $penjualan)
    {
        abort_if(Gate::denies('penjualan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $penjualan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPenjualanRequest $request)
    {
        Penjualan::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function reject(Order $order)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$this->orderService->reject($order)) {
            return redirect()->back()->with('error', 'Gagal memproses penolakan pesanan #' . $order->order_no);
        }

        return redirect()->back()->with('message', 'Pesanan #' . $order->order_no . ' ditolak');
    }

    public function accept(Order $order)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pdo = DB::connection()->getPdo();
        $pdo->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");

        DB::beginTransaction();
        try {
            foreach ($order->details as $detail) {
                if (!$this->produkService->checkoutStock($detail->produk_id, $detail->qty)) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Stok ' . $detail->produk->nama_produk . ' tidak mencukupi');
                }
            }

            if (!$this->orderService->accept($order)) {
                DB::rollback();
                return redirect()->back()->with('error', 'Gagal memproses pesanan #' . $order->order_no);
            }

            DB::commit();
            return redirect()->back()->with('message', 'Pesanan #' . $order->order_no . ' diterima');
        } catch (\Throwable $t) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan pada server. Coba kembali beberapa saat lagi');
        }
    }

    public function complete(Order $order)
    {
        abort_if(Gate::denies('penjualan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (!$this->orderService->complete($order)) {
            return redirect()->back()->with('error', 'Gagal menyelesaikan pesanan #'. $order->$order_no);
        }

        return redirect()->back()->with('message', 'Pesanan #' . $order->order_no . ' telah selesai');
    }
}
