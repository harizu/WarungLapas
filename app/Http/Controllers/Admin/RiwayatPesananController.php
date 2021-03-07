<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Produk;
use App\Services\OrderService;

use Gate;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Yajra\DataTables\Facades\DataTables;

class RiwayatPesananController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Order::with('wargaBinaan')
                ->select('*')
                ->where('user_id', $request->user()->id);

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('admin.riwayatPesanan.partials.datatablesActions', compact('row'));
            });

            $table->editColumn('order_no', function ($row) {
                return $row->order_no ? $row->order_no : "";
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
                if ($row->status === Order::STATUS_NEW_ORDER) {
                    return $row->expired_at ? date('d M Y H:i:s', strtotime($row->expired_at)) : "";
                }

                return "";
            });
            $table->editColumn('status', function ($row) {
                return view('admin.riwayatPesanan.partials.status', ['order' => $row]);
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.riwayatPesanan.index');
    }

    public function show(Order $order)
    {
        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->load('wargaBinaan');

        return view('admin.riwayatPesanan.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_if(Gate::denies('pembelian_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->orderService->cancel($order, true);

        return back();
    }
}
